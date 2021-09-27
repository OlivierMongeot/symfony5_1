<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer){
        $this->logger = $logger;
        $this->mailer = $mailer;
    }


    public static function getSubscribedEvents(){
        return [
            'product.view' => 'mailOnDisplay'
        ];
    }

    public function mailOnDisplay(ProductViewEvent $event){
        // $email = (new Email());
        // $email->from(new Address('olivier@gmail.com',"Info Boutique"));
        // $email->to('olivier@gmail.com');
        // $email->subject('Product view Visit');
        // $email->text('New product view Text Main for '. $event->getProduct()->getName())
        // ->html('<h2>See Twig integration for better HTML integration!</h2>');
        // $this->mailer->send($email);

        $this->logger->info('Display success for '.$event->getProduct()->getName());

    }

}

