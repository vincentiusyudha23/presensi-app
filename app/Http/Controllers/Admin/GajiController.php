<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Presensi;
use App\Models\Petugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GajiExport;
use App\Exports\GajiPetugasExport;

class GajiController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $presensiBulanIni = Presensi::getTotalPresensiBulanIni();
        $gajis = Gaji::whereMonth('tanggal', $bulanint)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->sortByDesc('tanggal');
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $gajis
            ], 200);
        }
        return view('admin.gaji', compact('gajis', 'bulan', 'tahun', 'presensiBulanIni'));
    }
    public function report(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $bulanint = $this->bulan2int($bulan);
        $gajis = Gaji::whereMonth('created_at', $bulanint)
            ->whereYear('created_at', $tahun)
            ->get();
        $timestamp = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
        $fileName = 'laporan-gaji_'  . time() . 'pdf';

        // return view('exports.reportGaji', compact('gajis', 'bulan', 'tahun', 'timestamp'));
        $pdf = PDF::loadView('exports.reportGaji', [
            'gajis' => $gajis,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'timestamp' => $timestamp
        ]);
        $content = $pdf->download()->getOriginalContent();
        Storage::disk('public')->put('reportGaji/' . $fileName, $content);
        return $pdf->stream();
    }
    public function viewPetugas(Request $request)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $gaji = Gaji::where('petugas_id', auth()->user()->petugas->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanint);
        // dd($bulan, $tahun, $gaji->get());
        $gaji = $gaji->get()->sortByDesc('tanggal');
        return view('petugas.gaji', compact('gaji', 'bulan', 'tahun'));
    }
    public function regenerate()
    {
        $ubah = false;
        $baseGaji = 2500000;
        $petugas = Petugas::where('is_admin', false)->get();
        // $gajis = Gaji::whereMonth('created_at', Carbon::now()->month)
        //     ->whereYear('created_at', Carbon::now()->year)
        //     ->get();
        DB::beginTransaction();
        try {
            foreach ($petugas as $item) {
                // dd($item->gajiBulanIni()->get());
                if ($item->gajiBulanIni() == null) {
                    $gaji = Gaji::create([
                        'petugas_id' => $item->id,
                        'gaji' => $baseGaji,
                        'tanggal' => Carbon::now()->toDateString(),
                        'total' => $baseGaji - ($baseGaji * $item->getPotonganGajiBulanIni() / 100),
                        'potongan' => $item->getPotonganGajiBulanIni()
                    ]);
                    $ubah = true;
                } else {
                    $gaji = Gaji::where('petugas_id', $item->id)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->first();
                    if ($gaji->potongan != $item->getPotonganGajiBulanIni()) {
                        $ubah = true;
                        $gaji->potongan = $item->getPotonganGajiBulanIni();
                        $gaji->total = $baseGaji - ($baseGaji * $item->getPotonganGajiBulanIni() / 100);
                        $gaji->save();
                    }
                }
            }
            if ($ubah) {
                DB::commit();
                return redirect()->back()->with('success', 'Gaji berhasil di regenerasi');
            } else {
                return redirect()->back()->with('success', 'Tidak ada perubahan pada gaji');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
            return redirect()->back()->with('error', 'Gaji gagal di regenerasi');
        }
    }
    public function detail(Request $request, $id)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $gaji = Gaji::find($id);
        $petugas = $gaji->petugas;
        $detailPresensi = Presensi::where('petugas_id', $petugas->id)
            ->whereMonth('created_at', $bulanint)
            ->whereYear('created_at', $tahun)
            ->get()
            ->groupBy(function ($item) {
                if ($item->waktu_masuk == null) {
                    return 'absen';
                } else {
                    return 'hadir';
                }
            })
            ->map(function ($item) {
                return $item->count();
            });
        $data = [
            'petugasNama' => $petugas->name,
            'totalGaji' => number_format($gaji->total, 0, ',', '.'),
            'totalHadir' => $detailPresensi['hadir'] ?? 0,
            'totalAbsen' => $detailPresensi['absen'] ?? 0,
            'totalPotongan' => $gaji->potongan . '%',
            'bulanTahun' => $bulan . '/' . $tahun
        ];
        return response()->json($data, 200);
    }
    public function exportExcel(Request $request)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $fileName = 'laporan-gaji_' . time() . '.xlsx';
        return Excel::download(new GajiExport(['bulanint' => $bulanint, 'tahun' => $tahun]), $fileName);
    }
    public function exportPetugas(Request $request, $id)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $fileName = 'laporan-gaji_' . time() . '.xlsx';
        return Excel::download(new GajiPetugasExport(['bulanint' => $bulanint, 'tahun' => $tahun, 'petugas_id' => $id]), $fileName);
    }
    public function export(Request $request, $id)
    {

        $uuid = ($request->post('uuid')) ? $request->post('uuid') : '';
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $gaji = Gaji::find($id);
        $petugas = $gaji->petugas;
        $detailPresensi = Presensi::where('petugas_id', $petugas->id)
            ->whereMonth('created_at', $bulanint)
            ->whereYear('created_at', $tahun)
            ->get()
            ->groupBy(function ($item) {
                if ($item->waktu_masuk == null) {
                    return 'absen';
                } else {
                    return 'hadir';
                }
            })
            ->map(function ($item) {
                return $item->count();
            });
        $fileName = $uuid . '.pdf';
        $filelink = url('storage/slipgaji/' . $fileName);

        $data = [
            'timestamp' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y'),
            'nama' => $petugas->name,
            'bulanTahun' => $bulan . '/' . $tahun,
            'hadir' => $detailPresensi['hadir'] ?? 0,
            'absen' => $detailPresensi['absen'] ?? 0,
            'potongan' => $gaji->potongan . '%',
            'total' => number_format($gaji->total, 0, ',', '.'),
            'filelink' => $filelink,
            'qrcode' => $request->post('qrb64')
        ];

        $pdf = PDF::loadView('exports.slipgaji', [
            'data' => (object)$data
        ]);
        $content = $pdf->download()->getOriginalContent();
        Storage::disk('public')->put('slipgaji/' . $fileName, $content);
        return $pdf->stream();
    }
    public function bulan2int($date_string)
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
