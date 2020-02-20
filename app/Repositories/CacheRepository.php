<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;

class CacheRepository
{

    public function isCacheDataPresent($key){
        return Cache::has($key);
    }

    public function getCacheData($key){
        return Cache::get($key);
    }

    public function setCacheData($key, $data, $timelimit){
        Cache::put($key, $data, $timelimit);
    }

}
