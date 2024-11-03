<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\User;
use App\Models\Jenis;
use App\Http\Requests\Admin\WisataRequest;
use Illuminate\Http\Request;

class SaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->level === 'ADMIN') {
            $sarans = Wisata::join('users', 'wisatas.user_id', '=', 'users.id')
                ->join('jenis', 'jenis.id', '=', 'wisatas.jenis_id')
                ->where('users.level', '=', 'USER')
                ->select('wisatas.*', 'users.name', 'users.username')
                ->orderBy('wisatas.created_at', 'desc')
                ->get();
            $title = 'Validasi Saran Destinasi Wisata';
        } else {
            // Jika pengguna adalah user, ambil data wisata yang dibuat oleh pengguna tersebut
            $sarans = Wisata::join('users', 'wisatas.user_id', '=', 'users.id')
                ->select('wisatas.*', 'users.name', 'users.username')
                ->where('wisatas.user_id', auth()->user()->id)
                ->where('users.level', '=', 'USER')
                ->orderBy('wisatas.created_at', 'desc')
                ->get();
            $title = 'Saran Destinasi Wisata';
        }
        return view('pages.admin.saran.data', [
            'title'     => $title,
            'sarans'    => $sarans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->level !== 'USER') {
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Ijin Untuk Melakukan Tindakan Ini.');
        }

        $jenises = Jenis::orderBy('jenis_name')->get();
        return view('pages.admin.saran.create', [
            'title' => 'Tambah Saran Destinasi Wisata',
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
        $saran              = new Wisata();
        $saran->jenis_id    = $request->jenis_id;
        $saran->user_id     = auth()->user()->id;
        $saran->nama_wisata = $request->nama_wisata;
        $saran->lokasi_maps = $request->lokasi_maps;
        $saran->link_foto   = $request->link_foto;
        $saran->keterangan  = $request->keterangan;
        $saran->fasilitas   = $request->fasilitas;
        $saran->biaya       = $request->biaya;
        $saran->situs       = $request->situs;
        $saran->validasi    = $request->validasi;
        $saran->save();

        return redirect('/dashboard/sarans')->with('success', 'Saran Destinasi Wisata Berhasil Ditambahkan.');
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
        $saran      = Wisata::findOrFail($id);
        $userLevel  = auth()->user()->level;
        
        if ($userLevel === 'ADMIN') {
            $title = 'Validasi Saran Destinasi Wisata';
        } elseif($userLevel === 'USER') {
            $title = 'Edit Saran Destinasi Wisata';
        }

        $jenises = Jenis::orderBy('jenis_name')->get();
        return view('pages.admin.saran.edit', [
            'title'     => $title,
            'saran'     => $saran,
            'jenises'   => $jenises
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $saran              = Wisata::findOrFail($id);
        $saran->nama_wisata = $request->nama_wisata;
        $saran->keterangan  = $request->keterangan;
        $saran->validasi    = $request->validasi;
        $saran->save();

        return redirect('/dashboard/sarans')->with('success', 'Saran Destinasi Wisata Berhasil Diperbarui.');
    }

    public function approve(Request $request, $id)
    {
        $saran = Wisata::findOrFail($id);
        // Validasi nilai yang diperbolehkan (1 = Tolak, 2 = Terima)
        $validasi = $request->input('action') === 'approve' ? 2 : 1;
        $saran->validasi = $validasi;
        $saran->save();
        $message = $validasi === 2 ? 'Saran telah disetujui.' : 'Saran telah ditolak.';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $saran = Wisata::findOrFail($id);
        $saran->delete();

        return redirect('/dashboard/sarans')->with('success', 'Saran Destinasi Wisata yang Dipilih Berhasil Dihapus.');
    }
}
