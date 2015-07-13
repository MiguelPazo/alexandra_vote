<?php namespace Ale;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    protected $fillable = [];
    protected $hidden = ['password', 'remember_token'];
    public $timestamps = false;


    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeUser($query, $user)
    {
        $query->where('user', $user);
    }
}
