<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Gaji;

class GajiPetugasExport implements FromView, ShouldAutoSize
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
        return view('exports.gaji', [
            'gajis' => Gaji::where('petugas_id', $this->params['petugas_id'])
                ->whereMonth('created_at', $this->params['bulanint'])
                ->whereYear('created_at', $this->params['tahun'])
                ->get()
                ->sortByDesc('created_at')
        ]);
    }
}
