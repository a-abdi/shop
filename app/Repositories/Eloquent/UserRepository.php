<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Contracts\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(private User $user) 
    {
        parent::__construct($user);
    }
}
