<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AlternativeStoreRequest;
use App\Http\Requests\Admin\AlternativeUpdateRequest;
use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AlternativeController extends Controller
{
    // pagination
    protected $limit = 10;
    protected $fields = array('wisatas.*', 'jenis.id as jenisId');
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // akses get
        if (auth()->user()->level === 'ADMIN' || auth()->user()->level === 'USER') {
            $alternatives = Alternative::with('user')->get();
        }
        // get wisata_id dari alternative
        $usedIds    = Alternative::select('wisata_id')->distinct()->get();
        $usedIdsFix = [];

        foreach ($usedIds as $usedId) {
            array_push($usedIdsFix, $usedId->wisata_id);
        }
        // menampilkan data alternatif
        $alternatives = Wisata::join('jenis', 'jenis.id', '=', 'wisatas.jenis_id')
            ->whereIn('wisatas.id', $usedIdsFix)
            ->orderBy('wisatas.nama_wisata')
            ->with('alternatives');
        // filter search
        if (request('search')) {
            $alternatives = Wisata::join('jenis', 'jenis.id', '=', 'wisatas.jenis_id')
                ->where('wisatas.nama_wisata', 'LIKE', '%' . request('search') . '%')
                ->orWhere('jenis.jenis_name', 'LIKE', '%' . request('search') . '%')
                ->whereIn('wisatas.id', $usedIdsFix)
                ->with('alternatives');
        }
        // wisata list tambah
        $wisatasList = Wisata::join('jenis', 'jenis.id', '=', 'wisatas.jenis_id')
            ->whereNotIn('wisatas.id', $usedIdsFix)
            ->where('wisatas.validasi', 2)
            ->orderBy('jenis.id')
            ->orderBy('wisatas.nama_wisata', 'ASC')
            ->get(['wisatas.*', 'jenis.id as jenisId'])
            ->groupBy('jenis.jenis_name');
        // Get value halaman yang dipilih dari dropdown
        $page = $request->query('page', 1);
        // Tetapkan opsi dropdown halaman yang diinginkan
        $perPageOptions = [5, 10, 15, 20, 25];
        // Get value halaman yang dipilih menggunaakan the query parameters
        $perPage = $request->query('perPage', $perPageOptions[1]);
        // Paginasi hasil dengan halaman dan dropdown yang dipilih
        $alternatives = $alternatives->paginate($perPage, $this->fields, 'page', $page);
        return view('pages.admin.alternatif.data', [
            'title'          => 'Data Alternatif',
            'alternatives'   => $alternatives,
            'criterias'      => Criteria::all(),
            'wisata_list'    => $wisatasList,
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlternativeStoreRequest $request)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        // menyimpan input destinasi wisata dengan jenis wisata
        $pisah    = explode(" ", $request->wisata_id);
        // explode(" ", $request->wisata_id);
        $validate = $request->validated();
        foreach ($validate['criteria_id'] as $key => $criteriaId) {
            $data = [
                'wisata_id'         => $pisah[0],
                'criteria_id'       => $criteriaId,
                'jenis_id'          => $pisah[1],
                'alternative_value' => $validate['alternative_value'][$key],
            ];
            Alternative::create($data);
        }
        return redirect('/dashboard/data/alternatif')->with('success', 'Alternatif Baru Telah Ditambahkan!');
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
    public function edit(Alternative $alternatif)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }
        
        // cek apakah ada kriteria baru yang belum diisi oleh pengguna
        $selectedCriteria  = Alternative::where('wisata_id', $alternatif->wisata_id)->pluck('criteria_id');
        $newCriterias      = Criteria::whereNotIn('id', $selectedCriteria)->get();
        $alternatives      = Wisata::where('id', $alternatif->wisata_id)->with('alternatives', 'alternatives.criteria')->first();
        return view('pages.admin.alternatif.edit', [
            'title'        => "Edit Nilai $alternatives->nama_wisata",
            'alternatives' => $alternatives,
            'newCriterias' => $newCriterias
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AlternativeUpdateRequest $request, Alternative $alternatif)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }
        
        $pisah    = explode(" ", $request->new_wisata_id);
        $validate = $request->validated();
        // masukkan nilai alternatif baru jika ada kriteria baru
        if ($validate['new_wisata_id'] ?? false) {
            foreach ($validate['new_criteria_id'] as $key => $newCriteriaId) {
                $data = [
                    'wisata_id'         => $pisah[0],
                    'jenis_id'          => $validate['new_jenis_id'],
                    'criteria_id'       => $newCriteriaId,
                    'alternative_value' => $validate['new_alternative_value'][$key],
                ];
                Alternative::create($data);
            }
        }
        foreach ($validate['criteria_id'] as $key => $criteriaId) {
            $data = [
                'criteria_id'       => $criteriaId,
                'alternative_value' => $validate['alternative_value'][$key],
            ];
            Alternative::where('id', $validate['alternative_id'][$key])->update($data);
        }
        return redirect('/dashboard/data/alternatif')->with('success', 'Alternatif yang Dipilih Telah Diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alternative $alternatif)
    {
        if (auth()->user()->level !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        Alternative::where('wisata_id', $alternatif->wisata_id)->delete();
        return redirect('/dashboard/data/alternatif')->with('success', 'Alternatif yang Dipilih Telah Dihapus!');
    }
}