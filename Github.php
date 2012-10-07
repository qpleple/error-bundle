<?php
namespace Short\ErrorBundle;

use Github\Client;

class Github
{    
    // ------------------------------------------------------------------------
    // Parameters injected by dependency injection (in services.xml)
    // ------------------------------------------------------------------------

    protected $container;
    protected $client;

    protected $repoName;
    protected $repoOwner;
    
    public function __construct($container, $user, $password, $repoOwner, $repoName)
    {
        $this->container = $container;
        $this->repoName  = $repoName;
        $this->repoOwner = $repoOwner;

        $this->client = new Client();

        $this->client->authenticate($user, $password, Client::AUTH_HTTP_PASSWORD);

    }
    
    // ------------------------------------------------------------------------
    // Public Methods of the service
    // ------------------------------------------------------------------------
    
    public function findIssuesBy($options)
    {
        return $this->client->api('issue')->all($this->repoOwner, $this->repoName, $options);
    }

    /**
     * Créé une nouvelle Issue dans Github
     * 
     * @var array $options. Exemple :
     *   array(
     *     'title' => 'The issue title',
     *     'body'   => 'The issue body',
     *   )
     * 
     * @return string JSON réponse
     */
    public function createIssue($title, $body)
    {
        $this->client->api('issue')->create($this->repoOwner, $this->repoName, array(
            'title' => $title,
            'body'  => $body,
        ));
    }
}
