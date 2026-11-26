<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    use HasFactory;

    // Jika nama tabel di database Anda adalah 'kontak' (singular) atau 'kontaks' (plural), 
    // sesuaikan properti $table di bawah ini (Laravel otomatis mendeteksi plural 'kontaks').
    protected $table = 'kontak'; 

    protected $fillable = [
        'nama',
        'email',
        'pesan',
    ];
}