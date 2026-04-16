<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
protected function validator(array $data)
{
    return Validator::make($data, [
        'company'      => ['required', 'string', 'max:255'], // NUR Regeln hier!
        'first_name'   => ['required', 'string', 'max:255'],
        'last_name'    => ['required', 'string', 'max:255'],
        'street'       => ['required', 'string', 'max:255'],
        'house_number' => ['required', 'string', 'max:20'],
        'zip_code'     => ['required', 'string', 'max:10'],
        'city'         => ['required', 'string', 'max:255'],
        'country'      => ['required', 'string'],
        'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password'     => ['required', 'string', 'min:8', 'confirmed'],
        'avv_confirm' => ['required', 'accepted'],
    ]);
}

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
protected function create(array $data)
{
return User::create([
        'company'      => $data['company'],
        'name'         => $data['first_name'] . ' ' . $data['last_name'],
        'street'       => $data['street'],
        'house_number' => $data['house_number'],
        'zip_code'     => $data['zip_code'],
        'city'         => $data['city'],
        'country'      => $data['country'],
        'email'        => $data['email'],
        'password'     => Hash::make($data['password']),
        'avv_accepted_at' => now(),
        'role'         => 'admin',
    ]);
}
}
