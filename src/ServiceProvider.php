<?php

namespace Ambiene\Sluggable;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . "/../config/sluggable.php" => config_path(
                    "sluggable.php"
                ),
            ],
            "config"
        );
    }
}
