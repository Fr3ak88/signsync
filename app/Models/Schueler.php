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


}