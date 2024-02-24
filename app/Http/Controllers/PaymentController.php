<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Cart;
use App\Models\Gift;
use App\Models\User;
use App\Models\Order;
use App\Models\PeriodicallyDonate;
use App\Models\OrderProject;
use App\Models\PaymentGateway;
use App\Models\Project;
use App\Support\Payment\Enum\Status;
use App\Support\Payment\Gateways\MyFatoorahPayment;
use App\Support\Payment\Gateways\TapPayment;
use App\Support\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        try {
            // Begin transaction
            DB::beginTransaction();

            $data = $request->validated();
            $user = User::find($request->user_id);

            // Create Order
            $latestOrder = Order::latest()->first();
            $orderNumber = str_pad($latestOrder ? $latestOrder->id : 0 + 1, 8, "0", STR_PAD_LEFT);


            $cart = Cart::where('user_id', $request->user_id)
            ->orWhere('session_id', $request->user_id)
            ->with('cartProjects')
            ->first();
        
            if (!$cart || $cart->cartProjects->isEmpty()) {
                return response()->json(['message' => 'cart is empty'], 404);
            }
        
            $amount = $cart->total_amount;

            $order = Order::create([
                'code' => $orderNumber,
                'amount' => $amount,
                'sub_total' => $amount,
                'name' =>  $user->name ?? null,
                'email' =>  $user->email ?? null,
                'phone' =>  $user->phone ?? null,
                'payment_type' => $data['payment_type'] ?? '',
                'user_id' => $user->id ??  null,
                'status' => Status::PENDING->value,
            ]);

            foreach ($cart->cartProjects as $cart) {
                OrderProject::create([
                    'order_id'   => $order->id,
                    'project_id' => $cart->project_id,
                    'qty'     => 1,
                    'price'   =>  $cart->amount,
                    'name'    =>  $cart->gifted_to_name ?? null,
                    'email'   =>  $cart->gifted_to_email ?? null,
                    'phone'   =>  $cart->gifted_to_phone ?? null,
                    'comment' =>  $cart->donor_comment ??null,
                ]);

                $project = Project::find($cart->project_id);

                if(isset($cart->recurring) && isset($user->id)){
                    PeriodicallyDonate::create([
                        'project_id'           => $cart->project_id,
                        'user_id'              => $user->id,
                        'recurring'            => $cart->recurring,
                        'recurring_type'       => $cart->recurring_type,
                        'recurring_start_date' => $cart->recurring_start_date,
                        'recurring_end_date'   => $cart->recurring_end_date,
                        'amount'   => $cart->amount,    
                        'payment_type' => $data['payment_type'] ?? '',

                    ]);
                }

                if($order->email && $project->is_gift && $order->user_id) {
                    Gift::create([
                        'user_id'         => $order->user_id,
                        'project_id'      => $cart->project_id,
                        'sender_name'     => $order->user?->name,
                        'sender_email'    => $order->user?->email,
                        'recipient_name'  => $order->name,
                        'recipient_email' => $order->email,
                        'price'           => $cart->amount
                    ]);
                }
            }

            // Commit transaction
            DB::commit();

            return $this->payment->makePayment([
                'customer_name' => $user->name ?? 'New Customer',
                'amount' => $amount,
                'customer_email' => $user->email ?? 'email@example.com',
                'customer_phone' => $user->phone ?? '',
                'language' => config('app.local'),
                'order_id' => $orderNumber,
                'payment_type' => $data['payment_type'] ?? '',
            ]);
        } catch (\Exception $e) {
            // Rollback transaction if an exception occurs
            DB::rollback();
            return response()->json(['error' => 'Failed to create order.'], 500);
        }
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



    public function createPaymentForPeriodicallyDonate($donate)
    {
        try {
            // Begin transaction
            DB::beginTransaction();


            // Create Order
            $latestOrder = Order::latest()->first();
            $orderNumber = str_pad($latestOrder ? $latestOrder->id : 0 + 1, 8, "0", STR_PAD_LEFT);


            $user = $donate->user;
            $amount = $donate->amount;

            $order = Order::create([
                'code' => $orderNumber,
                'amount' => $amount,
                'sub_total' => $amount,
                'name' =>  $user->name ?? '',
                'email' =>  $user->email ?? '',
                'phone' =>  $user->phone ?? '',
                'payment_type' => $donate->payment_type ?? '',
                'user_id' => $user->id ??  null,
                'status' => Status::PENDING->value,
            ]);

            OrderProject::create([
                'order_id' => $order->id,
                'project_id' => $donate->project_id,
                'qty' => 1,
                'price' => $donate->amount,
            ]);

            // Commit transaction
            DB::commit();

            return $this->payment->makePayment([
                'customer_name' => $user->name ?? 'New Customer',
                'amount' => $amount,
                'customer_email' => $user->email ?? 'email@example.com',
                'customer_phone' => $user->phone ?? '',
                'language' => config('app.local'),
                'order_id' => $orderNumber,
                'payment_type' => $donate->payment_type ?? '',
            ]);
        } catch (\Exception $e) {
            // Rollback transaction if an exception occurs
            DB::rollback();
            return response()->json(['error' => 'Failed to create order.'], 500);
        }
    }

}
