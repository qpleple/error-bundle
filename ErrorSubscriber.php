<?php
namespace Short\ErrorBundle;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


class ErrorSubscriber implements EventSubscriberInterface
{    
    // ------------------------------------------------------------------------
    // Parameters injected by dependency injection (in services.xml)
    // ------------------------------------------------------------------------

    protected $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    // ------------------------------------------------------------------------
    // Public methods of the service
    // ------------------------------------------------------------------------

    static public function getSubscribedEvents()
    {
        return array(
            'kernel.exception' => 'onKernelException',
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exc = $event->getException();
        
        if (!$this->container) {
            // If the container has not been injected. It may be because
            // a test is throwing an exception and there is no container
            // in tests
            return;
        }

        $this->container->get('short.error_manager')->onKernelException($exc);
    }
}
