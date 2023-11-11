<?php

namespace App\Livewire\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\DataPeminjam;
use App\Models\Kelas;
use App\Models\Setingan;
use App\Models\User;
use Livewire\Component;
use App\Models\DataSiswa as TabelSiswa;
use App\Models\TabunganSiswa;
use Livewire\WithPagination;

class DataSiswa extends Component
{
    public $id_siswa, $id_user, $id_kelas, $jenkel, $no_hp, $alamat, $nama_lengkap;
    use WithPagination;

    public $cari = '';
    public $result = 10;
    public function render()
    {
        $kelas = Kelas::leftJoin('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')->get();
        $data  = TabelSiswa::leftJoin('users','users.id','=','data_siswa.id_user')
        ->leftJoin('kelas','kelas.id_kelas','=','data_siswa.id_kelas')
        ->leftJoin('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')
        ->orderBy('id_siswa','desc')
        ->where('nama_lengkap', 'like','%'.$this->cari.'%')
        ->paginate($this->result);
        return view('livewire.kurikulum.data-siswa', compact('data','kelas'));
    }
    public function insert(){
        $this->validate([
            'id_kelas'=> 'required',
            'jenkel' => 'required',
            'no_hp'=> 'required',
            'alamat'=> 'required',
            'nama_lengkap'=> 'required',
        ]);
        $set = Setingan::where('id_setingan', 1)->first();
        $user = User::create([
            'username'=> substr(rand(100, 999).strtolower(str_replace(' ','', $this->nama_lengkap)),0,10),
            'password' => bcrypt($set->default_password),
            'id_role' => 8,
            'acc' => 'y'
        ]);
        $data2 = TabelSiswa::create([
            'id_user' => $user->id,
            'nama_lengkap'=> ucwords($this->nama_lengkap),
            'jenkel'=> $this->jenkel,
            'no_hp'=> $this->no_hp,
            'alamat'=> $this->alamat,
            'id_kelas' => $this->id_kelas,
        ]) ;


        TabunganSiswa::create([
            "id_siswa" => $data2->id_siswa,
            "jumlah_saldo" => 0,
        ]);

        session()->flash('sukses','Data berhasil ditambahkan');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function clearForm(){
        $this->id_kelas = '';
        $this->nama_lengkap = '';
        $this->jenkel = '';
        $this->no_hp = '';
        $this->alamat = '';
    }
    public function edit($id){
        $data = TabelSiswa::leftJoin('users','users.id','=','data_siswa.id_user')
        ->where('id_siswa', $id)->first();
        $this->id_user = $data->id_user;
        $this->nama_lengkap = $data->nama_lengkap;
        $this->jenkel = $data->jenkel;
        $this->no_hp = $data->no_hp;
        $this->alamat = $data->alamat;
        $this->id_user = $id;
        $this->id_kelas = $data->id_kelas;
        $this->id_siswa = $data->id_siswa;
    }
    public function update(){
        $this->validate([
            'id_kelas'=> 'required',
            'jenkel' => 'required',
            'no_hp'=> 'required',
            'alamat'=> 'required',
            'nama_lengkap'=> 'required',
        ]);
        $data = TabelSiswa::where('id_siswa', $this->id_siswa)->update([
            'id_user' => $this->id_user,
            'nama_lengkap'=> ucwords($this->nama_lengkap),
            'jenkel'=> $this->jenkel,
            'no_hp'=> $this->no_hp,
            'alamat'=> $this->alamat,
            'id_kelas' => $this->id_kelas,
        ]);
        session()->flash('sukses','Data berhasil diedit');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function c_delete($id){
        $this->id_user = $id;
    }
    public function delete(){
        User::where('id', $this->id_user)->delete();
        session()->flash('sukses','Data berhasil dihapus');
        $this->clearForm();
        $this->dispatch('closeModal');
    }

    public function c_reset($id){
        $this->id_user = $id;
    }
    public function p_reset(){
        $set = new Controller;
        $set->resetPass($this->id_user);
        session()->flash('sukses','Password berhasil direset');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
}