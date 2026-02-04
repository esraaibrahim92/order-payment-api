<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;
use App\Infrastructure\PaymentGateways\CreditCardGateway;
use App\Infrastructure\Persistence\Eloquent\OrderRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
            $this->app->bind(
                PaymentGatewayInterface::class,
                CreditCardGateway::class
            );

            $this->app->bind(
                OrderRepositoryInterface::class,
                OrderRepository::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
