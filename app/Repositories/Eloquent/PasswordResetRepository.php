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

    public function deleteToken($email)
    {
        return $this->passwordreset->where('email', $email)->delete();
    }

    public function getEmail($token)
    {
        return $this->passwordreset->where('token', $token)->value('email');
    }
}
