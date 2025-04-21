<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'karyawan';

    // Fillable 
    protected $fillable = [
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'email',
        'foto',
        'status',
    ];

    // One-to-Many
    public function jobs()
    {
        return $this->hasMany(Job::class, 'karyawan_id');
    }

    // One-to-One
    public function job(): HasOne
    {
        return $this->hasOne(Job::class, 'karyawan_id');
    }

    public function division()
    {
        return $this->hasOneThrough(
            Division::class,
            Job::class,
            'karyawan_id', // Foreign key on jobs table...
            'id', // Foreign key on divisions table...
            'id', // Local key on employees table...
            'divisi_id' // Local key on jobs table...
        );
    }
}