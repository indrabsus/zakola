<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TampungSoal extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'id_tampung';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = "tampung_soal";
    protected $guarded = [];
}