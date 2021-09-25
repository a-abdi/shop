<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\PaymentRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Services\HttpService;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\ServerErrorException;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private CartRepositoryInterface $cartRepository,
        private OrderRepositoryInterface $orderRepository,
        private Httpservice $httpService,
        private CartService $cartService,
    )
    {}

    public function create()
    {
        $userId = Auth::id();

        $cart = $this->cartRepository->getCart($userId);

        if (!$cart->count()) {
            throw new NotFoundException(__('messages.not_found', ["name" => "cart"]));
        }

        $this->cartService->updateCart($userId);

        $totalPrice = $this->cartRepository->totalPrice($userId);

        $totalDiscount = $this->cartRepository->totalDiscount($userId);

        $amount = $totalPrice - $totalDiscount;

        if ($amount < 1000 || $amount > 500000000) {
            throw new InvalidArgumentException;
        }
        
        $orderID = Str::random(50);

        $header = [
            'X-API-KEY' => '0aae5730-0256-4e4e-a0ff-900b9d4aef76',
            'X-SANDBOX' => true,
        ];

        $url = 'https://api.idpay.ir/v1.1/payment';

        $data = [
            "order_id" => $orderID,
            "amount"   => $amount,
            "callback" => 'http://api.a-abdi.ir/api/payment/verify'
        ];

        $response = $this->httpService->post($header, $url, $data);

        if($response->status() != 201) {
            throw new ServerErrorException;
        }

        $this->paymentRepository->create([
            'user_id' => $userId,
            'amount' => $amount,
            'order_id' => $orderID,
            'transaction_id' => $response->object()->id,
        ]);

        return $response->object()->link;  
    }

    public function verify(Request $request)
    {
        $payment = $this->paymentRepository->where('order_id', $request->order_id);
        
        if ($request->status != 10) {
            // redirect to page error
            $this->paymentRepository->update($request->only([
                'track_id',
                'card_no',
                'status_code',
            ]), $payment);
        }

        $verify = $this->paymentRepository->verify($request->only(['order_id', 'id']));

        if (!$verify) {
            $this->paymentRepository->update([
                'track_id' => $request->track_id,
                'card_no' => $request->card_no,
                'statsus_code' => 20,
            ], $payment);
            # redirect to page error
        }

        $header = [
            'X-API-KEY' => '0aae5730-0256-4e4e-a0ff-900b9d4aef76',
            'X-SANDBOX' => true,
        ];

        $url = 'https://api.idpay.ir/v1.1/payment/verify';

        $data = [
            "id"   => $request->id,
            "order_id" => $request->order_id,
        ];

        $response = $this->httpService->post($header, $url, $data);

        if ($response->status() != 200) {
            $this->paymentRepository->update([
                'track_id' => $response->object()->track_id,
                'card_no'  => $response->object()->payment->card_no,
                'status_code' => 21,
            ], $payment);
             # redirect to page error server error
        }

        if ($response->object()->status != 100) {
            $this->paymentRepository->update([
                'track_id' => $response->object()->track_id,
                'card_no'  => $response->object()->payment->card_no,
                'status_code' => $response->object()->status,
            ], $payment);
    
            # redirect to page error with message idpay
        }
        
        $this->paymentRepository->update([
            'track_id' => $response->object()->track_id,
            'card_no'  => $response->object()->payment->card_no,
            'status_code' => $response->object()->status,
        ], $payment);

        $order = $this->orderRepository->create([ 'payment_id' => $payment->id, 'status_code' => 1 ]);

        $this->cartRepository->registerOrder($payment->user_id, $order->id);

        #redirect to success page with necessary data
    }
}
