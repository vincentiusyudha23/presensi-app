<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class JadwalController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $petugas = \App\Models\Petugas::where('is_admin', 0)->get();
        $jadwal = \App\Models\Jadwal::with('petugas')->get()->sortByDesc('created_at');

        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('admin.jadwal', compact('petugas', 'jadwal'));
    }
    public function viewPetugas(Request $request)
    {
        $bulan = ($request->get('bulan'))
            ? $request->get('bulan') :
            Carbon::now()->locale('id')->getTranslatedMonthName();
        $tahun = ($request->get('tahun'))
            ? $request->get('tahun') : Carbon::now()->year;
        $bulanint = $this->bulan2int($bulan);
        $jadwal = \App\Models\Jadwal::where('petugas_id', Auth::user()->petugas->id)
            ->with('petugas')->get();
        $jadwal = $jadwal->filter(function ($item) use ($bulanint, $tahun) {
            $months = [
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
            ];
            $jadwalBulan = array_map(function ($bulan) use ($months) {
                return $months[strtolower($bulan)];
            }, explode('-', $item->periode));
            $arrayJadwalBulan = [];
            for ($jb = $jadwalBulan[0]; $jb <= $jadwalBulan[1]; $jb++) {
                $arrayJadwalBulan[] = $jb;
            }
            // dd($arrayJadwalBulan);
            return in_array($bulanint, $arrayJadwalBulan) == $bulanint && $item->created_at->year == $tahun;
        });
        // dd($jadwal);
        // dd(Auth::user()->petugas->id);
        return view('petugas.jadwal', compact('jadwal', 'bulan', 'tahun'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'periode' => 'required',
            'lokasi' => 'required',
            'waktu' => 'required',
            'hari' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $petugas = \App\Models\Petugas::where('nik', $request->nik)->first();
            $jadwal = \App\Models\Jadwal::create([
                'periode' => $request->periode,
                'lokasi' => $request->lokasi,
                'waktu' => $request->waktu,
                'hari' => $request->hari,
                'petugas_id' => $petugas->id,
            ]);
            DB::commit();
            toast('Success', 'Data Added Successfully');
            return redirect('admin/jadwal')->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            toast('Error', 'Data Added Failed');
            return redirect('admin/jadwal')->with('error', 'Data Added Failed');
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'periode' => 'required',
            'lokasi' => 'required',
            'waktu' => 'required',
            'hari' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $petugas = \App\Models\Petugas::where('nik', $request->nik)->first();
            $jadwal = \App\Models\Jadwal::find($id);
            $jadwal->update([
                'periode' => $request->periode,
                'lokasi' => $request->lokasi,
                'waktu' => $request->waktu,
                'hari' => $request->hari,
                'petugas_id' => $petugas->id,
            ]);
            DB::commit();
            toast('Success', 'Data Updated Successfully');
            return redirect('admin/jadwal')->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            toast('Error', 'Data Updated Failed');
            return redirect('admin/jadwal')->with('error', 'Data Updated Failed');
        }
    }
    public function destroy($id)
    {
        $jadwal = \App\Models\Jadwal::find($id);
        $jadwal->delete();
        toast('Success', 'Data Deleted Successfully');
        return redirect('admin/jadwal')->with('success', 'Data Deleted Successfully');
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
