<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentimesTransformer implements DataTransformerInterface{

    public function transform($value){

        return $value !== null ? $value / 100 : null;

    }

    public function reverseTransform($value){

         return null !== $value ? $value * 100 : null;
    }
             



}