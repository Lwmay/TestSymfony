<?php
namespace App\Test\EventSubscriber;

use App\EventSubscriber\ExceptionSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Request;

class ExceptionSubscriberTest extends TestCase {

    /**
     * Es ce que le système d'abonne aux bons évènements
     * On vérifie que la méthode contient bien la clé de l'évènement
     */
    public function testEventSubscription () {
        $this->assertArrayHasKey(ExceptionEvent::class, ExceptionSubscriber::getSubscribedEvents());
    }

    /**
     * On test la fonction onException qui va envopyer un email avec les infos de l'événement
     */
    public function testOnExceptionSendEmail () {

        /**
         * On crée un mock pour le mailer
         */
        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * On construit la classe exception subscriber
         */
        $subcriber = new ExceptionSubscriber($mailer, 'from@domain.fr', 'to@domain.fr');

        /**
         * La method onException() prends en premier paramtère un ExceptionEvent.
         * On peut construire cet event en passant en paramètre un kernelInterface.
         * On crée d'aboard un mock de cette kernel interface
         */
        $kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $event = new ExceptionEvent($kernel, new Request(), 1, new \Exception());

        /**
         * On vérifie qu'un email a bien été envoyé
         */
        $mailer->expects($this->once())->method('send');

        /**
         * On test la subscribe
         */
        $subcriber->onException($event);
    }

}