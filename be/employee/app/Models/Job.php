<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'pekerjaan';

    // Fillable 
    protected $fillable = [
        'karyawan_id',
        'divisi_id',
        'gaji',
    ];

    // Many-to-One
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Many-to-One
    public function division()
    {
        return $this->belongsTo(Division::class, 'divisi_id');
    }
}