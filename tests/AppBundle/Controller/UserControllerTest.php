<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Traits\ControllerTrait;

class UserControllerTest extends WebTestCase
{
    // Traits
    use ControllerTrait;

    public function testListUsersWithoutCredentials()
    {
        // Create anon user
        $client = static::createClient();

        // Check GET request
        $client->request('GET', '/users');
        // Check if 302 statusCode
        $this->assertEquals(
            302,
            $client->getResponse()->getStatusCode()
        );
        // Check redirection
        $ext = $client->getRequest()->getSchemeAndHttpHost();     // Get host name
        $this->assertTrue(                                        // Check if redirected to /login
            $client->getResponse()->isRedirect($ext . "/login")
        );

        // Request /users with all other methods and expect 405 status code
        $this->checkUnauthorizedMethods('/users', ['GET', 'HEAD'], $client);
    }

    public function testListUsersWithUserCredentials()
    {
        // Create role_user user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleUser',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        // GET request
        $client->request('GET', '/users');
        // Check 403
        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode()
        );

        // Request /users with role_user user with all methods
        $this->checkUnauthorizedMethods('/users', ['GET', 'HEAD'], $client);
    }

    public function testListUsersWithAdminCredentials()
    {
        // Create role_admin user and request /users
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        $crawler = $client->request('GET', '/users');

        // Check statusCode
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        // Check add user button
        $this->checkLink(
            "Créer un utilisateur",
            "/users/create",
            1,
            $crawler
        );

        // Check logout button
        $this->checkLink(
            "Se déconnecter",
            "/logout",
            1,
            $crawler
        );

        // Check H1
        $this->assertEquals(
            1,
            $crawler->filter('h1:contains("Liste des utilisateurs")')->count()
        );

        // Check table presence
        $this->assertEquals(
            1,
            $crawler->filter('table')->count()
        );

        // Check nbre of users
        $this->assertEquals(
            2,
            $crawler->filter('tbody tr')->count()
        );

        // Check nbre of edit buttons
        $this->assertEquals(
            2,
            $crawler->filter('a:contains("Edit")')->count()
        );
    }

    public function testCreateUserWithoutCredentials()
    {
        // Request /users/create with anon-user
        // Check forbidden
        // Check redirection to /login
    }

    public function testCreateUserWithBadCredentials()
    {
        // Request /users/create with role_user user
        // Check forbidden
        // Check redirection to /login
    }

    public function testCreateUserDisplayWithCredentials()
    {
        // Request /users/create with role_admin user
        // Check statusCode
        // Check user-form
    }

    public function testInvalidCreateUserActionWithCredentials()
    {
        // Request /users/create with role_admin user
        // Fill&Submit form with invalid datas
        // Check redirection to /users/create
        // Check flash-message
        // Check value of valid fields
        // Check error messages
    }

    public function testValidCreateUserActionWithCredentials()
    {
        // Request /users/create with role_admin user
        // Fill&Submit form with valid datas
        // Check redirection to /users
        // Check flash-message
        // Check presence of new user
    }

    public function testEditUserDisplayWithoutCredentials()
    {
        // Request /users/id/edit with anon-user & all methods
        // Check forbidden
        // Check redirection to /login
    }

    public function testEditUserDisplayWithBadCredentials()
    {
        // Request /users/id/edit with role_user user & all methods
        // Check forbidden
        // Check redirection to /
    }

    public function testEditUserWithCredentials()
    {
        // Request /users/id/edit with role_admin user
        // Check statusCode
        // Check user-form
    }

    public function testInvalidEditUserWithCredentials()
    {
        // Request /users/id/edit with role_admin user
        // Fill&Submit form with invalid datas
        // Check redirection to /users/id/edit
        // Check flash-message
        // Check value of valid fields
        // Check error messages
    }

    public function testValidEditUserWithCredentials()
    {
        // Request /users/id/edit with role_admin user
        // Fill&Submit form with valid datas
        // Check redirection to /users
        // Check flash-message
        // Check presence of modifications
    }

    private function checkUserForm()
    {
        // Check form presence&target
        // Check username input&label
        // Check password input&label
        // Check email input&label
        // Check role input&label
        // Check submit button (!= edit/add)
    }
}
