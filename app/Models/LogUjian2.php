<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogUjian2 extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'id_log';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "log_ujian2";

    protected $guarded = [];
}