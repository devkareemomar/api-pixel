<?php

namespace App\Http\Controllers;

use App\Interfaces\LinkInterface;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderProject;
use App\Support\Payment\Enum\Status;
use App\Models\PaymentGateway;
use App\Support\Payment\Gateways\MyFatoorahPayment;
use App\Support\Payment\Gateways\TapPayment;
use App\Support\Payment\Payment;
use App\Http\Requests\LinkRequest;

class LinkController extends BaseApiController
{
    private $link;
    protected Payment $payment;

    public function __construct(LinkInterface $link)
    {
        $this->link = $link;
        $currentActivePayment = PaymentGateway::where('status', true)->first();
        $payment = ['tap' => TapPayment::class, 'myfatorah' => MyFatoorahPayment::class];

        if ($currentActivePayment) {
            $this->payment = new Payment((new $payment[strtolower($currentActivePayment->name)]()));
        } else {
            $this->payment = new Payment((new TapPayment()));
        }
    }


   
    public function link($code)
    {
        $link = $this->link->link($code);
        return response()->json($link);
    }


    public function store(LinkRequest $request)
    {


        try {
            // Begin transaction
            DB::beginTransaction();

            $data = $request->validated();
            $user = User::find($data['user_id']);

            $latestOrder = Order::latest()->first();
            $orderNumber = str_pad($latestOrder ? $latestOrder->id : 0 + 1, 8, "0", STR_PAD_LEFT);


            $order = Order::create([
                'code' => $orderNumber,
                'amount' => $data['amount'],
                'sub_total' => $data['amount'],
                'name' =>  $user->name ?? null,
                'email' =>  $user->email ?? null,
                'phone' =>  $user->phone ?? null,
                'payment_type' => $data['payment_type'] ?? '',
                'user_id' => $user->id ??  null,
                'status' => Status::PENDING->value,
            ]);

            $orderProject = OrderProject::create([
                'order_id'   => $order->id,
                'project_id' => $data['project_id'],
                'qty'     => 1,
                'price'   =>  $data['amount'],
                'name'    =>  null,
                'email'   =>  null,
            ]);


            

            // Commit transaction
            DB::commit();
            return $this->payment->makePayment([
                'customer_name' => $user->name ?? 'New Customer',
                'amount' =>  $data['amount'],
                'customer_email' => $user->email ?? 'email@example.com',
                'customer_phone' => isset($user->phone) ?   substr($user->phone, 0, 11) : '11111111111',
                'language' => config('app.local'),
                'order_id' => $orderNumber,
                'payment_type' => $data['payment_type'] ?? '',
            ]);
        } catch (\Exception $e) {
            // Rollback transaction if an exception occurs
            DB::rollback();
            return response()->json(['error' => 'Failed to create payment.'], 500);
        }
    }

}
