<?php

namespace App\Taxes;

class Detector
{
    protected $maxRange;

    public function __construct(int $maxRange)
    {
        $this->maxRange = $maxRange;
    }

    public function detect(float $value): bool
    {

        if ($value > $this->maxRange) {
            return true;
        } else {
            return false;
        }
    }
}