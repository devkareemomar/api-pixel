<?php

namespace App\Support\Payment\Traits;

use App\Support\Payment\PaymentHelper;
use Exception;
use Illuminate\Support\Facades\Log;

trait PaymentErrorTrait
{
    protected ?string $errorMessage = null;

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $message = null): void
    {
        $this->errorMessage = $message;
    }

    protected function setErrorMessageAndLogging(Exception $exception, int $case): void
    {
        try {
            $error = [];
            $this->errorMessage = $exception->getMessage();
        } catch (Exception $exception) {
            Log::error(
                'Failed to make a payment charge.',
                PaymentHelper::formatLog([
                    'catch_case' => $case,
                    'error_message' => $exception->getMessage(),
                ], __LINE__, __FUNCTION__, __CLASS__)
            );
        }
    }
}
