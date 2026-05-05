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

    try {
        // Prüfe, ob es wie ein verschlüsselter String aussieht
        if (str_starts_with($value, 'eyJpdiI')) {
            return decrypt($value);
        }
        return $value; // Falls bereits Klartext
    } catch (\Exception $e) {
        return $value; // Fallback bei Fehlern
    }
}
}