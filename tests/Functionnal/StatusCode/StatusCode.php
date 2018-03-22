<?php
namespace Tests\Functionnal\StatusCode;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Functionnal\Traits\ClientCreator;

abstract class StatusCode extends WebTestCase
{

    // Traits
    use ClientCreator;

    /**
     * Request given URL and check response status code
     *
     * @param string $url       URL to request
     * @param array $methods    Methods for requests
     * @param int $expectStatus Expected status code
     * @param Client $client    Client for request URL
     *
     * @return Client           For additional checks
     */
    protected function checkResponseStatusCode(
        string $url,
        array $methods,
        int $expectStatus,
        Client $client
    ) {

        // loop on every method
        foreach($methods as $method){
            // Request target
            $client->request($method, $url);

            // Check status code
            $this->assertEquals(
                $expectStatus,
                $client->getResponse()->getStatusCode()
            );
        }

        // Return Client
        return $client;
    }

    /**
     * Check redirection status code and destination
     * Execute $this->checkResponseStatusCode
     *
     * @param string $requestedUrl    URL for requests
     * @param string $redirectionUrl  Attempted redirection url
     * @param array  $methods         Methods for requests
     * @param Client $client          Client for requests
     *
     * @return Client                 For additional checks
     */
    protected function checkRedirection(
        string $requestedUrl,
        string $redirectionUrl,
        array $methods,
        Client $client
    )
    {

        foreach($methods as $method) {
            // Check 302 status code at request
            $this->checkResponseStatusCode(
                $requestedUrl,
                [$method],
                302,
                $client
            );

            // Store host
            $host = $client->getRequest()->getSchemeAndHttpHost();
            // Check redirection to given url
            $this->assertTrue(
                $client->getResponse()->isRedirect($host . $redirectionUrl)
            );
        }

        return $client;
    }

    /**
     * Request route with all methods apart specified ones
     *
     * @param string $url        Url to request
     * @param array $authorized  Authorized methods
     * @param Client $client     Client for request
     */
    protected function checkForbiddenMethods(
        string $url,
        array $authorized,
        Client $client
    ) {

        // All HTTP methods
        $methods = [
            'GET',
            'POST',
            'DELETE',
            'PUT',
            'PATCH',
            'COPY',
            'OPTIONS',
            'LINK',
            'UNLINK',
            'PURGE',
            'LOCK',
            'UNLOCK',
            'PROPFIND',
            'VIEW'
        ];

        // Loop on every available methods
        foreach($methods as $method) {
            // Check if method is unauthorized
            if(!in_array($method, $authorized)){
                // Request path with current method
                $client->request($method, $url);
                // Check if 405 status code
                $this->assertEquals(405, $client->getResponse()->getStatusCode());
            }
        }

    }
}
