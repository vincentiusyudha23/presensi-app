<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresensiExport;
use App\Models\Presensi;
// use IntlDateFormatter;

class PresensiController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $presensis = Presensi::getPeriodeYearPresensis();
        return view('admin.presensiPeriod', compact('presensis'));
    }
    public function periode(Request $request, $year, $periode)
    {
        if ($request->get('bulan')) {
            $bulan = $this->myStrtotime($request->get('bulan'));
            // $bulan = date('m', $this->Mystrtotime('Desember'));
            // dd($this->Mystrtotime('Desember'));
            // dd($bulan);

            $presensis = Presensi::join('petugas', 'presensis.petugas_id', '=', 'petugas.id')
                ->join('jadwals', 'petugas.id', '=', 'jadwals.petugas_id')
                ->select(
                    'presensis.*',
                    'jadwals.periode',
                    'jadwals.lokasi',
                    'petugas.name',
                    'petugas.nik',
                )
                ->whereNotNull('presensis.waktu_masuk')
                ->whereYear('presensis.created_at', $year)
                ->where('jadwals.periode', $periode)
                ->whereMonth('presensis.created_at', $bulan)
                ->get()
                ->sortBy('created_at', SORT_DESC);
            return view('admin.presensi', compact('presensis'));
        }
        $presensis = Presensi::join('petugas', 'presensis.petugas_id', '=', 'petugas.id')
            ->join('jadwals', 'petugas.id', '=', 'jadwals.petugas_id')
            ->select(
                'presensis.*',
                'jadwals.periode',
                'jadwals.lokasi',
                'petugas.name',
                'petugas.nik',
            )
            ->whereNotNull('presensis.waktu_masuk')
            ->whereYear('presensis.created_at', $year)
            ->where('jadwals.periode', $periode)
            ->get()
            ->sortBy('created_at', SORT_DESC);
        return view('admin.presensi', compact('presensis'));
    }
    public function export(Request $request, $year, $periode)
    {
        $time = date('Y-m-d H:i:s');
        return Excel::download(new PresensiExport(['year' => $year, 'periode' => $periode]), 'presensi_' . $time . '.xlsx');
    }
    public function myStrtotime($date_string)
    {
        return strtr(
            strtolower($date_string),
            array(
                'januari' => 1,
                'februari' => 2,
                'maret' => 3,
                'april' => 4,
                'mei' => 5,
                'juni' => 6,
                'juli' => 7,
                'agustus' => 8,
                'september' => 9,
                'oktober' => 10,
                'november' => 11,
                'desember' => 12,
            )
        );
    }
}
