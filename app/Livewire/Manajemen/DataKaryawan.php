<?php

namespace App\Livewire\Manajemen;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Setingan;
use App\Models\User;
use Livewire\Component;
use App\Models\DataUser as TabelDataUser;
use Livewire\WithPagination;

class DataKaryawan extends Component
{
    public $id_role, $id_data, $nama_lengkap, $jenkel, $no_hp, $alamat, $id_user;
    use WithPagination;

    public $cari = '';
    public $result = 10;
    public function render()
    {
        $role = Role::where('id_role','<>', 1)->get();
        $data  = TabelDataUser::orderBy('id_data','desc')->
        where('nama_lengkap', 'like','%'.$this->cari.'%')
        ->leftJoin('users','users.id','=','data_user.id_user')
        ->leftJoin('roles','roles.id_role','=','users.id_role')
        ->paginate($this->result);
        return view('livewire.manajemen.data-karyawan', compact('data','role'));
    }
    public function insert(){
        $this->validate([
            'id_role' => 'required',
            'nama_lengkap'=> 'required',
            'jenkel' => 'required',
            'no_hp'=> 'required',
            'alamat'=> 'required',
        ]);
        $set = Setingan::where('id_setingan', 1)->first();
        $user = User::create([
            'username'=> substr(rand(100, 999).strtolower(str_replace(' ','', $this->nama_lengkap)),0,10),
            'password' => bcrypt($set->default_password),
            'id_role' => $this->id_role,
            'acc' => 'y'
        ]);
        $data = TabelDataUser::create([
            'id_user' => $user->id,
            'nama_lengkap'=> ucwords($this->nama_lengkap),
            'jenkel'=> $this->jenkel,
            'no_hp'=> $this->no_hp, 
            'alamat'=> $this->alamat
        ]) ;
        session()->flash('sukses','Data berhasil ditambahkan');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function clearForm(){
        $this->id_role = '';
        $this->nama_lengkap = '';
        $this->jenkel = '';
        $this->no_hp = '';
        $this->alamat = '';
    }
    public function edit($id){
        $data = TabelDataUser::leftJoin('users','users.id','=','data_user.id_user')
        ->where('id_data', $id)->first();
        $this->id_user = $data->id_user;
        $this->nama_lengkap = $data->nama_lengkap;
        $this->jenkel = $data->jenkel;
        $this->no_hp = $data->no_hp;
        $this->alamat = $data->alamat;
        $this->id_data = $id;
        $this->id_role = $data->id_role;
    }
    public function update(){
        $this->validate([
            'id_user' => 'required',
            'nama_lengkap'=> 'required',
            'jenkel' => 'required',
            'no_hp'=> 'required',
            'alamat'=> 'required',
        ]);
        $data = TabelDataUser::where('id_data', $this->id_data)->update([
            'id_user' => $this->id_user,
            'nama_lengkap'=> $this->nama_lengkap,
            'jenkel'=> $this->jenkel,
            'no_hp'=> $this->no_hp, 
            'alamat'=> $this->alamat
        ]);
        session()->flash('sukses','Data berhasil diedit');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function c_delete($id){
        $this->id_data = $id;
    }
    public function delete(){
        TabelDataUser::where('id_data',$this->id_data)->delete();
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
