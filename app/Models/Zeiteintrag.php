<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Zeiteintrag extends Model
{
    use SoftDeletes, LogsActivity; // GoBD-Grundpfeiler

    protected $table = 'zeiteintraege';

    protected $fillable = [
        'user_id',
        'schueler_id',
        'start_zeit',
        'ende_zeit',
        'pause_minuten', // <--- NEU: Für die automatische Pausenberechnung
        'notiz',
        'typ',
        'is_locked',    // Für GoBD Sperre
        'content_hash'  // Für Revisions-Check
    ];

    protected $casts = [
        'notiz' => 'encrypted',
        'start_zeit' => 'datetime',
        'ende_zeit' => 'datetime',
        'pause_minuten' => 'integer', // <--- NEU: Damit immer mit Zahlen gerechnet wird
        'is_locked' => 'boolean',
    ];

    /**
     * Konfiguration für das Activity-Log (GoBD Nachvollziehbarkeit)
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()        // Protokolliert alle wichtigen Felder inkl. Pause
            ->logOnlyDirty()       // Speichert nur echte Änderungen
            ->dontSubmitEmptyLogs();
    }

    // --- Beziehungen ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schueler()
    {
        return $this->belongsTo(Schueler::class);
    }
}