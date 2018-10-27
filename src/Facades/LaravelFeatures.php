<?php

namespace AlexGodbehere\LaravelFeatures\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelFeatures extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelfeatures';
    }
}
