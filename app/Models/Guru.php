<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'pendidikan',
        'tempat_lahir',
        'tanggal_lahir',
        'telepon',
        'tanggal_bergabung',
        'alamat',
        'status'
    ];
}
