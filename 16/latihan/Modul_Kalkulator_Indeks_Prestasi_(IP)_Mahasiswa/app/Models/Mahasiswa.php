<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'total_mutu',
        'total_sks',
        'watermark',
    ];

    protected $casts = [
        'total_mutu' => 'float',
        'total_sks' => 'integer',
    ];
}
