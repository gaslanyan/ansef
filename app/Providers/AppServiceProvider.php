<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
            $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('greater_than_field', function ($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value >= $min_value;
        });

        Validator::replacer('greater_than_field', function ($message, $attribute, $rule, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            return str_replace(':field', $parameters[0], 'Amount $' . $data['amount'] . ' is too small.');
        });

        Validator::extend('less_than_field', function ($attribute, $value, $parameters, $validator) {
            $max_field = $parameters[0];
            $data = $validator->getData();
            $max_value = $data[$max_field];
            return $value <= $max_value;
        });

        Validator::replacer('less_than_field', function ($message, $attribute, $rule, $parameters, $validator) {
            $max_field = $parameters[0];
            $data = $validator->getData();
            return str_replace(':field', $parameters[0], 'Amount $' . $data['amount'] . ' is too large.');
        });

    }
}
