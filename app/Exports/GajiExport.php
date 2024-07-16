<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Gaji;

class GajiExport implements FromView, ShouldAutoSize
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
            'gajis' => Gaji::whereMonth('created_at', $this->params['bulanint'])
                ->whereYear('created_at', $this->params['tahun'])
                ->get()
                ->sortByDesc('created_at')
        ]);
    }
}
