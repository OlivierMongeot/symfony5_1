<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{

    // /**
    //  * @Route("/", name="index")
    //  */
    // public function index()
    // {
    //     dd('hello world');
    // }


    /**
     * @Route("/test/{age<\d>?0}", name="test", methods={"GET","POST"})
     */
    public function test($age)
    {
        dd("hello world TEST $age");
    }


    
}