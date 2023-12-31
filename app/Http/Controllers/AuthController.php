<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginpage(){
        return view('login');
    }
    public function lupausername(){
        return view('lupausername');
    }
    public function registerpage(){
        $kelas = Kelas::leftJoin('jurusan','jurusan.id_jurusan','kelas.id_jurusan')->get();
        return view('register', compact('kelas'));
    }
    public function register(Request $request){
        $auth = $request->validate([
            'username' => 'required',
            'no_hp' => 'required',
            'nama_lengkap' => 'required',
            'jenkel' => 'required',
            'id_kelas' => 'required',
            'nis' => 'required|unique:data_siswa',
        ]);
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->nis),
            'id_role' => 8,
            'acc' => 'n',
        ]);
        DataSiswa::create([
            'id_user' => $user->id,
            'nama_lengkap' => ucwords($request->nama_lengkap),
            'jenkel' => $request->jenkel,
            'no_hp' => $request->no_hp,
            'nis' => $request->nis,
            'id_kelas' => $request->id_kelas,
        ]);
        return redirect()->route('loginpage')->with('sukses','Pendaftaran berhasil, silakan tunggu ACC Admin untuk login!');
    }
    public function login(Request $request){
        $auth = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($auth)){
            if(Auth::user()->acc == 'n'){
                Auth::logout();
    return redirect()->route('loginpage')->with('gagal','Akun anda belum diaktifkan oleh Admin!');
            } else {
                $data = Role::where('id_role', Auth::user()->id_role)->first();
                $role = $data->nama_role;

                return redirect()->route('dashboard');
            }


        } else {
            return redirect()->route('loginpage')->with('gagal', 'Username dan Password Salah!');
        }
}
public function logout(){
    Auth::logout();
    return redirect()->route('loginpage');
}

public function updatePassword(Request $request){
    $request->validate([
        'old_pass' => 'required',
        'password' => 'required|min:4',
        'k_pass' => 'required'
    ]);
    $global = new Controller;
    $user = User::where('id', Auth::user()->id)->first();
    if(password_verify($request->old_pass, $user->password)){
        if($request->password == $request->k_pass){
            $global->changePassword(Auth::user()->id, $request->password);
        } else {
            return redirect()->route('ubahpassword')->with('gagal', 'Password dan Konfirmasi Password harus sama!');
        }
    } else {
        return redirect()->route('ubahpassword')->with('gagal', 'Masukan Password Saat ini dengan benar!');
    }

    return redirect()->route('ubahpassword')->with('sukses', 'Pastikan akan mencatat password baru anda!');
}
}
