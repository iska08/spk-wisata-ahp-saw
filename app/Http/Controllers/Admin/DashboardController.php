<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Jenis;
use App\Models\Wisata;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $wisatas = Wisata::where('validasi', '=', '2')->orderby('nama_wisata')->get();
        return view('pages.admin.dashboard', [
            'title'     => 'Dashboard',
            'wisata'    => Wisata::where('validasi', '=', '2')->count(),
            'criterias' => Criteria::count(),
            'jenis'     => Jenis::count(),
            'users'     => User::whereNot('id', auth()->user()->id)->count(),
            'wisatas'   => $wisatas,
        ]);
    }
}