<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Traits\ControllerTrait;

class UserControllerTest extends WebTestCase
{

    use ControllerTrait;

    public function testLoginGet()
    {
        // Create client
        $client = static::createClient();

        // Request /login by GET
        $crawler = $client->request('GET', '/login');

        // Check Response Status Code
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        // Check register link
        $this->checkLink(
            1,
            "Créer un utilisateur",
            "/users/create",
            $crawler
        );

        // Check form
        $this->checkForm('form', 1, '/login_check', 'post', $crawler);

        // Check username input
        $this->checkInput('input#username', 1, 'text', 'username', '_username', $crawler);
        // Check label presence
        // Check label value
        // Check label for

        // Check password input presence
        // Check submit button presence
    }

}
