<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IzinExport;
use App\Models\Izin;

class IzinController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $izin = Izin::orderBy('tanggal', 'desc')->get();
        return view('admin.izin', compact('izin'));
        // $title = 'Delete Data!';
        // $text = "Are you sure you want to delete?";
        // confirmDelete($title, $text);
        // return view('admin.jadwal', compact('petugas', 'jadwal'));
    }

    public function setuju($id, $code)
    {
        $izin = Izin::find($id);
        if ($code == 1) {
            $code = 'disetujui';
        }
        if ($code == 2) {
            $code = 'ditolak';
        }
        $izin->status = $code;
        $izin->save();
        return redirect('admin/izin')->with('success', 'Data Updated Successfully');
    }
    public function export()
    {
        $time = date('Y-m-d H:i:s');
        return Excel::download(new IzinExport, 'izin_' . $time . '.xlsx');
    }
}
