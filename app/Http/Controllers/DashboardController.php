<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \App\Models\Petugas;
use \App\Models\Presensi;

use Session;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);

        if (Auth::user()->role == "admin") {

            $totalPetugas = \App\Models\User::where('role', '=', 'user')
                ->count();
            $totalHadirHariIni = \App\Models\Presensi::whereNotNull('waktu_masuk')
                ->whereDate('created_at', date('Y-m-d'))
                ->count();
            // $totalHadirPerhariSelamaSeminggu = \App\Models\Presensi::getTotalHadirPerhariSelamaSeminggu();
            $totalHadirPerhariSelamaSeminggu = \App\Models\Presensi::whereNotNull('waktu_masuk')
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->get()
                ->groupBy(
                    function ($pres) {
                        $daftar_hari = array(
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu'
                        );
                        return $daftar_hari[$pres->created_at->format('l')];
                    }
                )->map(function ($group) {
                    return $group->count();
                });
            $presensiHariIni = Presensi::whereDate('created_at', Carbon::now())->get();
            // dd($totalHadirPerhariSelamaSeminggu);
            $presentasiKehadiran = $totalHadirHariIni / $totalPetugas * 100;
            // $message = [];
            $AbsenKemarin = Presensi::whereDate('created_at', Carbon::yesterday())
                ->whereNull('waktu_masuk')
                ->count();
            if ($AbsenKemarin < 1) {
                $re = $this->genPres();
                if ($re['status'] == 'success') {
                    return view("admin.dashboard", compact(
                        'totalPetugas',
                        'totalHadirHariIni',
                        'totalHadirPerhariSelamaSeminggu',
                        'presensiHariIni',
                        'presentasiKehadiran'
                    ))->with('success', 'Presensi berhasil di generate');
                }
            }
            return view("admin.dashboard", compact(
                'totalPetugas',
                'totalHadirHariIni',
                'totalHadirPerhariSelamaSeminggu',
                'presensiHariIni',
                'presentasiKehadiran'
            ));
        } else {
            $petugas = Petugas::where('user_id', Auth::user()->id)->first();
            return view("petugas.dashboard", compact('petugas'));
        }
    }
    public function genPres()
    {
        DB::beginTransaction();
        try {
            $petugas = Petugas::where('is_admin', false)->get();
            $result = [];
            $cnt = 0;
            //buat presensi seminggu kebelakang untuk petugas yang tidak memiliki record presensi
            $weekDate = CarbonPeriod::create(Carbon::now()->subDays(7), Carbon::now());
            // dd($weekDate);
            foreach ($petugas as $p) {
                foreach ($weekDate as $day) {
                    $presensi = Presensi::where('petugas_id', $p->id)->whereDate('created_at', $day)->first();
                    if ($presensi == null) {
                        $cnt++;
                        $jadwalHari = $p->jadwal[0]->hari;
                        $jadwalHari = explode('-', $jadwalHari);
                        $daysOfWeek = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                        $jadwalHari = array_map(function ($hari) use ($daysOfWeek) {
                            return array_search($hari, $daysOfWeek);
                        }, $jadwalHari);
                        $date = Carbon::parse($day)->locale('id');
                        if ($date->dayOfWeek >= $jadwalHari[0] || $date->dayOfWeek <= $jadwalHari[1]) {
                            $result[] = Presensi::create([
                                'petugas_id' => $p->id,
                                'created_at' => strtotime($day),
                            ]);
                        }
                    }
                }
            }
            return [
                'status' => 'success',
                'cnt' => $cnt,
                'data' => $result
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
