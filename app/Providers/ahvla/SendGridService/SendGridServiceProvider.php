<?php

namespace ahvla\SendGridService;

use Illuminate\Support\ServiceProvider;

class SendGridServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['SendGrid'] = $this->app->share(function($app) {
                    return new SendGrid;
                });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array("SendGrid");
    }
}
?>