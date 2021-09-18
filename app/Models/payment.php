<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class payment extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'transaction_id',
        'error_code',
    ];

    /**
     * Get the user for the user.
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
