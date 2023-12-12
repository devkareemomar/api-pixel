<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDonationResource;
use App\Models\OrderProject;

class DonationController extends Controller
{
    public function index()
    {
//        $donations = Donation::paginate();
//        return DonationResource::collection($donations);


        $donates = OrderProject::with('project')->whereHas('order', function ($query) {
            $query->where(['user_id' => auth('sanctum')->id(), 'status' => 'completed'])
                ->when(request()->input('year'), function ($query) {
                    $query->whereYear('created_at', request()->input('year'));
                });
        })->get();

        return UserDonationResource::collection($donates);
    }
}
