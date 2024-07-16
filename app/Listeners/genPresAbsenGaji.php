<?php

namespace App\Listeners;

use App\Events\genPresAbsen;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use \App\Models\Gaji;
use \App\Models\Petugas;
use \App\Models\Presensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class genPresAbsenGaji
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\genPresAbsen  $event
     * @return void
     */
    public function handle(genPresAbsen $event)
    {
        $petugas = Petugas::where('is_admin', false)->get();

        //buat presensi seminggu kebelakang untuk petugas yang tidak memiliki record presensi
        $weekDate = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->yesterday());
        foreach ($weekDate as $day) {
            foreach ($petugas as $p) {
                $presensi = Presensi::where('petugas_id', $p->id)->whereDate('created_at', $day)->first();
                if (!$presensi) {
                    Presensi::create([
                        'petugas_id' => $p->id,
                        'status' => 'absen',
                        'created_at' => $day
                    ]);
                }
            }
        }
    }
}
