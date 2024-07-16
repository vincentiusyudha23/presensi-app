<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }
}
