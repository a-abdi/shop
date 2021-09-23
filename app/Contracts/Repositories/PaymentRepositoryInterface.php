<?php

namespace App\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function verify(array $data);
}