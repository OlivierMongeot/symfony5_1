<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface {

    protected $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
        

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendEmailSuccess'
        ];
    }


    public function sendEmailSuccess(PurchaseSuccessEvent $event)
    {
        // dd($event);
        $this->logger->info('Purchase success email sent for order #'.$event->getPurchase()->getId());

    }

    
}