<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_id',
        'kode_desc_id',
        'desc',
        'value',
        'status'
    ];
}
