<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CriteriaStoreRequest extends FormRequest
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
        return [
            'nama_kriteria' => 'required|max:225|unique:criterias',
            'kategori'      => 'required',
            'keterangan'    => 'required',
            'skala1'        => 'required',
            'skala2'        => 'required',
            'skala3'        => 'required',
            'skala4'        => 'required',
            'skala5'        => 'required'
        ];
    }
}