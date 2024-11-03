<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WisataRequest;
use App\Http\Requests\Admin\WisataUpdateRequest;
use App\Models\Criteria;
use App\Models\Jenis;
use App\Models\Wisata;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    // pagination
    protected $limit = 10;
    protected $fields = array('wisatas.*');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $criterias = Criteria::all();
        // Mengambil data wisata yang telah divalidasi dan publik
        $userLevel  = auth()->user()->level;
        if ($userLevel === 'ADMIN') {
            $query = Wisata::where('validasi', '=', '2');
            // filter search
            if (request('search')) {
                $query = Wisata::join('jenis as j', 'wisatas.jenis_id', '=', 'j.id')
                    ->where('wisatas.validasi', '=', '2')
                    ->where(function ($q) {
                        $q->where('wisatas.nama_wisata', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('j.jenis_name', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('wisatas.fasilitas', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('wisatas.biaya', 'LIKE', '%' . request('search') . '%');
                    });
            }
        } elseif($userLevel === 'USER') {
            $query = Wisata::where('validasi', '=', '2')
                ->where('tampil', '=', '2');
            // filter search
            if (request('search')) {
                $query = Wisata::join('jenis as j', 'wisatas.jenis_id', '=', 'j.id')
                    ->where('wisatas.validasi', '=', '2')
                    ->where('wisatas.tampil', '=', '2')
                    ->where(function ($q) {
                        $q->where('wisatas.nama_wisata', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('j.jenis_name', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('wisatas.fasilitas', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('wisatas.biaya', 'LIKE', '%' . request('search') . '%');
                    });
            }
        }
        // Mengambil nilai-nilai alternatif dari request
        $alternatives = $request->except(['perPage', 'page']);
        // Memastikan ada nilai-nilai alternatif yang dikirim
        if (!empty($alternatives)) {
            // Memulai pembentukan subquery untuk setiap kriteria
            $query->where(function ($subquery) use ($criterias, $request) {
                foreach ($criterias as $criteria) {
                    $criteriaId = $criteria->id;
                    $alternativeValue = $request->input("criteria_$criteriaId");
                    if ($alternativeValue !== null) {
                        $subquery->whereIn('id', function ($subquery) use ($criteriaId, $alternativeValue) {
                            $subquery->select('wisata_id')
                                ->from('alternatives')
                                ->where('criteria_id', $criteriaId)
                                ->where('alternative_value', $alternativeValue);
                        });
                    }
                }
            });            
        }
        // Get value halaman yang dipilih dari dropdown
        $page = $request->query('page', 1);
        // Tetapkan opsi dropdown halaman yang diinginkan
        $perPageOptions = [5, 10, 15, 20, 25];
        // Get value halaman yang dipilih menggunakan the query parameters
        $perPage = $request->query('perPage', $perPageOptions[1]);
        // Paginasi hasil dengan halaman dan dropdown yang dipilih
        $wisatas = $query->orderBy('nama_wisata')->paginate($perPage, $this->fields, 'page', $page);
        return view('pages.admin.wisata.data', [
            'title'          => 'Data Destinasi Wisata',
            'wisatas'        => $wisatas,
            'perPageOptions' => $perPageOptions,
            'perPage'        => $perPage,
            'criterias'      => $criterias
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

        $jenises = Jenis::orderBy('jenis_name')->get();
        return view('pages.admin.wisata.create', [
            'title'   => 'Tambah Data Destinasi Wisata',
            'jenises' => $jenises,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WisataRequest $request)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }
        $validatedData = $request->validated();
        // Mengelola unggahan foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filePath = $file->store('foto_wisata', 'public');
            $validatedData['foto'] = $filePath;
        }
        Wisata::create($validatedData);
        return redirect('/dashboard/data/wisata')->with('success', 'Destinasi Wisata Baru Telah Ditambahkan!');
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

        $wisata = Wisata::FindOrFail($id);
        $jenises = Jenis::orderBy('jenis_name')->get();
        return view('pages.admin.wisata.edit', [
            'title'   => "Edit Data $wisata->nama_wisata",
            'wisata'  => $wisata,
            'jenises' => $jenises
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WisataUpdateRequest $request, $id)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }
        $validatedData = $request->validated();
        $wisata = Wisata::findOrFail($id);
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($wisata->foto) {
                \Storage::disk('public')->delete($wisata->foto);
            }
            // Simpan foto baru
            $file = $request->file('foto');
            $filePath = $file->store('foto_wisata', 'public');
            $validatedData['foto'] = $filePath;
        }
        $wisata->update($validatedData);
        return redirect('/dashboard/data/wisata')->with('success', 'Destinasi Wisata yang Dipilih Telah Diperbarui!');
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
        $wisata = Wisata::findOrFail($id);
        // Menghapus foto terkait jika ada
        if ($wisata->foto) {
            \Storage::disk('public')->delete($wisata->foto);
        }
        $wisata->delete();
        return redirect('/dashboard/data/wisata')->with('success', 'Destinasi Wisata yang Dipilih Telah Dihapus!');
    }
}