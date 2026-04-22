<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Zeiteintrag extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'zeiteintraege';

    protected $fillable = [
        'user_id',
        'schueler_id',
        'start_zeit',
        'ende_zeit',
        'pause_minuten', 
        'notiz',
        'typ',
        'is_locked',
        'content_hash' 
    ];

    protected $casts = [
        'notiz' => 'encrypted',
        'start_zeit' => 'datetime',
        'ende_zeit' => 'datetime',
        'pause_minuten' => 'integer',
        'is_locked' => 'boolean',
    ];

    /**
     * Konfiguration für das Activity-Log (GoBD Nachvollziehbarkeit)
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
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