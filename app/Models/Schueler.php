<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schueler extends Model
{
    // Falls deine Tabelle "schuelers" heißt (wie wir in db:show gesehen haben):
    protected $fillable = [
    'name', 
    'admin_id', 
    'birth_date', 
    'school_name'
];

public function mitarbeiter()
{
    return $this->belongsToMany(User::class, 'schueler_user');
}

public function zugewieseneSchueler()
{
    return $this->belongsToMany(Schueler::class, 'employee_schueler', 'user_id', 'schueler_id');
}

protected $casts = [
    'name' => 'encrypted',
    'birth_date' => 'date', 
];
}