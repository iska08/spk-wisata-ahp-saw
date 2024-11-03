<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JenisStoreRequest;
use App\Http\Requests\Admin\JenisUpdateRequest;
use App\Models\Jenis;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JenisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // mengurutkan
        $jenis = Jenis::orderby('jenis_name')->get();
        return view('pages.admin.jenis.data', [
            'title'   => 'Data Jenis Wisata',
            'wisatas' => '',
            'jenises' => $jenis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        return view('pages.admin.jenis.create', [
            'title' => 'Tambah Jenis Wisata',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisStoreRequest $request)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        $validatedData = $request->validate([
            'jenis_name' => 'required|max:30|unique:jenis',
            'keterangan' => 'required',
        ]);
        $request['slug'] = Str::slug($request->jenis_name, '-');
        Jenis::create($validatedData);
        return redirect('/dashboard/data/jenis')->with('success', "Tambah Jenis Wisata Baru Berhasil");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        $jenis = Jenis::FindOrFail($id);
        return view('pages.admin.jenis.edit', [
            'title'   => "Edit Jenis $jenis->jenis_name",
            'jenises' => $jenis,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(JenisUpdateRequest $request, $id)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        $validatedData = $request->validated();
        $validatedData['slug'] = Str::slug($validatedData['jenis_name'], '-');
        $item = Jenis::findOrFail($id);
        $item->update($validatedData);
        return redirect('/dashboard/data/jenis')->with('success', 'Jenis Wisata yang Dipilih Telah Diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }
        
        $jenis = Jenis::findOrFail($id);
        $jenis->delete();
        return redirect('/dashboard/data/jenis')->with('success', 'Jenis Wisata yang Dipilih Telah Dihapus!');
    }

    public function wisatas(Jenis $jenis)
    {
        return view('pages.admin.jenis.detail', [
            'title'   => $jenis->jenis_name,
            'wisatas' => $jenis->wisatas,
            'active'  => 'jenis',
            'jenis'   => $jenis->jenis_name,
        ]);
    }
}