<?php

namespace App\Application\Payment\Gateway;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;

class PaymentGatewayRegistry
{
    private array $gateways = [];

    public function __construct(iterable $gateways)
    {
        foreach ($gateways as $gateway) {
            $this->gateways[$gateway->method()] = $gateway;
        }
    }

    public function get(string $method): PaymentGatewayInterface
    {
        if (! isset($this->gateways[$method])) {
            throw new InvalidArgumentException("Unsupported payment method: {$method}");
        }
        return $this->gateways[$method];
    }

    public function supportedMethods(): array
    {
        return array_keys($this->gateways);
    }
}
