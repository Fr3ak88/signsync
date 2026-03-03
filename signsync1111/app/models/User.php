<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

 protected $fillable = [
    'first_name',
    'last_name',
    'name', // Kannst du behalten oder später entfernen, wenn Vor-/Nachname reichen
    'email',
    'password',
    'street',
    'house_number',
    'zip_code',
    'city',
    'country',
];
