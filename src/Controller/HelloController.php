<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloController extends AbstractController
{
    protected $logger;
    protected $calculator;
    protected $twig;

    public function __construct(LoggerInterface $logger, Calculator $calculator, Environment $twig)
    {
        $this->logger = $logger;
        $this->calculator = $calculator;
        $this->twig = $twig;
    }


    /**
     * @Route("/hello/{name?World}", name="hello")
     */
    public function hello($name)
    {
        return $this->render('hello.html.twig', [
            'name' => $name,
            'formateur1' => ['prenom' => 'Lior', 'nom' => 'Chamla'],
            'formateur2' => ['prenom' => 'Olivier', 'nom' => 'Mongeot']
        ]);
    }

 
}