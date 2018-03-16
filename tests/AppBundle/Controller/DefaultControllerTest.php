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

    }

    public function testIndexWithCredentials()
    {
        // Create an authenticated user
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Thibaud41',
            'PHP_AUTH_PW'   => 'pommepomme',
        ));

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
//        $this->assertEquals(    // Check presence
//            1,
//            $crawler->filter('a:contains("Créer un utilisateur")')->count()
//        );
//        $this->assertEquals(    // Check href
//            $host . '/users/create',
//            $crawler->selectLink("Créer un utilisateur")->link()->getUri()
//        );

        // Check if logout button is present
        $this->testLink(
            1,
            "Se déconnecter",
            "/logout",
            $crawler
        );
//        $this->assertEquals(    // Check presence
//            1,
//            $crawler->filter('a:contains("Se déconnecter")')->count()
//        );
//        $this->assertEquals(    // Check href
//            $host . '/logout',
//            $crawler->selectLink("Se déconnecter")->link()->getUri()
//        );

        // Check if create-task button is present
        $this->testLink(
            1,
            "Créer une nouvelle tâche",
            "/tasks/create",
            $crawler
        );
//        $this->assertEquals(    // Check presence
//            1,
//            $crawler->filter('a:contains("Créer une nouvelle tâche")')->count()
//        );
//        $this->assertEquals(    // Check href
//            $host . '/tasks/create',
//            $crawler->selectLink("Créer une nouvelle tâche")->link()->getUri()
//        );

        // Check if list-current-tasks button is present
        $this->testLink(
            1,
            "Consulter la liste des tâches à faire",
            "/tasks",
            $crawler
        );
//        $this->assertEquals(    // Check presence
//            1,
//            $crawler->filter('a:contains("Consulter la liste des tâches à faire")')->count()
//        );
//        $this->assertEquals(    // Check href
//            $host . '/tasks',
//            $crawler->selectLink("Consulter la liste des tâches à faire")->link()->getUri()
//        );


        // Check if list-ended-tasks button is present
        $this->testLink(
            1,
            "Consulter la liste des tâches terminées",
            "/",
            $crawler
        );
//        $this->assertEquals(    // Check presence
//            1,
//            $crawler->filter('a:contains("Consulter la liste des tâches terminées")')->count()
//        );
//        $this->assertEquals(    // Check href
//            $host . '/',
//            $crawler->selectLink("Consulter la liste des tâches terminées")->link()->getUri()
//        );

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
}
