<?php

namespace App\Http\Requests\Project;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('project-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'status_id' => 'required',
            'user_assigned_id' => 'required',
            'user_created_id' => '',
            // 'client_external_id' => 'required',
            'deadline' => '',
            'unit_type' => 'required',
            'sub_type' => Rule::requiredIf(function () {
                $allowedUnitTypes = ['villa', 'apartment', 'office'];
                return in_array($this->input('unit_type'), $allowedUnitTypes);
            }),
        ];
    }
}
