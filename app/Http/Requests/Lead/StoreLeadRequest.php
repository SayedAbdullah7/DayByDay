<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('lead-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'phone_1' => 'numeric|required',
            'lead_source' => 'required',
            'lead_sub_source' => 'required',
            'description' => 'required',
            'status_id' => 'required',
            'user_assigned_id' => 'required',
            'user_created_id' => '',
            'project_external_id' => 'required',
            'deadline' => 'required'
        ];
    }
}
