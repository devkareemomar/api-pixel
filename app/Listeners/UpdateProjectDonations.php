<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
            $project->update([
                'total_earned' => abs($project->total_earned + $orderProject->price),
            ]);
        }
    }

    private function calculateDonation($cart, $paymentAmount)
    {
    }
}
