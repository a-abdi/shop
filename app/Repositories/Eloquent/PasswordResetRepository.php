<?php

namespace App\Repositories\Eloquent;

use App\Models\PasswordReset;
use App\Contracts\Repositories\PasswordResetRepositoryInterface;

class PasswordResetRepository extends BaseRepository implements PasswordResetRepositoryInterface
{
    public function __construct(private PasswordReset $passwordreset) 
    {
        parent::__construct($passwordreset);
    }
}
