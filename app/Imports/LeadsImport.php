<?php

namespace App\Imports;

use App\Http\Controllers\LeadsController;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class LeadsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private $row = 1;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->row++;
        $project_external_id =  Project::where('title', $row['project'])->first()->external_id ?? null;
        $status_id =  Status::where('title', $row['status'])->first()->id ?? null;
        $user_assigned_id =  User::where('name', $row['user'])->first()->id ?? null;
        $user_created_id =  User::where('name', $row['created_by'])->first()->id ?? null;
        $interested_in_our = $row['interested_in'] == 'our units' ? 1 : 0;
        $datetime = Carbon::parse($row['deadline']);
        $time = $datetime->format('H:i');
        $date = $datetime->toDateString();
        $data = [
            'name' => $row['name'],
            'phone_1' => $row['primary_number'],
            'phone_2' => $row['secondary_number'],
            'lead_source' => $row['lead_source'],
            'lead_sub_source' => $row['lead_sub_source'],
            'description' => $row['description'],
            'status_id' => $status_id,
            'user_assigned_id' => $user_assigned_id,
            'user_created_id' => $user_created_id,
            'deadline' => $date,
            'contact_time' => $time,
            'interested_in_our' => $interested_in_our,
            'project_external_id' => $project_external_id
        ];

        // Create an instance of the StorePostRequest
        $request = new StoreLeadRequest();

        // Manually validate the request data
        $validator = Validator::make($data, $request->rules());

        if ($validator->fails()) {
            $validator->errors()->add(
                'row',
                " in row number  " . $this->row
            );
            throw new ValidationException($validator);
        }

        $request = new StoreLeadRequest($data);

        $controller = new LeadsController();
        $controller->store($request);
    }
}
