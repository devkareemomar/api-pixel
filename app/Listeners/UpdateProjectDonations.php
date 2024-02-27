<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Gift;
use App\Mail\GiftMail;
use Illuminate\Support\Facades\Mail;

class UpdateProjectDonations
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentCompleted $event): void
    {
        $order = $event->order;
        foreach ($order->orderProjects as $orderProject) {
            $project =Project::where('id', $orderProject->project_id)->first();
            if($project){
                $project->update([
                    'total_earned' => abs($project->total_earned + $orderProject->price),
                ]);

                if($orderProject->is_gift){
                    $gift = Gift::where('order_project_id', $orderProject->id)->first();
                    Mail::to($gift->recipient_email)
                    ->send(new GiftMail($gift));
                }
            }


        }
    }

    private function calculateDonation($cart, $paymentAmount)
    {
    }
}
