<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface {

    protected $logger;
    protected $mailer;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security) {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }
        

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendEmailSuccess'
        ];
    }


    public function sendEmailSuccess(PurchaseSuccessEvent $event)
    {
        
        /**
         * @var User
         */
        $user = $this->security->getUser();

        //Purchase
        $purchase = $event->getPurchase();

        //Ecrire Mail
        $email = new TemplatedEmail();
        $email
        ->from('commande@bushcraft.com')
        ->to(new Address($user->getEmail(), $user->getUsername()))
        ->subject('Votre achat a bien été effectué')
        ->htmlTemplate('emails/purchase_success.html.twig')
        ->context([
            'purchase' => $purchase,
            'user' => $user
        ]);

        //Envoyer Mail
        $this->mailer->send($email);

        $this->logger->info('Purchase success email sent for order #'.$event->getPurchase()->getId());

    }

    
}