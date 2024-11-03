<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Criteria extends Model
{
    use HasFactory, Sluggable;
    protected $fillable = [
        'nama_kriteria',
        'kategori',
        'keterangan',
        'slug',
        'skala1',
        'skala2',
        'skala3',
        'skala4',
        'skala5'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama_kriteria'
            ]
        ];
    }
}