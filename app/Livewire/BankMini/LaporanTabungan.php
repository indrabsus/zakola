<?php

namespace App\Livewire\BankMini;

use App\Models\DataSiswa;
use Livewire\Component;
use App\Models\LaporanTabungan as TabelLaporan;
use App\Models\LaporanTabungan as ModelsLaporanTabungan;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class LaporanTabungan extends Component
{
    public $tahun_masuk, $id_angkatan;
    use WithPagination;
    
    public $cari = '';
    public $result = 10;
    public function render()
    {
        $sumkredit = DB::table('log_tabungan')->where('jenis', 'kd')->sum('nominal');
        $sumdebit = DB::table('log_tabungan')->where('jenis', 'db')->sum('nominal');
        $total = $sumkredit - $sumdebit;
        $count = DB::table('log_tabungan')->distinct()->pluck('id_siswa')->count();

        $data  = TabelLaporan::orderBy('id_laporan','desc')->where('jumlah_nasabah', 'like','%'.$this->cari.'%')->paginate($this->result);
        return view('livewire.bank-mini.laporan-tabungan', compact('data','sumkredit','sumdebit','total', 'count'));
    }
}