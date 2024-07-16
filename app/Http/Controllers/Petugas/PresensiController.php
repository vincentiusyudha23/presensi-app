<?php

namespace App\Http\Controllers\Petugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Presensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\PresensiPetugasExport;

class PresensiController extends \App\Http\Controllers\Controller
{
    function storeImage($request, $prefix)
    {
        $image_64 = $request->subIm;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        // $imageName = Str::random(10) . '.' . $extension;
        $imageName = $prefix . time() . '.' . $extension;
        // $request->subIm->storeAs('imgpres', $imageName);
        Storage::disk('public')->put('imgpres/' . $imageName, base64_decode($image));
        return $imageName;
    }
    public function index(Request $request)
    {

        $todayPresensi = Presensi::where('petugas_id', Auth::user()->petugas->id)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
        return view('petugas.presensi', compact('todayPresensi'));
    }
    public function absen(Request $request)
    {
        try {
            $request->validate([
                'subIm' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['status' => 'error', 'message' => 'Image Required'], 500);
        }
        DB::beginTransaction();
        try {
            $todayPresensi = Presensi::where('petugas_id', Auth::user()->petugas->id)
                ->whereDate('created_at', date('Y-m-d'))
                ->first();
            if ($todayPresensi) {
                if ($todayPresensi->waktu_masuk == null) {
                    $imageName = $this->storeImage($request, 'masuk_');
                    $todayPresensi->update([
                        'waktu_masuk' => date('H:i:s'),
                        'bukti_masuk' => 'imgpres/' . $imageName,
                    ]);
                    DB::commit();
                    return response()->json(['status' => 'success', 'message' => 'Data Added Successfully', 'img' => $imageName], 200);
                }
                $imageName = $this->storeImage($request, 'keluar_');
                $todayPresensi->update([
                    'waktu_keluar' => date('H:i:s'),
                    'bukti_keluar' => 'imgpres/' . $imageName,
                ]);
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Data Added Successfully', 'img' => $imageName], 200);
            }
            $imageName = $this->storeImage($request, 'masuk_');
            $todayPresensi = Presensi::create([
                'petugas_id' => Auth::user()->petugas->id,
                'waktu_masuk' => date('H:i:s'),
                'bukti_masuk' => 'imgpres/' . $imageName,
            ]);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Added Successfully', 'img' => $imageName], 200);
        } catch (\Exception $e) {
            DB::rollback();
            // return $e;
            return response()->json(['status' => 'error', 'message' => 'Data Added Failed'], 500);
        }
    }
    public function riwayat(Request $request, $id)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $presensi = Presensi::where('petugas_id', Auth::user()->petugas->id)
            ->whereNotNull('waktu_masuk')
            ->whereMonth('created_at', $bulanint)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('petugas.presensiRiwayat', compact('presensi', 'bulan', 'tahun'));
    }
    public function export(Request $request, $id)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $fileName = 'laporan-presensi_' . time() . '.xlsx';
        return Excel::download(new PresensiPetugasExport(['bulanint' => $bulanint, 'tahun' => $tahun, 'petugas_id' => $id]), $fileName);
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
