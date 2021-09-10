<?php

namespace App\Taxes;

use Psr\Log\LoggerInterface;

class Calculator
{
    protected $vat;
    protected $logger;

    public function __construct(LoggerInterface $logger, float $vat)
    {
        $this->logger = $logger;
        $this->vat = $vat;
    }
    public function calculateTax($price)
    {
        $this->logger->info("Calculating tax for $price");
        $tax = $price * $this->vat / 100;
        return $tax;
    }
}