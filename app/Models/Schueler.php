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

        if (empty($value)) return $value;



        try {

            // Laravel hat durch das $casts['name' => 'encrypted']

            // den Wert eigentlich schon entschlüsselt.

            // Falls es aber knallt, fangen wir es hier ab.

            return decrypt($value);

        } catch (\Exception $e) {

            // Falls der Wert schon Klartext ist (wie deine jetzigen Testdaten)

            // oder der Key falsch ist, gib den Rohwert zurück.

            return $value;

        }

    }

}