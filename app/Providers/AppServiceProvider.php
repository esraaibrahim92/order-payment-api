<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;
use App\Infrastructure\PaymentGateways\CreditCardGateway;
use App\Infrastructure\Persistence\Eloquent\OrderRepository;
use App\Domain\Payment\Repositories\PaymentRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\PaymentRepository;
use App\Infrastructure\PaymentGateways\PaypalGateway;
use App\Application\Payment\Gateway\PaymentGatewayRegistry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );

        $this->app->singleton(PaymentGatewayRegistry::class, function ($app) {
            return new PaymentGatewayRegistry([
                $app->make(CreditCardGateway::class),
                $app->make(PaypalGateway::class),
                // add new gateways here
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
