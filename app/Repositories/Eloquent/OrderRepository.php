<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Contracts\Repositories\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(private Order $order) 
    {
        parent::__construct($order);
    }
}
