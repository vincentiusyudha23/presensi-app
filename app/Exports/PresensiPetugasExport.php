<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Presensi;

class PresensiPetugasExport implements FromView, ShouldAutoSize
{
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('exports.presensi', [
            'presensis' => Presensi::join('petugas', 'presensis.petugas_id', '=', 'petugas.id')
                ->join('jadwals', 'petugas.id', '=', 'jadwals.petugas_id')
                ->select(
                    'presensis.*',
                    'jadwals.periode',
                    'jadwals.lokasi',
                    'petugas.name',
                    'petugas.nik',
                )
                ->where('presensis.petugas_id', $this->params['petugas_id'])
                ->whereYear('presensis.created_at', $this->params['tahun'])
                ->whereMonth('presensis.created_at', $this->params['bulanint'])
                ->get()
                ->sortByDesc('created_at')
        ]);
    }
}
