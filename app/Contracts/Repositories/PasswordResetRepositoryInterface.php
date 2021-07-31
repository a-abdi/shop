<?php

namespace App\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;

interface PasswordResetRepositoryInterface extends BaseRepositoryInterface
{
    public function deleteToken($email);

    public function getEmail($token);

}