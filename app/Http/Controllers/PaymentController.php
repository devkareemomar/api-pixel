<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Gift;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderProject;
use App\Models\PaymentGateway;
use App\Models\Project;
use App\Support\Payment\Enum\Status;
use App\Support\Payment\Gateways\MyFatoorahPayment;
use App\Support\Payment\Gateways\TapPayment;
use App\Support\Payment\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
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

    public function create(PaymentRequest $request)
    {

        $data = $request->validated();
        $user = User::find($request->user_id);
        // Create Order
        $latestOrder = Order::latest()->first();
        $orderNumber = str_pad($latestOrder ? $latestOrder->id : 0 + 1, 8, "0", STR_PAD_LEFT);

        $cartCollection = collect($data['cart']);

        $amount = $cartCollection->sum('amount');


        $order = Order::create([
            'code' => $orderNumber,
            'amount' => $amount,
            'sub_total' => $amount,
            'user_id' => $user->id ??  null,
            'status' => Status::PENDING->value,
        ]);

        foreach ($data['cart'] as $item) {
            OrderProject::create([
                'order_id' => $order->id,
                'project_id' => $item['project_id'],
                'qty' => 1,
                'price' => $item['amount'],
            ]);

            $project = Project::find($item['project_id']);

            if($order->email && $project->is_gift && $order->user_id) {
                Gift::create([
                    'user_id' => $order->user_id,
                    'project_id' => $item['project_id'],
                    'sender_name' => $order->user?->name,
                    'sender_email' => $order->user?->email,
                    'recipient_name' => $order->name,
                    'recipient_email' => $order->email,
                    'price' => $item['amount']
                ]);
            }
        }
        return $this->payment->makePayment([
            'customer_name' => $user->name ?? 'New Customer',
            'amount' => $amount,
            'customer_email' => $user->email ?? 'email@example.com',
            'customer_phone' => $user->phone ?? '',
            'language' => config('app.local'),
            'order_id' => $orderNumber,
            'payment_type' => $data['payment_type'] ?? '',
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'ref_id' => 'required|exists:orders,code',
        ]);

        $order = Order::where(['code' => $request->input('ref_id')])->first();

        if ($order && $order->status == Status::COMPLETED->value) {
            return response()->json(['is_success' => true]);
        }

        if ($order) {
            return response()->json(['is_success' => false]);
        }

        return response()->json(['is_success' => false]);
    }

    /**
     * This handle tape callback
     *
     * @param Request $request
     * @return mixed
     */
    public function callbackVerify(Request $request)
    {
        return $this->payment->getPaymentObj()->verify(['tap_id' => $request->get('tap_id')]);
    }


    /**
     * Get MyFatoorah Payment Information
     * Provide the callback method with the paymentId
     *
     * @return Response
     */
    public function callback() {
        $MyFatoorahPayment = new MyFatoorahPayment();
        $MyFatoorahPayment->afterMakePayment();
        return redirect(config('app.dashboard'));
    }

}
