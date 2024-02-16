<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\PeriodicallyDonate;
use App\Http\Controllers\PaymentController;

class RepeatDonation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:repeat-donation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'repeat donation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Carbon::now();

        $periodicallyDonates = PeriodicallyDonate::where('recurring_start_date', '<=', $currentDate)
        ->where('recurring_end_date', '>=', $currentDate)
        ->where(function ($query) use ($currentDate) {
            $query->where(function ($q) use ($currentDate) {
                $q->where('recurring', 'daily');
            })
            ->orWhere(function ($q) use ($currentDate) {
                $q->where('recurring', 'monthly')
                ->whereDay('recurring_start_date', $currentDate->day);
            })
            ->orWhere(function ($q) use ($currentDate) {
                $q->where('recurring', 'weekly')
                ->whereRaw("DAYOFWEEK(recurring_start_date) = ?", [$currentDate->dayOfWeek]);
            });
        })
        ->get();

        foreach($periodicallyDonates as $donate){
            $payment =  (new PaymentController)->createPaymentForPeriodicallyDonate($donate);
            dd($payment['data']['invoice_url']);
        }


    dd($periodicallyDonates);

        Log::info('Check for repeat donations command executed successfully.');

        $this->info('Check Repeat Donation successfully!');

    }




}
