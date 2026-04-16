<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monatsabschluss extends Model
{
    protected $table = 'monats_abschluesse';

    protected $fillable = [
        'user_id',
        'monat',
        'jahr',
        'abgeschlossen_am',
        'schule_signatur',
        'employee_signatur',
        'schule_unterzeichner',
        'ist_abgeschlossen',
        'is_internal',
        'pdf_path',
        'file_hash',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'abgeschlossen_am'  => 'datetime',
        'unterzeichnet_am'  => 'datetime',
        'cancelled_at' => 'datetime',
        'is_internal'       => 'boolean',
        'ist_abgeschlossen' => 'boolean',
    ];

    /**
     * Beziehung zum User (Mitarbeiter), dem dieser Abschluss gehört.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}