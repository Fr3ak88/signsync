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
    // 'name' => 'encrypted',
    'birth_date' => 'date', 
];

public function getNameAttribute($value)
{
    if (empty($value)) return $value;

    // Wenn der Wert NICHT mit "eyJpdiI" anfängt, ist es Klartext -> direkt zurückgeben
    if (!str_starts_with($value, 'eyJpdiI')) {
        return $value;
    }

    try {
        // Es sieht nach Verschlüsselung aus, also versuchen wir es
        return decrypt($value);
    } catch (\Exception $e) {
        // Wenn es trotzdem scheitert (falscher Key), zeige einen Hinweis
        // oder zumindest einen Teil des Salats, damit man weiß, da ist was
        return "Unlesbar (Key-Fehler)"; 
    }
}
}