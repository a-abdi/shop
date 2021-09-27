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
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private PaymentService $paymentService,
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
            'X-API-KEY' =>  config('payment.X-API-KEY'),
            'X-SANDBOX' =>  config('payment.X-SANDBOX'),
        ];

        $url = config('payment.create_url');

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
        $payment = $this->paymentRepository->getPayment($request->only(['order_id', 'id']));
        $baseDomain = $this->httpService->baseDomainFrontend();
        
        if (!$payment) {
            $data = $this->paymentService->dataUpdate(
                $request->track_id, 
                $request->card_no, 
                20
            );
            $this->paymentRepository->update($data, $payment);
           
            return redirect($baseDomain.'/order/error');
        }

        if ($request->status != 10) {
            $data = $this->paymentService->dataUpdate(
                $request->track_id, 
                $request->card_no, 
                $request->status
            );
            $this->paymentRepository->update($data, $payment);
            
            return redirect($baseDomain.'/order/error');
        }

        $header = [
            'X-API-KEY' =>  config('payment.X-API-KEY'),
            'X-SANDBOX' =>  config('payment.X-SANDBOX'),
        ];

        $url = config('payment.verify_url');

        $data = [
            "id"   => $request->id,
            "order_id" => $request->order_id,
        ];

        $response = $this->httpService->post($header, $url, $data);

        if ($response->status() != 200) {
            $data = $this->paymentService->dataUpdate(
                $response->object()->track_id, 
                $response->object()->payment->card_no, 
                21
            );
            $this->paymentRepository->update($data, $payment);

            return redirect($baseDomain.'/order/error');
        }

        if ($response->object()->status != 100) {
            $data = $this->paymentService->dataUpdate(
                $response->object()->track_id, 
                $response->object()->payment->card_no, 
                $response->object()->status
            );
            $this->paymentRepository->update($data, $payment);

            return redirect($baseDomain.'/order/error');
        }

        $data = $this->paymentService->dataUpdate(
            $response->object()->track_id, 
            $response->object()->payment->card_no, 
            $response->object()->status
        );
        $this->paymentRepository->update($data, $payment);

        $order = $this->orderRepository->create([ 'payment_id' => $payment->id, 'status_code' => 1 ]);

        $this->cartRepository->registerOrder($payment->user_id, $order->id);

        return redirect($baseDomain.'/order/success');
    }

    public function latestMessage()
    {
        $payment = $this->paymentRepository->getLastPayment(Auth::id());

        return $this->successResponse(message: __('payment.'.$payment->status_code));
    }
}
