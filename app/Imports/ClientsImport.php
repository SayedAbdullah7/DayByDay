<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Http\Controllers\ClientsController;
use App\Http\Requests\Client\StoreClientRequest;
use App\Models\Industry;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
// use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ClientsImport implements ToModel, WithHeadingRow,SkipsEmptyRows
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
        // return $row;
        $industry_id = Industry::where('name', $row['industry'])->first()->id??null;
        $user_id =  User::where('name', $row['user'])->first()->id??null;
        $data = [
            'name' => $row['name'],
            'email' => $row['email'],
            'primary_number' =>  $row['primary_number'],
            'secondary_number' =>  $row['secondary_number'],

            'vat' =>  $row['vat'],
            'company_name' => $row['company_name'],
            'address' =>  $row['address'],
            'zipcode' =>  $row['zipcode'],
            'city' =>  $row['city'],
            'company_type' =>  $row['company_type'],
            'industry_id' =>  $industry_id,
            'user_id' =>  $user_id,

        ];

        // Create an instance of the StorePostRequest
        $request = new StoreClientRequest();

        // Manually validate the request data
        $validator = Validator::make($data, $request->rules());

        if ($validator->fails()) {
            $validator->errors()->add(
                'row',
                " in row number  " . $this->row
            );
            throw new ValidationException($validator);
        }

        $request = new StoreClientRequest($data);

        // return dd($request->validate());

        $controller = new ClientsController();
        $controller->store($request,true);

        // return new Client([

        // ]);
    }

    // public function rules(): array
    // {
    //     $request = new StoreClientRequest();
    //     $rules = $request->rules();
    //     $rules['user'] = $rules['user_id'];
    //     $rules['industry'] = $rules['industry_id'];

    //     unset($rules['industry_id']);
    //     unset($rules['user_id']);
    //     return $rules;
    // }

    // public function customValidationMessages()
    // {
    //     return [];
    // }

    // public function onFailure(Failure ...$failures)
    // {
    //     $validationErrors = [];

    //     foreach ($failures as $failure) {
    //         $row = $failure->row();
    //         $errors = $failure->errors();

    //         foreach ($errors as $attribute => $messages) {
    //             $validationErrors[] = [
    //                 'row' => $row,
    //                 'attribute' => $attribute,
    //                 'messages' => $messages,
    //             ];
    //         }
    //     }

    //     // You can handle the validation errors as per your requirements,
    //     // such as logging, storing in a database, or returning a response.
    //     // For example, you can store the validation errors in a session
    //     // flash message and redirect back to the import page.
    //     session()->flash('import_errors', $validationErrors);
    //     return redirect()->back();
    // }
}
