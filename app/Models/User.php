<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\EmployeeInvitationMail;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         
        'company',
        'admin_id',
        'first_name',    
        'last_name',     
        'street',
        'house_number',
        'zip_code',
        'city',
        'country',
        'plan_name',    
        'max_employees',  
        'has_active_subscription', 
        'mollie_customer_id',    
        'avv_accepted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function schueler()
{
    // Dies setzt voraus, dass du eine Pivot-Tabelle 'employee_schueler' 
    // oder 'schueler_user' hast. 
    return $this->belongsToMany(Schueler::class, 'employee_schueler', 'employee_id', 'schueler_id');
}

    // --- RELATIONEN ---

    public function employees()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    public function positions()
    {
        return $this->hasMany(Position::class, 'admin_id');
    }

    public function zeiteintraege()
    {
        return $this->hasMany(\App\Models\Zeiteintrag::class, 'user_id');
    }
    public function sendPasswordResetNotification($token)
    {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

        // Wir "missbrauchen" hier deine Mailable-Klasse, 
        // um das Passwort-Reset-Token in deinem Design zu senden.
        \Mail::to($this->email)->send(new EmployeeInvitationMail($this->name, $url));
    }
}