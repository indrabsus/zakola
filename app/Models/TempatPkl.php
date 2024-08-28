<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatPkl extends Model
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'id_tempat';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "tempat_pkl";
    protected $guarded = [];
}