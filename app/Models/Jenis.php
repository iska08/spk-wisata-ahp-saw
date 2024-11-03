<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'jenis_name',
        'slug',
        'keterangan'
    ];

    public function wisatas()
    {
        return $this->hasMany(Wisata::class);
    }

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
                'source' => 'jenis_name'
            ]
        ];
    }
}