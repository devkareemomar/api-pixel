<?php

namespace App\Support\Payment\Gateways;

use App\Events\PaymentCompleted;
use App\Models\Order;
use App\Models\Payment;
use App\Support\Payment\Enum\Status;
use App\Support\Payment\Interfaces\ProducePaymentInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class TapPayment implements ProducePaymentInterface
{

    public function execute(array $data)
    {
        return $this->makePayment($data);
    }

    public function makePayment(array $data)
    {
        $unique_id = uniqid();
        $paymentType = $data['payment_type'] == 'knet' ? 'src_kw.knet' : 'src_card';

        $response = Http::withHeaders([
            "authorization" => "Bearer " . env('TAP_SECRET_KEY'),
            "Content-Type" => "application/json",
            'lang_code' => $data['language'],
        ])->post('https://api.tap.company/v2/charges', [
            "amount" => $data['amount'],
            "currency" => 'KWD',
            "threeDSecure" => true,
            "save_card" => false,
            "description" => "Cerdit",
            "statement_descriptor" => "Cerdit",
            "reference" => [
                "transaction" => $data['order_id'],
                "order" => $data['order_id']
            ],
            "receipt" => [
                "email" => true,
                "sms" => true
            ], "customer" => [
                "first_name" => $data['customer_name'],
                "middle_name" => "",
                "last_name" => $data['customer_name'],
                "email" => $data['customer_email'],
                "phone" => [
                    "country_code" => "20",
                    "number" => $data['customer_phone']
                ]
            ],
            "source" => ["id" => $paymentType],
            "post" => ["url" => route('payment.verify', ['payment' => "tap"])],
            "redirect" => ["url" => route('payment.verify', ['payment' => "tap"])]
        ])->json();

        try {
            return [
                'is_success' => true,
                'data' => [
                    'invoice_id' => $response['id'],
                    'invoice_url' => $response['transaction']['url'],
                    'process_data' => $response,
                    'html' => ""
                ],
            ];
        } catch (\Throwable $th) {
            return $response;
        }
    }

    public function verify(array $data)
    {
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . env('TAP_SECRET_KEY'),
                "Content-Type" => "application/json",
            ])->get('https://api.tap.company/v2/charges/' . $data['tap_id'])->json();

            $order = Order::where('code', $response['reference']['order'])->with('orderProjects')->first();

            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->user_id = $order->user_id;
            $payment->currency = $response['currency'];
            $payment->charge_id = $response['receipt']['id'];
            $payment->amount = $response['amount'];
            $payment->metadata = collect($response)->toJson();
            $payment->payment_method = 'Tap';

            if (isset($response['status']) && $response['status'] == "CAPTURED") {

                $order->status = Status::COMPLETED->value;
                $order->save();

                $payment->status = Status::COMPLETED->value;
                $payment->save();

                PaymentCompleted::dispatch($order);

            } else {
                $order->status = Status::FAILEd->value;
                $order->save();

                $payment->status = Status::FAILEd->value;
                $payment->save();
            }
            return redirect((env('FRONTEND_URL') . '?ref_id=' . $order->code));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
