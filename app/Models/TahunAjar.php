<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjar extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'semester',
        'keterangan',
        'status'
    ];

}
