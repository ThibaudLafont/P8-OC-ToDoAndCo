<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Traits\ControllerTrait;

class SecurityControllerTest extends WebTestCase
{
    // Traits
    use ControllerTrait;

    // Test functions
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
            "Créer un utilisateur",
            "/users/create",
            1,
            $crawler
        );

        // Check form
        $this->checkForm(
            'form',
            '/login_check',
            'post',
            1,
            $crawler
        );

        // Check username input
        $this->checkInput(
            'input#username',
            'text',
            'username',
            '_username',
            1,
            $crawler
        );
        // Check username label
        $this->checkLabel(
            "Nom d'utilisateur :",
            "username",
            1,
            $crawler
        );

        // Check password input
        $this->checkInput(
            'input#password',
            'password',
            'password',
            '_password',
            1,
            $crawler
        );
        // Check password label
        $this->checkLabel(
            "Mot de passe :",
            "password",
            1,
            $crawler
        );

        // Check submit button
        $this->checkButton('form button', 'Se connecter', 'submit', 1, $crawler);
    }

    public function testLoginSubmitWithInvalidCredentials()
    {
        // Generate client and request
        $client = static::createClient();
        $client->followRedirects(true);                // Have to follow redirections
        $crawler = $client->request('GET', '/login');  // Request login page

        // Generate and store Crawler by form submit
        $form = $this->createLoginForm('invalid', 'invalid', $crawler);
        $crawler = $client->submit($form);

        // Check flash alert presence
        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());
        // Check flash alert value
        $this->assertEquals(
            'Invalid credentials.',
            $crawler->filter('.alert-danger')->getNode(0)->nodeValue
        );
    }

    public function testLoginSubmitWithValidCredentials()
    {
        // Generate client and request
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');  // Request login page

        // Generate and store Crawler
        $form = $this->createLoginForm('Thibaud41', 'pommepomme', $crawler);
        $client->submit($form);

        // Check if redirected
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
        // Check statusCode
        $this->assertEquals(
            302,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testLogout()
    {
        // Request Logout with authenticated user
        // Check redirection
        // Check logout is effective
    }

    // Private functions
    private function createLoginForm(string $username, string $password, Crawler $crawler)
    {
        // Login form fields
        $fields = [
            '_username' => $username,
            '_password' => $password
        ];

        // Return Crawler
        return $this->createForm('Se connecter', $fields, $crawler);
    }

}
