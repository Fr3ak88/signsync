<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schueler extends Model
{
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

protected $casts = [
    'name' => 'encrypted',
    'birth_date' => 'date', 
];

public function getNameAttribute($value)
{
    try {
        // Falls das Feld in $casts als 'encrypted' steht, 
        // versucht Laravel es hier automatisch zu entschlüsseln.
        return decrypt($value);
    } catch (\Exception $e) {
        // Falls die Entschlüsselung fehlschlägt (Payload invalid),
        // gib den rohen Wert aus der Datenbank zurück.
        return $value;
    }
}
}