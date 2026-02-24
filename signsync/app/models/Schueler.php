<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schueler extends Model
{
    protected $fillable = [\'name\',\'schule\',\'notizen\'];

    public function zeiteintraege()
    {
        return $this->hasMany(Zeiteintrag::class);
    }
}