<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon;
use Session;
use Datatables;
use App\Models\Lead;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Http\Requests;
use App\Models\Client;
use App\Models\Project;
use App\Models\Status;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\Invoice\InvoiceCalculator;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadFollowUpRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB as MyBD;

class LeadsController extends Controller
{
    const CREATED = 'created';
    const UPDATED_STATUS = 'updated_status';
    const UPDATED_DEADLINE = 'updated_deadline';
    const UPDATED_ASSIGN = 'updated_assign';

    public function __construct()
    {
        $this->middleware('lead.create', ['only' => ['create']]);
        $this->middleware('lead.assigned', ['only' => ['updateAssign']]);
        $this->middleware('lead.update.status', ['only' => ['updateStatus']]);
    }


    public function index()
    {
        return view('leads.index')
            ->withStatuses(Status::typeOfLead()->get());;
    }

    /**
     * Data for Data tables
     * @return mixed
     */
    public function leadsJson()
    {
        $leads = Lead::with(['user', 'creator', 'project', 'status', 'comments' => function ($query) {
            $query->latest()->take(1);
        }])->get();

        $leads->map(function ($item) {
            return [$item['visible_deadline_date'] = $item['deadline']->format(carbonDate()), $item["visible_deadline_time"] = $item['deadline']->format(carbonTime())];
        });
        $statuses = Status::get();
        $users = User::get()->makeVisible(['id']);
        $data = [
            'leads' => $leads->toArray(),
            'statuses' => $statuses->toArray(),
            'users' => $users->toArray(),
        ];
        return json_encode($data);
        
        return $leads->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_external_id = null)
    {
        $project =  Project::whereExternalId($client_external_id);
        return view('leads.create')
            ->withUsers(User::with(['department'])->get()->pluck('nameAndDepartmentEagerLoading', 'id'))
            ->withProjects(Project::pluck('title', 'external_id'))
            ->withProject($project ?: null)
            ->withStatuses(Status::typeOfLead()->pluck('title', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLeadRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeadRequest $request)
    {

        if ($request->project_external_id) {
            $project = Project::whereExternalId($request->project_external_id);
        }

        $lead = Lead::create(
            [
                'name' => $request->name,
                'phone_1' => $request->phone_1,
                'phone_2' => $request->phone_2,
                'lead_source' => $request->lead_source,
                'lead_sub_source' => $request->lead_sub_source,
                'description' => clean($request->description),
                'user_assigned_id' => $request->user_assigned_id,
                'deadline' => Carbon::parse($request->deadline . " " . $request->contact_time . ":00"),
                'status_id' => $request->status_id,
                'user_created_id' => auth()->id(),
                'external_id' => Uuid::uuid4()->toString(),
                'interested_in_our' => $request->interested_in_our,
                'project_id' => $request->interested_in_our ? $project->id : null
            ]
        );

        event(new \App\Events\LeadAction($lead, self::CREATED));
        session()->flash('flash_message', __('Lead successfully added'));
        return redirect()->route('leads.show', $lead->external_id);
    }

    public function destroy(Lead $lead, Request $request)
    {
        $deleteOffers = $request->delete_offers ? true : false;
        if ($lead->offers && $deleteOffers) {
            $lead->offers()->delete();
        } elseif ($lead->offers) {
            foreach ($lead->offers as $offer) {
                $offer->update([
                    'source_id' => null,
                    'source_type' => null,
                ]);
            }
        }

        $lead->delete();

        session()->flash('flash_message', __('Lead deleted'));
        return redirect()->back();
    }

    public function destroyJson(Lead $lead, Request $request)
    {
        $deleteOffers = $request->delete_offers ? true : false;
        if ($lead->offers && $deleteOffers) {
            $lead->offers()->delete();
        } elseif ($lead->offers) {
            foreach ($lead->offers as $offer) {
                $offer->update([
                    'source_id' => null,
                    'source_type' => null,
                ]);
            }
        }

        $lead->delete();

        return response('OK');
    }

    public function updateAssign($external_id, Request $request)
    {
        $lead = $this->findByExternalId($external_id);
        $input = $request->get('user_assigned_id');
        $input = array_replace($request->all());
        $lead->fill($input)->save();
        if($request->jsonResponse){
            return response()->json(['status'=>true,'msg'=>'success']);
        }   
        event(new \App\Events\LeadAction($lead, self::UPDATED_ASSIGN));
        Session()->flash('flash_message', __('New user is assigned'));
        return redirect()->back();
    }

    /**
     * Update the follow up date (Deadline)
     * @param UpdateLeadFollowUpRequest $request
     * @param $external_id
     * @return mixed
     */
    public function updateFollowup(UpdateLeadFollowUpRequest $request, $external_id)
    {
        if (!auth()->user()->can('lead-update-deadline')) {
            session()->flash('flash_message_warning', __('You do not have permission to change task deadline'));
            return redirect()->route('tasks.show', $external_id);
        }
        $lead = $this->findByExternalId($external_id);
        $lead->fill(['deadline' => Carbon::parse($request->deadline . " " . $request->contact_time . ":00")])->save();
        event(new \App\Events\LeadAction($lead, self::UPDATED_DEADLINE));
        Session()->flash('flash_message', __('New follow up date is set'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $external_id
     * @return \Illuminate\Http\Response
     */
    public function show($external_id)
    {
        $lead = $this->findByExternalId($external_id);

        $offers = $lead->offers->map(function ($offer) {
            return new InvoiceCalculator($offer);
        });

        return view('leads.show')
            ->withLead($lead)
            ->withOffers($offers)
            ->withUsers(User::with(['department'])->get()->pluck('nameAndDepartmentEagerLoading', 'id'))
            ->withCompanyname(Setting::first()->company)
            ->withStatuses(Status::typeOfLead()->pluck('title', 'id'));
    }

    /**
     * Complete lead
     * @param $external_id
     * @param Request $request
     * @return mixed
     */
    public function updateStatus($external_id, Request $request)
    {
        if (!auth()->user()->can('lead-update-status')) {
            session()->flash('flash_message_warning', __('You do not have permission to change lead status'));
            return redirect()->route('tasks.show', $external_id);
        }
        $lead = $this->findByExternalId($external_id);
        if (isset($request->closeLead) && $request->closeLead === true) {
            $lead->status_id = Status::typeOfLead()->where('title', 'Closed')->first()->id;
            $lead->save();
        } elseif (isset($request->openLead) && $request->openLead === true) {
            $lead->status_id = Status::typeOfLead()->where('title', 'Open')->first()->id;
            $lead->save();
        } else {
            $lead->fill($request->all())->save();
        }
        if($request->jsonResponse){
            return response()->json(['status'=>true,'msg'=>'success']);
        }
        event(new \App\Events\LeadAction($lead, self::UPDATED_STATUS));
        Session()->flash('flash_message', __('Lead status updated'));
        return redirect()->back();
    }

    public function convertToOrder(Lead $lead)
    {
        $invoice = $lead->convertToOrder();
        return $invoice->external_id;
    }
    /**
     * @param $external_id
     * @return mixed
     */
    public function findByExternalId($external_id)
    {
        return Lead::whereExternalId($external_id)->firstOrFail();
    }

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        MyBD::beginTransaction();

        try {
            Excel::import(new \App\Imports\LeadsImport, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            // return dd($failures);
            // Handle the validation failures here
            return redirect()->back();
        }
        MyBD::commit();


        // Import successful, handle the response as needed
        session()->flash('flash_message', __('Lead successfully imported'));
    }
}
