<?php


namespace App\EventSubscriber;


use App\Kernel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $maintenance;

    public function __construct(Environment $twig, bool $maintenance)
    {
        $this->twig = $twig;
        $this->maintenance = $maintenance;
    }


    public function methodCallOnKernelResponseEvent(FilterResponseEvent $event)
    {
        if($this->maintenance) {
            $view = $this->twig->render('maintenance/maintenance.html.twig');
            $response = $event->getResponse();
            $response->setContent($view);
            $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['methodCallOnKernelResponseEvent', -200]
        ];
    }

}
