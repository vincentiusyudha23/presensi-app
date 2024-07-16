<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Presensi;

class PresensiExport implements FromView, ShouldAutoSize
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
                ->whereYear('presensis.created_at', $this->params['year'])
                ->where('jadwals.periode', $this->params['periode'])
                ->get()
                ->sortByDesc('created_at')
        ]);
    }
}
