<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Zeiteintrag extends Model
{
    protected $fillable = [
        \'user_id\',\'schueler_id\',\'datum\',
        \'start_zeit\',\'ende_zeit\',\'pause_minuten\',
        \'dauer_min\',\'status\',\'signature_data\',\'admin_kommentar\'
    ];

    protected $casts = [
        \'datum\' => \'date\',
        \'start_zeit\' => \'datetime\',
        \'ende_zeit\' => \'datetime\',
    ];

    public function schueler()
    {
        return $this->belongsTo(Schueler::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}