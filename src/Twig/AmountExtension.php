<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;


class AmountExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amountFilter'])
        ];
    }


    public function amountFilter($number, $symbol = '€', $decPoint = ',', $thousandsSep = ' ')
    {
        $number = $number / 100;
        $finalValue = number_format($number, 2, $decPoint, $thousandsSep);
        return $finalValue . ' ' . $symbol;
    }
}
