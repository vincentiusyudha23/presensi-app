<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public static function getPeriodeYearPresensis()
    {
        $press = self::join('petugas', 'presensis.petugas_id', '=', 'petugas.id')
            ->join('jadwals', 'petugas.id', '=', 'jadwals.petugas_id')
            ->select('presensis.*', 'jadwals.periode')
            ->get()
            ->groupBy([
                fn ($pres) => $pres->created_at->format('Y'),
                fn ($pres) => $pres->periode,
            ])
            ->map(function ($group) {
                return $group->map(function ($pres) {
                    return $pres->unique('petugas_id')->count();
                });
            });

        return $press;
    }
    public static function getTotalHadirPerhariSelamaSeminggu()
    {
        return self::whereNotNull('waktu_masuk')
            ->whereBetween('created_at', [date('Y-m-d', strtotime('-7 days')), date('Y-m-d')])
            ->get()
            ->groupBy(
                fn ($pres) => $pres->created_at->format('Y-m-d')
            )->map(function ($group) {
                return $group->count();
            });
    }
    public static function getTotalPresensiBulanIni()
    {
        return self::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(
                fn ($pres) => $pres->petugas_id
            )
            ->map(function ($group) {
                return $group->count();
            });
    }
}
