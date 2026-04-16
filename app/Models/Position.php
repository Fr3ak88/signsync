<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    // admin_id statt user_id
    protected $fillable = ['admin_id', 'name'];

    public function user() {
        // Wir sagen Laravel explizit, dass der Fremdschlüssel admin_id heißt
        return $this->belongsTo(User::class, 'admin_id');
    }
}