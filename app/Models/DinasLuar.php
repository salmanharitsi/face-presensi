<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DinasLuar extends Model
{
    use HasFactory;

    protected $table = 'dinas_luar';

    protected $fillable = [
        'user_id',
        'tanggal',
        'keterangan',
        'status',
        'surat_izin'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dinasLuar) {
            $dinasLuar->id = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
