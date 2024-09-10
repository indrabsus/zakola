<?php

namespace App\Livewire\Sarpras;

use App\Http\Controllers\Controller;
use App\Models\BosRealisasi as ModelRealisasi;
use App\Models\Distribusi;
use Livewire\Component;
use App\Models\Pengajuan as TabelPengajuan;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class BosRealisasi extends Component
{
    public $id_realisasi, $nama_barang,
    $volume,$volume_realisasi, $satuan, $bulan_pengajuan,
    $bulan_pengajuan_realisasi, $tahun_arkas ,$id_role, $jenis,
    $perkiraan_harga, $perkiraan_harga_realisasi, $nama_kegiatan,
    $status, $volume_distribusi;
    use WithPagination;
    public $show = false;
    public $cari = '';
    public $result = 10;
    public function render()
    {
        $bos = new Controller;

        $role = Role::all();
        if(Auth::user()->id_role == 1 || Auth::user()->id_role == 16 || Auth::user()->id_role == 17){
        $data  = ModelRealisasi::leftJoin('pengajuan','pengajuan.id_pengajuan','bos_realisasi.id_pengajuan')
        ->leftJoin('roles','roles.id_role','pengajuan.id_role')
        ->orderBy('bos_realisasi.id_pengajuan','desc')->
        where('nama_barang', 'like','%'.$this->cari.'%')
        ->paginate($this->result);
        } else {
            $data  = ModelRealisasi::leftJoin('pengajuan','pengajuan.id_pengajuan','bos_realisasi.id_pengajuan')
        ->leftJoin('roles','roles.id_role','pengajuan.id_role')
        ->orderBy('bos_realisasi.id_pengajuan','desc')->
        where('nama_barang', 'like','%'.$this->cari.'%')
        ->where('pengajuan.id_role', Auth::user()->id_role)
        ->paginate($this->result);
        }
        return view('livewire.sarpras.bos-realisasi', compact('data','role','bos'));
    }

    public function clearForm(){
        $this->nama_barang = '';
        $this->nama_kegiatan = '';
        $this->volume = '';
        $this->satuan = '';
        $this->bulan_pengajuan = '';
        $this->jenis = '';
        $this->tahun_arkas = '';
        $this->perkiraan_harga = '';
    }
    public function edit($id){
        $data = ModelRealisasi::where('id_realisasi', $id)->first();
        $this->id_realisasi = $data->id_realisasi;
        $this->volume_realisasi =  $data->volume_realisasi;
        $this->bulan_pengajuan_realisasi = $data->bulan_pengajuan_realisasi;
        $this->perkiraan_harga_realisasi = $data->perkiraan_harga_realisasi;
        $this->status = $data->status;
    }

    public function update(){
        $semua = $this->validate([
            'volume_realisasi'=> 'required',
            'bulan_pengajuan_realisasi'=> 'required',
            'perkiraan_harga_realisasi'=> 'required',
            'status'=> 'required',
        ]);
        $data = ModelRealisasi::where('id_realisasi', $this->id_realisasi)->update([
            'volume_realisasi'=> $this->volume_realisasi,
            'bulan_pengajuan_realisasi'=> $this->bulan_pengajuan_realisasi,
            'perkiraan_harga_realisasi'=> $this->perkiraan_harga_realisasi,
            'status'=> $this->status
        ]);
        session()->flash('sukses','Data berhasil diedit');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function c_delete($id){
        $this->id_realisasi = $id;
    }
    public function delete(){
        ModelRealisasi::where('id_realisasi',$this->id_realisasi)->delete();
        session()->flash('sukses','Data berhasil dihapus');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function showKolom(){
        $this->show = !$this->show;
    }

    public function cx_distribusi($id){
        $data = ModelRealisasi::where('bos_realisasi.id_realisasi', $id)
        ->leftJoin('pengajuan','pengajuan.id_pengajuan','bos_realisasi.id_pengajuan')
        ->first();
        $this->id_realisasi = $id;
        $this->id_role = $data->id_role;
    }
    public function distribusi(){
        $this->validate([
            'id_role'=> 'required',
            'volume_distribusi' => 'required',
        ]);
        $hitung = ModelRealisasi::leftJoin('distribusi', 'distribusi.id_realisasi', '=', 'bos_realisasi.id_realisasi')
        ->where('bos_realisasi.id_realisasi', $this->id_realisasi)->first();
        $total = $hitung ->volume_realisasi - $hitung->volume_distribusi;
        if($total < $this->volume_distribusi){
            session()->flash('gagal','Volume distribusi melebihi volume realisasi');
            $this->clearForm();
        $this->dispatch('closeModal');
            return;
        }
        Distribusi::create([
            'id_realisasi'=> $this->id_realisasi,
            'id_role'=> $this->id_role,
            'volume_distribusi'=> $this->volume_distribusi
        ]);
        session()->flash('sukses','Data berhasil ditambahkan');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
}