<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konfigurasi_siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'siswas_id',
        'status'
    ];
}
