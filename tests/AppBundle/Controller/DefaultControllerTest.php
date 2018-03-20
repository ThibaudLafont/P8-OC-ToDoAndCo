<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
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

    public function testIndexWithUserCredentials()
    {
        // Create role_user user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleUser',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);

        // Check index display
        $crawler = $this->checkIndex($client);

        // TODO : Check non presence of add-user button
        // Check create-user button non presence
        $this->checkLink(
            "Créer un utilisateur",
            "/users/create",
            0,
            $crawler
        );
    }

    /**
     * Functionnal Homepage testing with credentials
     */
    public function testIndexWithAdminCredentials()
    {
        // Create an authenticated user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);

        // Check index display & store crawler
        $crawler = $this->checkIndex($client);

        // Check if create-user button is present
        $this->checkLink(
            "Créer un utilisateur",
            "/users/create",
            1,
            $crawler
        );
    }

    private function checkIndex(Client $client)
    {
        // Check unauthorized methods
        $this->checkUnauthorizedMethods("/", ['GET', 'HEAD'], $client);

        // Request homepage
        $crawler = $client->request('GET', '/');

        // Check if StatusCode is 200
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        // TODO : add edit profile button check

        // Check if logout button is present
        $this->checkLink(
            "Se déconnecter",
            "/logout",
            1,
            $crawler
        );

        // Check if create-task button is present
        $this->checkLink(
            "Créer une nouvelle tâche",
            "/tasks/create",
            1,
            $crawler
        );

        // Check if list-current-tasks button is present
        $this->checkLink(
            "Consulter la liste des tâches à faire",
            "/tasks",
            1,
            $crawler
        );

        // Check if list-ended-tasks button is present
        $this->checkLink(
            "Consulter la liste des tâches terminées",
            "",
            1,
            $crawler
        );

        // return crawler for additional checks
        return $crawler;
    }
}
