<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    protected $redirectTo = '/home';

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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required | max:20 | string' ,
            'ic' => 'required | max:12 | string',
            'address' => 'required | max:255 | string',
            'username' => 'required | max:20 | unique:users | string',
            'password' =>  'required|string|min:8|max:12|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$^+=!*()@%&]).{8,10}$',
            'phone' => 'required | max:11 | string',
            'role' => 'required | string'
        ]);

        $validateData['password'] = Hash::make($request->password);
        if ($validateData) {
            DB::table('users')->insert([$validateData]);
            return response()->json(['message' => 'Successfully saved', 200]);
        } else {
            return response()->json(['message' => 'Invalid format and no saved data']);
        }
    }
}
