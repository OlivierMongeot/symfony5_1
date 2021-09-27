<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }


    public static function getSubscribedEvents(){
        return [
            'product.view' => 'makeLogOnDisplay'
        ];
    }

    public function makeLogOnDisplay(ProductViewEvent $event){
        // dump('ProductViewSubscriber succes for '.$event->getProduct()->getName());
        $this->logger->info('Display success for '.$event->getProduct()->getName());
    }

}

