<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $fillable = [
        'konfigurasi_pelajarans_id',
        'gurus_id',
        'users_id',
        'keterangan',
        'absensis_start',
        'absensis_end',
        'status'
    ];
}
