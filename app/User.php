<?php

namespace App;

use App\Notifications\PasswordResetNotification;
use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;


class User extends Authenticatable
{
    //
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $fillable = ['name','ic','address','username','password','phone','role'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function AauthAccessToken() {
        return $this->hasMany('\App\OauthAccessToken');
    }
}
