<?php

namespace App\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    public function getCart(int $userId);

    public function totalPrice(int $userId);
    
    public function totalDiscount(int $userId);
}