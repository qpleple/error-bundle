<?php
namespace Short\ErrorBundle;

use Short\ErrorBundle\Entity\Error;

class ErrorManager
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
    
    public function log($exc, $notify = true)
    {
        // Log only in prod
        if ($this->container->getParameter('kernel.environment') != 'prod') {
            return;
        }

        // Build an object Error from the exception and current context (user, referer, ...)
        $error = $this->buildError($exc);
        
        // Persist it in the database
        $em = $this->container->get('doctrine.orm.entity_manager');
        if ($em->isOpen()) {
            $em->persist($error);
            $em->flush();
        }

        // Send an email if needed
        if ($notify && $error->getCode() != 404 && $error->getCode() != 403) {
            $this->issueGithub($error);
            $this->email($error);
        }
    }
    
    // Méthode appelée automatique lors d'une exception du kernel (voir services.xml)
    public function onKernelException($exc)
    {
        $this->log($exc, true);
    }

    // ------------------------------------------------------------------------
    // Private methods of the service
    // ------------------------------------------------------------------------
    
    protected function buildError($exc)
    {
        $error = new Error();
        $code  = method_exists($exc, 'getStatusCode') ? $exc->getStatusCode() : 0;
        $error->setCode($code);
        $error->setClass(get_class($exc));
        $error->setMessage($exc->getMessage());
        $error->setTrace($code != 404 ? $exc->getTraceAsString() : '');
        
        $trace = $exc->getTrace();
        foreach ($trace as $row) {
            if (array_key_exists('file', $row) && preg_match("|/src/Short/|", $row['file']) === 1) {
                $error->setWhereClass(array_key_exists('class', $row) ? $row['class'] : '');
                $error->setWhereFunction(array_key_exists('function', $row) ? $row['function'] : '');
            }
        }

        // Sometimes, there is no request and no user (cron command for instance)
        try {
            $request = $this->container->get('request');
            $error->setIp($request->getClientIp());
            $error->setAgent($request->server->get('HTTP_USER_AGENT'));
            $error->setReferer($request->server->get('HTTP_REFERER'));
            $error->setUrl($request->getUri());

            $context = $this->container->get('security.context');
            if ($context->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $error->setUser($context->getToken()->getUser());
            }
        } catch (\Exception $e) { }
        
        return $error;
    }
    
    protected function email(Error $error)
    {
        $subject = sprintf('[short%s] %s', $error->getCode() ? ' ' . $error->getCode() : '', $error);
        $msg     = $this->container->get('twig')->render('ShortErrorBundle::email.html.twig', array('error' => $error));
        
        $mailingList = Error::className($error->getClass()) === 'MobileException' ? 'short.addresses_mobile_contact' : 'short.addresses_system_contact';

        // Create the message
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('short.edition@gmail.com', 'shortEdition')
            ->setTo($this->container->getParameter($mailingList))
            ->addPart($msg, 'text/html')
        ;       

        $this->container->get('mailer')->send($message);
    }

    public function issueGithub(Error $error)
    {
        $github = $this->container->get('short.github');

        $msg = $this->container->get('twig')->render('ShortErrorBundle::issue_github.html.twig', array('error' => $error));
        $github->createIssue($error->__toString(), $msg);
    }
}