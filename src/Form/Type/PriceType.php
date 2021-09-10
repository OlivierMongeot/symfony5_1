<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PriceType extends AbstractType{


    public function getParent(){
        return NumberType::class;
    }

}