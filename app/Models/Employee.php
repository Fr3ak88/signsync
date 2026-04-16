<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'admin_id', // Wichtig, damit wir wissen, zu welcher Firma der Employee gehört
        'first_name',
        'last_name',
        'email',
        'position'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * NEU: Die Beziehung zu den Schülern
     */
    public function schueler()
{
    // Falls du die m:n Beziehung (Pivot-Tabelle) nutzt, wie wir sie besprochen hatten:
    return $this->belongsToMany(Schueler::class, 'employee_schueler', 'employee_id', 'schueler_id');
}
}