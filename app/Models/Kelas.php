<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'nama',
        'tahun_ajars_id',
        'gurus_id',
        'status'
    ];
}
