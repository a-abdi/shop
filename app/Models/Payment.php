<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'order_id',
        'transaction_id',
        'track_id',
        'card_no',
        'status_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'order_id',
        'transaction_id',
        'track_id',
    ];


    /**
     * Get the order for the user.
     */
    public function order()
    {
        return $this->hasOne(Order::class);
    }

    /**
     * Get the user for the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
