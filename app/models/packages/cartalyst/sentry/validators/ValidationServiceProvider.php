<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 22/03/15
 * Time: 22:07
 */

namespace packages\cartalyst\sentry\validators;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider {

    public function register(){}

    public function boot()
    {
        $this->app->validator->resolver(function($translator, $data, $rules, $messages)
        {
            return new UserValidator($translator, $data, $rules, $messages);
        });
    }
}