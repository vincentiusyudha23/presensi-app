<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Petugas;
use Faker\Factory as Faker;

class PetugasController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);


        $petugas = Petugas::where('is_admin', 0)->with('user')->get()->sortByDesc('created_at');
        // dd($petugas);
        return view('admin.akunPetugas', compact('petugas'));
    }
    public function import(Request $request)
    {
        try {
            $request->validate([
                'data' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['status' => 'error', 'message' => 'Data Required'], 500);
        }
        $faker = Faker::create('id_ID');
        $data = $request->data;
        DB::beginTransaction();
        try {
            foreach ($data as $key => $value) {
                $user = \App\Models\User::create([
                    'username' => $faker->userName(),
                    'password' => bcrypt($faker->password()),
                ]);
                $petugas = \App\Models\Petugas::create([
                    'name' => $value['nama'],
                    'nik' => $value['nik'],
                    'email' => $value['email'],
                    'alamat' => $value['alamat'],
                    'tgl_lahir' => $value['tgl lahir'],
                    'no_telp' => $value['no telepon'],
                    'user_id' => $user->id,
                ]);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Added Successfully'], 200);
        } catch (\Exception $e) {
            dd($data);
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Data Added Failed'], 500);
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $user = \App\Models\User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role' => 'admin',
            ]);
            $petugas = \App\Models\Petugas::create([
                'name' => 'Admin',
                'nik' =>  rand(1000000000, 9999999999),
                'email' => $request->email,
                'alamat' => 'generatedAlamat',
                'tgl_lahir' => '1999-09-09',
                'no_telp' => '081234567890',
                'user_id' => $user->id,
                'is_admin' => true,
            ]);
            DB::commit();
            return back()->with('success', 'Registed Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return back()->with('error', 'Registration Failed');
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'nik' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required',
            'no_telp' => 'required',
            'password' => 'required',
        ]);
        try {
            DB::transaction(function () use ($request) {
                $user = \App\Models\User::create([
                    'username' => $request->username,
                    'password' => bcrypt($request->password),
                ]);

                $petugas = \App\Models\Petugas::create([
                    'name' => $request->nama,
                    'nik' => $request->nik,
                    'email' => $request->email,
                    'alamat' => $request->alamat,
                    'tgl_lahir' => $request->tgl_lahir,
                    'no_telp' => $request->no_telp,
                    'user_id' => $user->id,
                ]);
            });
        } catch (\Exception $e) {
            dd($e);
            return back()->with('error', 'Data Added Failed');
        }
        toast('Success', 'Data Added Successfully');
        return redirect('admin/akun-petugas')->with('success', 'Data Added Successfully');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'nik' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required',
            'no_telp' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $petugas = \App\Models\Petugas::find($id);
            $petugas->name = $request->nama;
            $petugas->nik = $request->nik;
            $petugas->email = $request->email;
            $petugas->alamat = $request->alamat;
            $petugas->tgl_lahir = $request->tgl_lahir;
            $petugas->no_telp = $request->no_telp;
            $petugas->save();

            $user = \App\Models\User::find($petugas->user_id);
            $user->username = $request->username;
            if ($request->password) {
                $user->password = bcrypt($request->password);
                // dd($user->password);
            }
            $user->save();

            DB::commit();
            toast('Success', 'Data Updated Successfully');
            return redirect('admin/akun-petugas')->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            toast('Error', 'Data Updated Failed');
            return redirect('admin/akun-petugas')->with('error', 'Data Updated Failed');
        }
    }
    public function destroy($id)
    {
        $petugas = \App\Models\Petugas::find($id);
        // $petugas->user->delete();
        $petugas->delete();
        toast('Success', 'Data Deleted Successfully');
        return redirect('admin/akun-petugas')->with('success', 'Data Deleted Successfully');
    }
}
