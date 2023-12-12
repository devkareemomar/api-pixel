<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expiration date from another endpoint';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get(
            env('SUPER_ADMIN_URL')
            .'/api/check-expiration/'
            . env('DOMAIN_NAME')
        );

        if ($response->successful()) {
            $expirationDate = $response->json()['expiration_date'];
            $isExpired = $expirationDate < now()->toDateString();
            Setting::first()->update(['is_expired' => $isExpired]);
        }
    }
}
