<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexWithoutCredentials()
    {
        // Create client and request home_page
        $client = static::createClient();
        $client->request('GET', '/');

        // Check redirection
        $ext = $client->getRequest()->getSchemeAndHttpHost();     // Get host name
        $this->assertTrue(                                        // Check if redirected to /login
            $client->getResponse()->isRedirect($ext . "/login")
        );

        // Check unauthorized methods
        $this->testUnauthorizedMethods("/", ['GET', 'HEAD'], $client);

    }

    public function testIndexWithCredentials()
    {
        // Create an authenticated user
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Thibaud41',
            'PHP_AUTH_PW'   => 'pommepomme',
        ));

        // Check unauthorized methods
        $this->testUnauthorizedMethods("/", ['GET', 'HEAD'], $client);

        // Request homepage
        $crawler = $client->request('GET', '/');

        // Check if StatusCode is 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check if create-user button is present
        $this->testLink(
            1,
            "Créer un utilisateur",
            "/users/create",
            $crawler
        );

        // Check if logout button is present
        $this->testLink(
            1,
            "Se déconnecter",
            "/logout",
            $crawler
        );

        // Check if create-task button is present
        $this->testLink(
            1,
            "Créer une nouvelle tâche",
            "/tasks/create",
            $crawler
        );

        // Check if list-current-tasks button is present
        $this->testLink(
            1,
            "Consulter la liste des tâches à faire",
            "/tasks",
            $crawler
        );


        // Check if list-ended-tasks button is present
        $this->testLink(
            1,
            "Consulter la liste des tâches terminées",
            "/",
            $crawler
        );

    }
    
    private function testLink(int $count, string $content, string $href, Crawler $crawler)
    {
        // Store base href
        $host = $crawler->getBaseHref();

        // Check presence
        $this->assertEquals(
            $count,
            $crawler->filter('a:contains("' . $content . '")')->count()
        );

        // Check href
        $this->assertEquals(
            $host . substr($href, 1),
            $crawler->selectLink($content)->link()->getUri()
        );
    }

    private function testUnauthorizedMethods(string $uri, array $authorized, $client)
    {
        // All HTTP methods
        $methods = [
            'GET',
            'POST',
            'DELETE',
            'PUT',
            'PATCH',
            'COPY',
            'HEAD',
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
                $client->request($method, $uri);
                // Check if 405 status code
                $this->assertEquals(405, $client->getResponse()->getStatusCode());
            }
        }

    }
}
