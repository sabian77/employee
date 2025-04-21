<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'karyawan_id');
    }

    // Many-to-One
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'divisi_id');
    }
}