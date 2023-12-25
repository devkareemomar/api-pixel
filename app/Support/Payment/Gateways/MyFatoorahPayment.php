<?php

namespace App\Support\Payment\Gateways;

use App\Support\Payment\Interfaces\ProducePaymentInterface;
use App\Support\Payment\Traits\PaymentErrorTrait;
use Exception;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class MyFatoorahPayment implements ProducePaymentInterface
{

    use PaymentErrorTrait;

    protected PaymentMyfatoorahApiV2 $paymentObj;

    public function __construct()
    {
        $this->paymentObj = new PaymentMyfatoorahApiV2(config('myfatoorah.api_key'), config('myfatoorah.country_iso'), config('myfatoorah.test_mode'));
    }


    public function execute(array $data)
    {
        $paymentType = isset($data['payment_type']) ? $data['payment_type'] : '';

        try {

            $callbackURL = route('myfatoorah.callback');
            // $callbackURL = route('payment.myfatoorah.callback');

            $data = [
                'CustomerName' => $data['customer_name'],
                'InvoiceValue' => $data['amount'],
                'DisplayCurrencyIso' => 'KWD',
                'CustomerEmail' => $data['customer_email'],
                'CallBackUrl' => $callbackURL,
                'ErrorUrl' => $callbackURL,
                'MobileCountryCode' => '+965',
                'CustomerMobile' => $data['customer_phone'],
                'Language' => $data['language'] ?? 'en',
                'CustomerReference' => $data['order_id'],
                'SourceInfo' => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' .
                    MYFATOORAH_LARAVEL_PACKAGE_VERSION,
            ];

            return $this->makePayment($data, $paymentType);

        } catch (Exception $e) {
            $this->setErrorMessageAndLogging($e, 1);
            return false;
        }
    }

    public function makePayment(array $data, $paymentType = '')
    {
            switch ($paymentType) {
                case 'knet':
                    $paymentMethodId = 1;
                    break;
                case 'visa':
                    $paymentMethodId = 2;
                    break;
                default:
                    $paymentMethodId = 0;
                    break;
            }

        try {
             // 0 for MyFatoorah invoice or 1 for Knet in test mode
            $data = $this->paymentObj->getInvoiceURL($data, $paymentMethodId);
            $response = ['is_success' => 'true', 'Message' => 'Invoice created successfully.', 'data' => [
                'invoice_url' => $data['invoiceURL'],
                'invoice_id' => $data['invoiceId'],
            ]];
        } catch (\Exception $e) {
            $this->setErrorMessageAndLogging($e, 1);
            $response = ['IsSuccess' => 'false', 'Message' => $e->getMessage()];
        }
        return $response;
    }

    public function afterMakePayment()
    {
        try {
            $paymentId = request('paymentId');
            $data = $this->paymentObj->getPaymentStatus($paymentId, 'PaymentId');

            if ($data->InvoiceStatus == 'Paid') {
                $msg = 'Invoice is paid.';
            } else if ($data->InvoiceStatus == 'Failed') {
                $msg = 'Invoice is not paid due to ' . $data->InvoiceError;
            } else if ($data->InvoiceStatus == 'Expired') {
                $msg = 'Invoice is expired.';
            }

            $response = ['IsSuccess' => 'true', 'Message' => $msg, 'Data' => $data];
        } catch (\Exception $e) {
            $response = ['IsSuccess' => 'false', 'Message' => $e->getMessage()];
        }
        return response()->json($response);
    }
}
