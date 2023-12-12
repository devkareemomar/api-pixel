<?php


namespace App\Traits;

use App\Models\Visit;
use App\Services\IPInfo;

trait Visitable
{
    public function visits()
    {
        return Visit::where([
            'visitable_id' => $this->id,
            'visitable_type' => get_class($this),
        ])->get();
    }

    public function visit($userId = null)
    {

        $ipInfo = geoip(request()->ip());


        return Visit::updateOrCreate([
            'visitable_id' => $this->id,
            'visitable_type' => get_class($this),
            'user_id' => $userId ?: auth()->user()?->id,
            'ip' => request()->ip(),
        ],
            [
                'updated_at' => now(),
                'country' => $ipInfo?->country ?? null,
                'country_code' => $ipInfo?->iso_code ?? null
            ]);
    }
}
