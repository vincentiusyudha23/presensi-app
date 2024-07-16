<?php

namespace App\Http\Controllers\Petugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Izin;
use App\Models\Petugas;

class IzinController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $izin = Izin::where('petugas_id', Auth::user()->petugas->id)->get();
        return view('petugas.izin', compact('izin'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'jenisPengajuan' => 'required',
            'keterangan' => 'required',
        ]);
        Izin::create([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenisPengajuan,
            'keterangan' => $request->keterangan,
            'petugas_id' => Auth::user()->petugas->id,

        ]);
        toast('Success', 'Data Added Successfully');
        return redirect('petugas/izin')->with('success', 'Data Added Successfully');
    }
}
