<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getKeyName()
    {
        return 'wisata_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }

    public function wisataList()
    {
        return $this->belongsTo(Wisata::class, 'wisata_id');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    // Nilai Pembagi
    public static function getDividerByCriteria($criterias)
    {
        $dividers = [];
        foreach ($criterias as $criteria) {
            if ($criteria->kategori === 'BENEFIT') {
                $divider = static::where('criteria_id', $criteria->id)->max('alternative_value');
            } else if ($criteria->kategori === 'COST') {
                $divider = static::where('criteria_id', $criteria->id)->min('alternative_value');
            }
            $data = [
                'criteria_id'   => $criteria->id,
                'nama_kriteria' => $criteria->nama_kriteria,
                'kategori'      => $criteria->kategori,
                'keterangan'    => $criteria->keterangan,
                'divider_value' => floatval($divider)
            ];
            array_push($dividers, $data);
        }
        return $dividers;
    }

    // get alternative
    public static function getAlternativesByCriteria($criterias)
    {
        $results = static::with('criteria', 'wisataList', 'jenis')->whereIn('criteria_id', $criterias)->get();
        if (!$results->count()) {
            return $results;
        }
        $finalRes = [];
        foreach ($results as $result) {
            $isExists = array_search($result->wisata_id, array_column($finalRes, 'wisata_id'));
            if ($isExists !== '' && $isExists !== null && $isExists !== false) {
                array_push($finalRes[$isExists]['criteria_id'], $result->criteria->id);
                array_push($finalRes[$isExists]['criteria_name'], $result->criteria->nama_kriteria);
                array_push($finalRes[$isExists]['criteria_name'], $result->criteria->keterangan);
                array_push($finalRes[$isExists]['alternative_val'], $result->alternative_value);
            } else {
                $data = [
                    'wisata_id'       => $result->wisata_id,
                    'nama_wisata'     => $result->wisataList->nama_wisata,
                    'foto'            => $result->wisataList->foto,
                    'lokasi_maps'     => $result->wisataList->lokasi_maps,
                    'fasilitas'       => $result->wisataList->fasilitas,
                    'biaya'           => $result->wisataList->biaya,
                    'situs'           => $result->wisataList->situs,
                    'jenis_id'        => $result->jenis->id,
                    'jenis_name'      => $result->jenis->jenis_name,
                    'criteria_id'     => [$result->criteria->id],
                    'criteria_name'   => [$result->criteria->nama_kriteria],
                    'keterangan'      => [$result->criteria->keterangan],
                    'alternative_val' => [$result->alternative_value]
                ];
                array_push($finalRes, $data);
            }
        }
        return $finalRes;
    }

    public static function checkAlternativeByCriterias($criterias)
    {
        $isAllCriteriaPresent = false;
        foreach ($criterias as $criteria) {
            $check = static::where('criteria_id', $criteria)->get()->count();
            if ($check > 0) {
                $isAllCriteriaPresent = true;
            } else {
                $isAllCriteriaPresent = false;
                break;
            }
        }
        return $isAllCriteriaPresent;
    }
}