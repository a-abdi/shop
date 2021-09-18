<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Product;
use App\Models\Payment as UserPayment;
use App\Models\OauthAccessToken;
use App\Models\PersonalInformation;
use Faker\Provider\ar_SA\Payment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the products for the user.
     */
    public function product()
    {
        return $this->hasMany(Product::class, 'orders');
    }

    /**
     * Get the personal information associated with the user.
     */
    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class);
    }

    /**
     * Get the payment for the user.
     */
    public function payment()
    {
        return $this->hasMany(UserPayment::class);
    }


    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }
}
