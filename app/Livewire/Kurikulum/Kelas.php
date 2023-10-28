<?php

namespace App\Livewire\Kurikulum;

use App\Models\Angkatan;
use App\Models\Jurusan;
use Livewire\Component;
use App\Models\Kelas as TabelKelas;
use Livewire\WithPagination;

class Kelas extends Component
{
    public $id_kelas, $nama_kelas, $id_jurusan, $tingkat;
    use WithPagination;

    public $cari = '';
    public $result = 10;
    public function render()
    {
        $angkatan = Angkatan::all();
        $jurusan = Jurusan::all();
        $data  = TabelKelas::leftJoin('jurusan','jurusan.id_jurusan','kelas.id_jurusan')->where('nama_kelas', 'like','%'.$this->cari.'%')->paginate($this->result);
        return view('livewire.kurikulum.kelas', compact('data','jurusan','angkatan'));
    }
    public function insert(){
        $this->validate([
            'nama_kelas' => 'required',
            'id_jurusan' => 'required',
            'tingkat' => 'required',
        ]);
        $data = TabelKelas::create([
            'nama_kelas' => $this->nama_kelas,
            'id_jurusan' => $this->id_jurusan,
            'tingkat' => $this->tingkat,
        ]) ;
        session()->flash('sukses','Data berhasil ditambahkan');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function clearForm(){
        $this->nama_kelas = '';
        $this->id_jurusan = '';
        $this->tingkat = '';
    }
    public function edit($id){
        $data = TabelKelas::where('id_kelas', $id)->first();
        $this->nama_kelas = $data->nama_kelas;
        $this->id_kelas = $data->id_kelas;
        $this->id_jurusan = $data->id_jurusan;
        $this->tingkat = $data->tingkat;
    }
    public function update(){
        $this->validate([
            'nama_kelas' => 'required',
            'id_jurusan' => 'required',
            'tingkat' => 'required',
        ]);
        $data = TabelKelas::where('id_kelas', $this->id_kelas)->update([
            'nama_kelas' => $this->nama_kelas,
            'id_jurusan' => $this->id_jurusan,
            'tingkat' => $this->tingkat,
        ]);
        session()->flash('sukses','Data berhasil diedit');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
    public function c_delete($id){
        $this->id_kelas = $id;
    }
    public function delete(){
        TabelKelas::where('id_kelas',$this->id_kelas)->delete();
        session()->flash('sukses','Data berhasil dihapus');
        $this->clearForm();
        $this->dispatch('closeModal');
    }
}