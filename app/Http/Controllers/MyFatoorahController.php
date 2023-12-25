<?php

namespace App\Http\Controllers;

use App\Events\PaymentCompleted;
use App\Models\Order;
use App\Models\Payment;
use App\Support\Payment\Enum\Status;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class MyFatoorahController extends Controller
{

    public $mfObj;

//-----------------------------------------------------------------------------------------------------------------------------------------
    public function __construct()
    {
        $this->mfObj = new PaymentMyfatoorahApiV2(config('myfatoorah.api_key'), config('myfatoorah.country_iso'), config('myfatoorah.test_mode'));
    }


//-----------------------------------------------------------------------------------------------------------------------------------------


    public function index()
    {

    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     *
     * @param int|string $orderId
     * @return array
     */
    private function getPayLoadData($orderId = null)
    {
        $callbackURL = route('myfatoorah.callback');

        return [
            'CustomerName' => 'FName LName',
            'InvoiceValue' => '10',
            'DisplayCurrencyIso' => 'KWD',
            'CustomerEmail' => 'test@test.com',
            'CallBackUrl' => $callbackURL,
            'ErrorUrl' => $callbackURL,
            'MobileCountryCode' => '+965',
            'CustomerMobile' => '12345678',
            'Language' => 'en',
            'CustomerReference' => $orderId,
            'SourceInfo' => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------


    public function callback()
    {
        try {
            $paymentId = request('paymentId');
            $data = $this->mfObj->getPaymentStatus($paymentId, 'PaymentId');

            if ($data->InvoiceStatus == 'Paid') {
                $msg = 'Invoice is paid.';
            } else if ($data->InvoiceStatus == 'Failed') {
                $msg = 'Invoice is not paid due to ' . $data->InvoiceError;
            } else if ($data->InvoiceStatus == 'Expired') {
                $msg = 'Invoice is expired.';
            }

            $response = ['isSuccess' => 'true', 'message' => $msg, 'data' => collect($data)->toArray()];
            $order = Order::where('code', $response['data']['CustomerReference'])->first();


            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->user_id = $order->user_id;
            $payment->currency = $response['data']['focusTransaction']->Currency;
            $payment->charge_id = $response['data']['focusTransaction']->ReferenceId;
            $payment->amount = $response['data']['focusTransaction']->DueValue;
            $payment->metadata = collect($response['data'])->toJson();
            $payment->payment_method = 'MyFatoorah';
            if ($response['data']['InvoiceStatus'] == 'Paid') {
                $order->status = Status::COMPLETED->value;
                $order->save();
                $payment->status = Status::COMPLETED->value;
                PaymentCompleted::dispatch($order);

            }

            if ($response['data']['InvoiceStatus'] === 'Failed') {
                $order->status = Status::FAILEd->value;
                $payment->status = Status::FAILEd->value;

            }


            $order->save();
            $payment->save();

            return redirect((env('FRONTEND_URL') . '?ref_id=' . $order->code));


        } catch (\Exception $e) {
            return ['isSuccess' => 'false', 'message' => $e->getMessage()];
        }

    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
