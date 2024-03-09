<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\GiftRequest;
use App\Models\Gift;
use App\Models\GiftTemplate;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GiftTemplateResource;
use Illuminate\Support\Facades\DB;
use App\Support\Payment\Gateways\MyFatoorahPayment;
use App\Support\Payment\Gateways\TapPayment;
use App\Support\Payment\Payment;
use App\Models\PaymentGateway;
use App\Models\OrderProject;
use App\Models\Order;
use App\Support\Payment\Enum\Status;


class GiftController extends BaseApiController
{

    protected Payment $payment;

    public function __construct()
    {
        $currentActivePayment = PaymentGateway::where('status', true)->first();
        $payment = ['tap' => TapPayment::class, 'myfatorah' => MyFatoorahPayment::class];

        if ($currentActivePayment) {
            $this->payment = new Payment((new $payment[strtolower($currentActivePayment->name)]()));
        } else {
            $this->payment = new Payment((new TapPayment()));
        }
    }
    public function index()
    {
        return $this->return_success(__('Gifts retrieved successfully'), Gift::filter()->get());
    }

    public function store(GiftRequest $request)
    {


        try {
            // Begin transaction
            DB::beginTransaction();

            $data = $request->validated();

            $latestOrder = Order::latest()->first();
            $orderNumber = str_pad($latestOrder ? $latestOrder->id : 0 + 1, 8, "0", STR_PAD_LEFT);


            $order = Order::create([
                'code' => $orderNumber,
                'user_id' => $data['user_id'] ?? null,
                'amount' => $data['price'],
                'sub_total' => $data['price'],
                'name' =>  $data['sender_name'] ?? null,
                'email' =>  $data['sender_email'] ?? null,
                'payment_type' => $data['payment_type'] ?? null,
                'status' => Status::PENDING->value,
            ]);

            $orderProject = OrderProject::create([
                'order_id'   => $order->id,
                'project_id' => $data['project_id'],
                'qty'     => 1,
                'price'   =>  $data['price'],
                'is_gift' =>  1,
                'name'    => $data['sender_name'] ?? null,
                'email'   =>  $data['sender_email'] ?? null,
            ]);


            $gift = Gift::create([
                'project_id' => $data['project_id'],
                'template_id' => $data['template_id'],
                'sender_name' => $data['sender_name'],
                'sender_email' => $data['sender_email'],
                'recipient_name' => $data['recipient_name'],
                'recipient_email' => $data['recipient_email'],
                'price' => $data['price'],
                'order_project_id' => $orderProject->id,
            ]);

            // Commit transaction
            DB::commit();
            return $this->payment->makePayment([
                'customer_name' => $data['sender_name'] ?? 'New Customer',
                'amount' => $data['price'],
                'customer_email' => $data['sender_email'] ?? 'email@example.com',
                'customer_phone' =>  '11111111111',
                'language' => config('app.local'),
                'order_id' => $orderNumber,
                'payment_type' => $data['payment_type'] ?? '',
            ]);
        } catch (\Exception $e) {
            // Rollback transaction if an exception occurs
            DB::rollback();
            return response()->json(['error' => 'Failed to create gift.'], 500);
        }
    }


    public function  gifts_templates()
    {

        $templates  =  GiftTemplate::get();
        return GiftTemplateResource::collection($templates);
    }
}
