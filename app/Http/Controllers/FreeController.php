<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CriteriaPerbadinganRequest;
use App\Http\Requests\Admin\BobotRequest;
use App\Models\Alternative;
use App\Models\Bobot;
use App\Models\Criteria;
use App\Models\CriteriaAnalysis;
use App\Models\CriteriaAnalysisDetail;
use App\Models\Jenis;
use App\Models\PriorityValue;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Http\Request;

class FreeController extends Controller
{
    protected $limit   = 10;
    protected $field1s = array('wisatas.*');
    protected $field2s = array('wisatas.*', 'jenis.id as jenisId');
    protected $field3s = array('criterias.*');

    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }
    
    public function index()
    {
        $comparisons = CriteriaAnalysis::with('user')->with(['details' => function ($query) {
            $query->join('criterias', 'criteria_analysis_details.criteria_id_second', '=', 'criterias.id')
            ->select('criteria_analysis_details.*', 'criterias.nama_kriteria as criteria_name')
            ->orderBy('criterias.id');
        }])
        ->get();
        $criteriaAnalysis = CriteriaAnalysis::with('user')->with(['details' => function ($query) {
            $query->join('criterias', 'criteria_analysis_details.criteria_id_second', '=', 'criterias.id')
                ->select('criteria_analysis_details.*', 'criterias.nama_kriteria as criteria_name')
                ->orderBy('criterias.nama_kriteria');
        }])->get();
        $criterias = Criteria::all();
        $wisatas = Wisata::where('validasi', '=', '2')->orderby('nama_wisata')->get();
        return view('pages.free.dashboard', [
            'title'             => 'Rekomendasi Destinasi Wisata',
            'wisata'            => Wisata::count(),
            'criterias'         => Criteria::count(),
            'jenis'             => Jenis::count(),
            'users'             => User::count(),
            'wisatas'           => $wisatas,
            'comparisons'       => $comparisons,
            'criterias'         => $criterias,
            'criteria_analysis' => $criteriaAnalysis,
        ]);
    }

    public function kriteria(Request $request)
    {
        // menampilkan data kriteria
        $criterias = Criteria::orderby('id');
        // filter search
        if (request('search')) {
            $criterias = Criteria::where('nama_kriteria', 'LIKE', '%' . request('search') . '%')
                ->orWhere('slug', 'LIKE', '%' . request('search') . '%')
                ->orWhere('kategori', 'LIKE', '%' . request('search') . '%')
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
        $criterias = $criterias->paginate($perPage, $this->field3s, 'page', $page);
        return view('pages.free.kriteria', [
            'title'          => 'Data Kriteria',
            'criterias'      => $criterias,
            'perPageOptions' => $perPageOptions,
            'perPage'        => $perPage
        ]);
    }

    public function wisata(Request $request)
    {
        $criterias = Criteria::all();
        // Mengambil data wisata yang telah divalidasi dan publik
        $query = Wisata::where('validasi', '=', '2')
            ->where('tampil', '=', '2');
        // Mengambil nilai-nilai alternatif dari request
        $alternatives = $request->except(['perPage', 'page']);
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
        $wisatas = $query->orderBy('nama_wisata')->paginate($perPage, $this->field1s, 'page', $page);
        return view('pages.free.wisata', [
            'title'          => 'Data Destinasi Wisata',
            'wisatas'        => $wisatas,
            'perPageOptions' => $perPageOptions,
            'perPage'        => $perPage,
            'criterias'      => $criterias
        ]);
    }

    public function jenis()
    {
        // mengurutkan
        $jenis = Jenis::orderby('jenis_name')->get();
        return view('pages.free.jenis', [
            'title'   => 'Data Jenis Wisata',
            'wisatas' => '',
            'jenises' => $jenis
        ]);
    }

    public function jenisSlug(Jenis $jenis)
    {
        return view('pages.free.jenisSlug', [
            'title'   => $jenis->jenis_name,
            'wisatas' => $jenis->wisatas,
            'active'  => 'jenis',
            'jenis'   => $jenis->jenis_name,
        ]);
    }

    public function alternatif(Request $request)
    {
        $usedIds    = Alternative::select('wisata_id')->distinct()->get();
        $usedIdsFix = [];
        foreach ($usedIds as $usedId) {
            array_push($usedIdsFix, $usedId->wisata_id);
        }
        $alternatives = Wisata::join('jenis', 'jenis.id', '=', 'wisatas.jenis_id')
            ->whereIn('wisatas.id', $usedIdsFix)
            ->orderBy('wisatas.nama_wisata')
            ->with('alternatives');
        if (request('search')) {
            $alternatives = Wisata::join('jenis', 'jenis.id', '=', 'wisatas.jenis_id')
                ->where('wisatas.nama_wisata', 'LIKE', '%' . request('search') . '%')
                ->orWhere('jenis.jenis_name', 'LIKE', '%' . request('search') . '%')
                ->whereIn('wisatas.id', $usedIdsFix)
                ->with('alternatives');
        }
        $wisatasList = Wisata::join('jenis', 'jenis.id', '=', 'wisatas.jenis_id')
            ->whereNotIn('wisatas.id', $usedIdsFix)
            ->orderBy('jenis.id')
            ->orderBy('wisatas.nama_wisata', 'ASC')
            ->get(['wisatas.*', 'jenis.id as jenisId'])
            ->groupBy('jenis.jenis_name');
        $page = $request->query('page', 1);
        $perPageOptions = [5, 10, 15, 20, 25];
        $perPage = $request->query('perPage', $perPageOptions[1]);
        $alternatives = $alternatives->paginate($perPage, $this->field2s, 'page', $page);
        return view('pages.free.alternatif', [
            'title'          => 'Data Alternatif',
            'alternatives'   => $alternatives,
            'criterias'      => Criteria::all(),
            'wisata_list'    => $wisatasList,
            'perPageOptions' => $perPageOptions,
            'perPage'        => $perPage
        ]);
    }

    public function awal()
    {
        $comparisons = CriteriaAnalysis::with('user')->with(['details' => function ($query) {
            $query->join('criterias', 'criteria_analysis_details.criteria_id_second', '=', 'criterias.id')
            ->select('criteria_analysis_details.*', 'criterias.nama_kriteria as criteria_name')
            ->orderBy('criterias.id');
        }])
        ->get();
        $criteriaAnalysis = CriteriaAnalysis::with('user')->with(['details' => function ($query) {
            $query->join('criterias', 'criteria_analysis_details.criteria_id_second', '=', 'criterias.id')
                ->select('criteria_analysis_details.*', 'criterias.nama_kriteria as criteria_name')
                ->orderBy('criterias.id');
        }])->get();
        $criterias = Criteria::all();
        return view('pages.free.perhitungan', [
            'title'             => 'Metode SPK',
            'comparisons'       => $comparisons,
            'criterias'         => $criterias,
            'criteria_analysis' => $criteriaAnalysis,
        ]);
    }

    // menghitung perbandingan
    private function _countRestDetails($criteriaAnalysisId, $detailIds)
    {
        // get semua data perbandingan yang tidak dimasukkan pengguna nilainya
        $restDetails = CriteriaAnalysisDetail::where('criteria_analysis_id', $criteriaAnalysisId)
            ->whereNotIn('id', $detailIds)
            ->get();

        // count and update nilai perbandingan
        if ($restDetails->count()) {
            $restDetails->each(function ($value, $key) use ($criteriaAnalysisId) {
                $prevComparison = CriteriaAnalysisDetail::where([
                    'criteria_analysis_id' => $criteriaAnalysisId,
                    'criteria_id_first'    => $value->criteria_id_second,
                    'criteria_id_second'   => $value->criteria_id_first,
                ])->first();
                // perbandingan hasil
                $comparisonResult = 1 / $prevComparison['comparison_value'];
                $comparisonValue = $prevComparison['comparison_value'];
                CriteriaAnalysisDetail::where([
                    'criteria_analysis_id' => $criteriaAnalysisId,
                    'criteria_id_first'    => $value->criteria_id_first,
                    'criteria_id_second'   => $value->criteria_id_second,
                ])->update([
                    'comparison_result' => $comparisonResult,
                    'comparison_value' => $comparisonValue,
                ]);
            });
        }
    }

    // penjumlahan kriteria
    private function _getTotalSumPerCriteria($criteriaAnalysisId, $criterias)
    {
        $result = [];
        foreach ($criterias as $criteria) {
            $totalPerCriteria = CriteriaAnalysisDetail::where([
                'criteria_analysis_id' => $criteriaAnalysisId,
                'criteria_id_second'   => $criteria->id
            ])->sum('comparison_result');
            $data = [
                'nama_kriteria' => $criteria->nama_kriteria,
                'totalSum'      => floatval($totalPerCriteria)
            ];
            array_push($result, $data);
        }
        return $result;
    }

    // menghitung eigen vector
    private function _countPriorityValue($criteriaAnalysisId)
    {
        // get semua kriteria yang dipilih by user
        $criterias = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysisId);
        // loop criteria
        foreach ($criterias as $criteria) {
            // get semua nilai perbandingan dari first criteria id yang cocok dengan loop criteria id
            $dataDetails = CriteriaAnalysisDetail::select('criteria_id_second', 'comparison_result')
                ->where([
                    'criteria_analysis_id' => $criteriaAnalysisId,
                    'criteria_id_first'    => $criteria->id
                ])->get();
            // nilai prioritas sementara
            $tempValue = 0;
            // loop nilai perbandingan
            foreach ($dataDetails as $detail) {
                $totalPerCriteria = CriteriaAnalysisDetail::where([
                    'criteria_analysis_id' => $criteriaAnalysisId,
                    'criteria_id_second'   => $detail->criteria_id_second
                ])->sum('comparison_result');
                // nilai prioritas sementara
                $res = substr($detail->comparison_result / $totalPerCriteria, 0, 11);
                $tempValue += $res;
            }
            // final prioritas value = nilai sementara / jumlah total kriteria
            $FinalPrevValue = $tempValue / $criterias->count();
            $data = [
                'criteria_analysis_id' => $criteriaAnalysisId,
                'criteria_id'          => $criteria->id,
                'value'                => floatval($FinalPrevValue),
            ];
            PriorityValue::updateOrCreate([
                'criteria_analysis_id' => $criteriaAnalysisId,
                'criteria_id'          => $criteria->id,
            ], $data);
            $existingBobot = Bobot::where([
                'criteria_analysis_id' => $criteriaAnalysisId,
                'criteria_id'          => $criteria->id,
            ])->first();
            // Jika record tidak ada atau value-nya adalah 0, lakukan pembaruan atau pembuatan record
            if (!$existingBobot || $existingBobot->value == 0) {
                $bobot = [
                    'criteria_analysis_id' => $criteriaAnalysisId,
                    'criteria_id'          => $criteria->id,
                    'value'                => floatval(0),
                ];
                Bobot::updateOrCreate([
                    'criteria_analysis_id' => $criteriaAnalysisId,
                    'criteria_id'          => $criteria->id,
                ], $bobot);
            }
        }
    }

    // nilai random konsistensi
    private function prepareAnalysisData($criteriaAnalysis)
    {
        $criteriaAnalysis->load('details', 'details.firstCriteria', 'details.secondCriteria', 'priorityValues', 'priorityValues.criteria');
        $totalPerCriteria = $this->_getTotalSumPerCriteria($criteriaAnalysis->id, CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id));
        // Nilai Random Konsistensi
        $ruleRC = [
            1  => 0.0,
            2  => 0.0,
            3  => 0.58,
            4  => 0.90,
            5  => 1.12,
            6  => 1.24,
            7  => 1.32,
            8  => 1.41,
            9  => 1.45,
            10 => 1.49,
            11 => 1.51,
            12 => 1.48,
            13 => 1.56,
            14 => 1.57,
            15 => 1.59,
        ];
        $criteriaAnalysis->unsetRelation('details');
        return ['totalSums' => $totalPerCriteria,'ruleRC' => $ruleRC,];
    }

    // check jika ada kriteria
    private function checkIfAbleToRank()
    {
        $availableCriterias = Criteria::all()->pluck('id');
        return Alternative::checkAlternativeByCriterias($availableCriterias);
    }

    // normalisasai saw
    private function _hitungNormalisasi($dividers, $alternatives)
    {
        $normalisasi = [];
        foreach ($alternatives as $alternative) {
            $normalisasiAngka = [];
            foreach ($alternative['alternative_val'] as $key => $val) {
                if ($val == 0) {
                    $dividers = 0;
                }
                $kategori = $dividers[$key]['kategori'];
                if ($kategori === 'BENEFIT' && $val != 0) {
                    $result = substr(floatval($val / $dividers[$key]['divider_value']), 0, 11);
                }
                if ($kategori === 'COST' && $val != 0) {
                    $result = substr(floatval($dividers[$key]['divider_value'] / $val), 0, 11);
                }
                array_push($normalisasiAngka, $result);
            }
            array_push($normalisasi, [
                'wisata_id'       => $alternative['wisata_id'],
                'nama_wisata'     => $alternative['nama_wisata'],
                'foto'            => $alternative['foto'],
                'jenis_name'      => $alternative['jenis_name'],
                'lokasi_maps'     => $alternative['lokasi_maps'],
                'fasilitas'       => $alternative['fasilitas'],
                'biaya'           => $alternative['biaya'],
                'situs'           => $alternative['situs'],
                'criteria_name'   => $alternative['criteria_name'],
                'criteria_id'     => $alternative['criteria_id'],
                'alternative_val' => $alternative['alternative_val'],
                'results'         => $normalisasiAngka
            ]);
        }
        // Menambahkan orderby berdasarkan nama destinasi (wisata_name) secara naik (ascending)
        $normalisasi = collect($normalisasi)->sortBy('wisata_name')->values()->all();
        return $normalisasi;
    }

    // ranking
    private function _finalRanking($priorityValues, $normalizations)
    {
        foreach ($normalizations as $keyNorm => $normal) {
            foreach ($normal['results'] as $keyVal => $value) {
                $importanceVal = $priorityValues[$keyVal]->value;
                // Operasi penjumlahan dari perkalian matriks ternormalisasi dan prioritas
                $result = $importanceVal * $value;
                if (array_key_exists('rank_result', $normalizations[$keyNorm])) {
                    $normalizations[$keyNorm]['rank_result'] += $result;
                } else {
                    $normalizations[$keyNorm]['rank_result'] = $result;
                }
            }
        }
        usort($normalizations, function ($a, $b) {
            return $b['rank_result'] <=> $a['rank_result'];
        });
        return $normalizations;
    }

    public function resultKombinasi(CriteriaAnalysis $criteriaAnalysis)
    {
        $data           = $this->prepareAnalysisData($criteriaAnalysis);
        $isAbleToRank   = $this->checkIfAbleToRank();

        $criterias      = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id);
        $criteriaIds    = $criterias->pluck('id');
        $alternatives   = Alternative::getAlternativesByCriteria($criteriaIds);
        $dividers       = Alternative::getDividerByCriteria($criterias);
        $normalizations = $this->_hitungNormalisasi($dividers, $alternatives);
        try {
            $ranking    = $this->_finalRanking($criteriaAnalysis->priorityValues, $normalizations);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('pages.free.kombinasi', [
            'title'             => 'Perhitungan Kombinasi',
            'criteria_analysis' => $criteriaAnalysis,
            'totalSums'         => $data['totalSums'],
            'ruleRC'            => $data['ruleRC'],
            'isAbleToRank'      => $isAbleToRank,
            'dividers'          => $dividers,
            'criteriaAnalysis'  => $criteriaAnalysis,
            'criteria_analysis' => $criteriaAnalysis,
            'dividers'          => $dividers,
            'criterias'         => Criteria::all(),
            'normalizations'    => $normalizations,
            'ranks'             => $ranking,
        ]);
    }

    // detail perhitungan
    public function detailKombinasi(CriteriaAnalysis $criteriaAnalysis)
    {
        $criteriaAnalysis->load('priorityValues');
        $data           = $this->prepareAnalysisData($criteriaAnalysis);
        $isAbleToRank   = $this->checkIfAbleToRank();

        $criterias      = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id);
        $criteriaIds    = $criterias->pluck('id');
        $alternatives   = Alternative::getAlternativesByCriteria($criteriaIds);
        $dividers       = Alternative::getDividerByCriteria($criterias);
        $normalizations = $this->_hitungNormalisasi($dividers, $alternatives);
        try {
            $ranking    = $this->_finalRanking($criteriaAnalysis->priorityValues, $normalizations);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('pages.free.kombinasiDetail', [
            'title'             => 'Detail Perhitungan Kombinasi',
            'criteria_analysis' => $criteriaAnalysis,
            'totalSums'         => $data['totalSums'],
            'ruleRC'            => $data['ruleRC'],
            'isAbleToRank'      => $isAbleToRank,
            'criteriaAnalysis'  => $criteriaAnalysis,
            'dividers'          => $dividers,
            'criterias'         => Criteria::all(),
            'normalizations'    => $normalizations,
            'ranks'             => $ranking,
            'ranking'           => $ranking,
        ]);
    }

    public function resultAHP(CriteriaAnalysis $criteriaAnalysis)
    {
        $criteriaAnalysis->load('priorityValues');
        $criterias          = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id);
        $criteriaIds        = $criterias->pluck('id');
        $dividers           = Alternative::getDividerByCriteria($criterias);
        $criterias          = Criteria::all();
        $numberOfCriterias  = count($criterias);
        $alternatives       = Alternative::all();
        $alternative1s       = Alternative::getAlternativesByCriteria($criteriaIds);
        $normalizations     = $this->_hitungNormalisasi($dividers, $alternative1s);
        $data = $this->prepareAnalysisData($criteriaAnalysis);
        $isAbleToRank = $this->checkIfAbleToRank();
        try {
            $ranking    = $this->_finalRanking($criteriaAnalysis->bobots, $normalizations);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('pages.free.ahp', [
            'title'             => 'Perhitungan AHP',
            'criteriaAnalysis'  => $criteriaAnalysis,
            'criteria_analysis' => $criteriaAnalysis,
            'dividers'          => $dividers,
            'totalSums'         => $data['totalSums'],
            'ruleRC'            => $data['ruleRC'],
            'isAbleToRank'      => $isAbleToRank,
            'numberOfCriterias' => $numberOfCriterias,
            'alternatives'      => $alternatives,
            'criterias'         => $criterias,
            'normalizations'    => $normalizations,
        ]);
    }

    public function detailAHP(CriteriaAnalysis $criteriaAnalysis)
    {
        $data = $this->prepareAnalysisData($criteriaAnalysis);
        $isAbleToRank = $this->checkIfAbleToRank();
        $criteriaAnalysis->load('priorityValues');
        $criterias          = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id);
        $criteriaIds        = $criterias->pluck('id');
        $dividers           = Alternative::getDividerByCriteria($criterias);
        $criterias          = Criteria::all();
        $numberOfCriterias  = count($criterias);
        $alternatives       = Alternative::all();
        $alternative1s      = Alternative::getAlternativesByCriteria($criteriaIds);
        $normalizations     = $this->_hitungNormalisasi($dividers, $alternative1s);
        try {
            $ranking    = $this->_finalRanking($criteriaAnalysis->bobots, $normalizations);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('pages.free.ahpDetail', [
            'title'             => 'Detail Perhitungan AHP',
            'criteriaAnalysis'  => $criteriaAnalysis,
            'criteria_analysis' => $criteriaAnalysis,
            'totalSums'         => $data['totalSums'],
            'ruleRC'            => $data['ruleRC'],
            'isAbleToRank'      => $isAbleToRank,
            'dividers'          => $dividers,
            'numberOfCriterias' => $numberOfCriterias,
            'alternatives'      => $alternatives,
            'criterias'         => $criterias,
            'normalizations'    => $normalizations,
        ]);
    }

    public function resultSAW(CriteriaAnalysis $criteriaAnalysis)
    {
        $criteriaAnalysis->load('bobots');
        $criterias      = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id);
        $criteriaIds    = $criterias->pluck('id');
        $alternatives   = Alternative::getAlternativesByCriteria($criteriaIds);
        $dividers       = Alternative::getDividerByCriteria($criterias);
        $normalizations = $this->_hitungNormalisasi($dividers, $alternatives);
        try {
            $ranking    = $this->_finalRanking($criteriaAnalysis->bobots, $normalizations);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('pages.free.saw', [
            'title'            => 'Perhitungan SAW',
            'dividers'         => $dividers,
            'normalizations'   => $normalizations,
            'criteriaAnalysis' => $criteriaAnalysis,
            'criterias'        => Criteria::all(),
            'ranks'            => $ranking
        ]);
    }

    public function detailSAW(CriteriaAnalysis $criteriaAnalysis)
    {
        $criterias      = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id);
        $criteriaIds    = $criterias->pluck('id');
        $alternatives   = Alternative::getAlternativesByCriteria($criteriaIds);
        $dividers       = Alternative::getDividerByCriteria($criterias);
        $normalizations = $this->_hitungNormalisasi($dividers, $alternatives);
        try {
            $ranking    = $this->_finalRanking($criteriaAnalysis->bobots, $normalizations);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        $title = 'Detail Perhitungan SAW';
        return view('pages.free.sawDetail', [
            'criteriaAnalysis' => $criteriaAnalysis,
            'dividers'         => $dividers,
            'normalizations'   => $normalizations,
            'ranking'          => $ranking,
            'title'            => $title
        ]);
    }

    public function rekomendasi(CriteriaAnalysis $criteriaAnalysis)
    {
        $data           = $this->prepareAnalysisData($criteriaAnalysis);
        $isAbleToRank   = $this->checkIfAbleToRank();

        $criterias      = CriteriaAnalysisDetail::getSelectedCriterias($criteriaAnalysis->id);
        $criteriaIds    = $criterias->pluck('id');
        $alternatives   = Alternative::getAlternativesByCriteria($criteriaIds);
        $dividers       = Alternative::getDividerByCriteria($criterias);
        $normalizations = $this->_hitungNormalisasi($dividers, $alternatives);
        try {
            $ranking    = $this->_finalRanking($criteriaAnalysis->priorityValues, $normalizations);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('pages.free.rekomendasi', [
            'title'             => 'Rekomendasi Destinasi Wisata',
            'criteria_analysis' => $criteriaAnalysis,
            'totalSums'         => $data['totalSums'],
            'ruleRC'            => $data['ruleRC'],
            'isAbleToRank'      => $isAbleToRank,
            'dividers'          => $dividers,
            'criteriaAnalysis'  => $criteriaAnalysis,
            'criteria_analysis' => $criteriaAnalysis,
            'dividers'          => $dividers,
            'criterias'         => Criteria::all(),
            'normalizations'    => $normalizations,
            'ranks'             => $ranking,
        ]);
    }
}