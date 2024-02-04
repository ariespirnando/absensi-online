<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konfigurasi_pelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'pelajarans_id',
        'gurus_id',
        'status'
    ];
}
