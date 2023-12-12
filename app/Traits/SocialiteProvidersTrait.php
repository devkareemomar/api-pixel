<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait SocialiteProvidersTrait
{
    protected function generateTempId($depth = 40)
    {
        return Str::random($depth);
    }

    protected function cacheStatePutKeyInUrl($url = null, $state = null)
    {
        $tempId = $this->generateTempId();
        $this->tempStoreStateInCache($tempId, $state);

        return $url.'&state='.$tempId;
    }

    protected function tempStoreStateInCache($tempId, $state, $seconds = 60)
    {
        Cache::put($tempId, $state, $seconds);
    }
}
