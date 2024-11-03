<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AlternativeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'criteria_id'       => 'required|array',
            'alternative_id'    => 'required|array',
            'alternative_value' => 'required|array',
        ];

        if (Request::instance()->new_wisata_id) {
            $rules['new_wisata_id']         = 'required|exists:wisatas,id';
            $rules['new_criteria_id']       = 'required|array';
            $rules['new_jenis_id']          = 'required|exists:jenis,id';
            $rules['new_alternative_value'] = 'required|array';
        }
        return $rules;
    }
}