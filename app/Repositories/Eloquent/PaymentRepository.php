<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Contracts\Repositories\PaymentRepositoryInterface;
use phpDocumentor\Reflection\Types\Boolean;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(private Payment $payment) 
    {
        parent::__construct($payment);
    }

    public function verify(array $data)
    {
        return $verify = Payment::where([
            ['order_id', '=', $data['order_id']],
            ['transaction_id', '=', $data['id']],
        ])->exists();
    }
}
