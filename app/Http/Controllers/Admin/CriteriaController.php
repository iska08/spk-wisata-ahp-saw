<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CriteriaStoreRequest;
use App\Http\Requests\Admin\CriteriaUpdateRequest;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CriteriaController extends Controller
{
    // pagination
    protected $limit = 10;
    protected $fields = array('criterias.*');
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // menampilkan data kriteria
        $criterias = Criteria::orderby('id');
        // filter search
        if (request('search')) {
            $criterias = Criteria::where('nama_kriteria', 'LIKE', '%' . request('search') . '%')
                ->orWhere('slug', 'LIKE', '%' . request('search') . '%')
                ->orWhere('kategori', 'LIKE', '%' . request('search') . '%')
                ->orWhere('keterangan', 'LIKE', '%' . request('search') . '%')
                ->orWhere('skala1', 'LIKE', '%' . request('search') . '%')
                ->orWhere('skala2', 'LIKE', '%' . request('search') . '%')
                ->orWhere('skala3', 'LIKE', '%' . request('search') . '%')
                ->orWhere('skala4', 'LIKE', '%' . request('search') . '%')
                ->orWhere('skala5', 'LIKE', '%' . request('search') . '%');
        }
        // Get value halaman yang dipilih dari dropdown
        $page = $request->query('page', 1);
        // Tetapkan opsi dropdown halaman yang diinginkan
        $perPageOptions = [5, 10, 15, 20, 25];
        // Get value halaman yang dipilih menggunaakan the query parameters
        $perPage = $request->query('perPage', $perPageOptions[1]);
        // Paginasi hasil dengan halaman dan dropdown yang dipilih
        $criterias = $criterias->paginate($perPage, $this->fields, 'page', $page);
        return view('pages.admin.kriteria.data', [
            'title'          => 'Data Kriteria',
            'criterias'      => $criterias,
            'perPageOptions' => $perPageOptions,
            'perPage'        => $perPage
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

        return view('pages.admin.kriteria.create', ['title' => 'Tambah Kriteria',]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriteriaStoreRequest $request)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        $validatedData   = $request->validated();
        $request['slug'] = Str::slug($request->nama_kriteria, '-');
        Criteria::create($validatedData);
        return redirect('/dashboard/data/kriteria')->with('success', 'Kriteria Baru Telah Ditambahkan!');
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

        $kriterium = Criteria::FindOrFail($id);
        return view('pages.admin.kriteria.edit', [
            'title'    => "Edit Kriteria $kriterium->nama_kriteria",
            'criteria' => $kriterium,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriteriaUpdateRequest $request, $id)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }
        
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nama_kriteria'], '-');
        $item = Criteria::findOrFail($id);
        $item->update($data);
        return redirect('/dashboard/data/kriteria')->with('success', 'Kriteria yang Dipilih Telah Diperbarui!');
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

        $kriterium = Criteria::findOrFail($id);
        $kriterium->delete();
        return redirect('/dashboard/data/kriteria')->with('success', 'Kriteria yang Dipilih Telah Dihapus!');
    }
}