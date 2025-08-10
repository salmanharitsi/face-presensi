<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($presensi) {
            $presensi->id = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
