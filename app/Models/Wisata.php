<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_id',
        'user_id',
        'nama_wisata',
        'lokasi_maps',
        'foto',
        'keterangan',
        'fasilitas',
        'biaya',
        'situs',
        'validasi',
        'tampil',
    ];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }

    public function alternatives()
    {
        return $this->hasMany(Alternative::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}