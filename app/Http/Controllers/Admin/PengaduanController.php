<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
// use App\Exports\PengaduanExport;
use App\Models\Pengaduan;
use App\Exports\PengaduanExport;

class PengaduanController extends \App\Http\Controllers\Controller
{
    public function store_image($image, $prefix)
    {
        $imageName = $prefix . '_' . time() . '.' . $image->getClientOriginalExtension();
        // Storage::disk('public')->put('imgpengaduan/' . $imageName, $image);
        $path = Storage::putFileAs('public/imgpengaduan', $image, $imageName);
        return $imageName;
    }
    public function index(Request $request)
    {
        $pengaduan = \App\Models\Pengaduan::orderBy('created_at')->get();
        return view('admin.pengaduan', compact('pengaduan'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tanggal' => 'required',
            'lokasi' => 'required',
            'keterangan' => 'required',
            'foto' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $pengaduan = new Pengaduan();
            $pengaduan->tanggal = $request->tanggal;
            $pengaduan->lokasi = $request->lokasi;
            $pengaduan->keterangan = $request->keterangan;
            $pengaduan->foto = $this->store_image($request->file('foto'), 'pengaduan');
            $pengaduan->petugas_id = Auth::user()->petugas->id;
            $pengaduan->save();
            DB::commit();
            return redirect('petugas/pengaduan')->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('petugas/pengaduan')->with('error', 'Data Added Failed');
        }
    }
    public function export()
    {
        $data = Pengaduan::orderByDesc('created_at')->get();
        $time = date('Y-m-d H:i:s');
        return Excel::download(new PengaduanExport($data), 'pengaduan_' . $time . '.xlsx');
    }
}
