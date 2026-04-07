<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLogs extends Model
{
    protected $fillable = [
        'user_id',
        'event',
        'auditable_id',
        'auditable_type',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    // Cast kolom JSON menjadi array PHP otomatis
    protected $casts = [ //lakukan casting atau ubah bentukan
        'old_values' => 'array', //yang tadinya di database tersimpan tipe json dan ketika diambil ke php bakal berantakan
        'new_values' => 'array',// dgn casts, laravel otomatis memanggila decode_json dan merubahnya menjadi array asosiativ sehingga mudah dipanggil
    ];

    /**
     * Relasi ke User yang melakukan aksi
     */
    public function user(): BelongsTo //artinya adalah "milik siapa"
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Polymorphic ke model target (Product, Sale, dll)
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}