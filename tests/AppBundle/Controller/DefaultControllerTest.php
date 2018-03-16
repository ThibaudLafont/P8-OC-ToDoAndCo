<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Traits\ControllerTrait;

class DefaultControllerTest extends WebTestCase
{

    use ControllerTrait;

    /**
     * Functional Homepage testing without credentials
     */
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
        $this->checkUnauthorizedMethods("/", ['GET', 'HEAD'], $client);
    }

    /**
     * Functionnal Homepage testing with credentials
     */
    public function testIndexWithCredentials()
    {
        // Create an authenticated user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'Thibaud41',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);

        // Check unauthorized methods
        $this->checkUnauthorizedMethods("/", ['GET', 'HEAD'], $client);

        // Request homepage
        $crawler = $client->request('GET', '/');

        // Check if StatusCode is 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check if create-user button is present
        $this->checkLink(
            1,
            "Créer un utilisateur",
            "/users/create",
            $crawler
        );

        // Check if logout button is present
        $this->checkLink(
            1,
            "Se déconnecter",
            "/logout",
            $crawler
        );

        // Check if create-task button is present
        $this->checkLink(
            1,
            "Créer une nouvelle tâche",
            "/tasks/create",
            $crawler
        );

        // Check if list-current-tasks button is present
        $this->checkLink(
            1,
            "Consulter la liste des tâches à faire",
            "/tasks",
            $crawler
        );

        // Check if list-ended-tasks button is present
        $this->checkLink(
            1,
            "Consulter la liste des tâches terminées",
            "",
            $crawler
        );
    }
}
