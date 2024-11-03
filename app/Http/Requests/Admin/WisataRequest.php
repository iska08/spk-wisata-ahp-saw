<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WisataRequest extends FormRequest
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
            'nama_wisata' => 'required|max:60|min:5',
            'lokasi_maps' => 'required',
            'foto'        => 'nullable|file|image|max:2048',
            'keterangan'  => 'required',
            'fasilitas'   => 'required',
            'biaya'       => 'required',
            'situs'       => 'nullable',
            'validasi'    => 'required',
            'tampil'      => 'required',
            'jenis_id'    => 'required',
            'user_id'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'foto.image' => 'The file must be an image.',
            'foto.max'   => 'The image must not be larger than 2MB.',
        ];
    }
}