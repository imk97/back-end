<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\App\User;
use Illuminate\Support\Facades\Hash;
use CreateOauthAccessTokensTable;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use HasApiTokens;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $role = DB::table('users')->where('email', $request->get('email'))
            ->value('role');
        $loginData = $request->validate([
            'email' => 'required | max:50 ',
            'password' => 'required | max:12',
        ]);
        if (!auth()->attempt($loginData)) {
            return response()->json(['message' => 'Invalid credentials'], 200);
        } else {
            if ($role == 1) {
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                return response()->json([
                    'user' => auth()->user(),
                    'access_token' => $accessToken,
                    'message' => 'user'
                ], 200);
            } else if ($role == 2) {
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                return response()->json([
                    'user' => auth()->user(),
                    'access_token' => $accessToken,
                    'message' => 'staff'
                ], 200);
            } else {
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                return response()->json([
                    'user' => auth()->user(),
                    'access_token' => $accessToken,
                    'message' => 'admin'
                ], 200);
            }
        }
    }

    public function logout() {
        if(Auth::check()) {
            Auth::user()->AauthAccessToken()->delete();
            Auth::logout();
        }
    }
}
