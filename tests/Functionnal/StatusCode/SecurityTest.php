<?php
namespace Tests\Functionnal\StatusCode;

class SecurityTest extends StatusCode
{

    /**
     * Test request /login with anon user
     */
    public function testLoginWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Check 200 status code when GET
        $this->checkResponseStatusCode(
            '/login',
            ['GET', 'POST'],
            200,
            $client
        );

        // Check 405 status code for other methods
        $this->checkForbiddenMethods(
            '/login',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Test request /login with role_user user
     */
    public function testLoginWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Check 403 forbidden when GET&POST
        $this->checkResponseStatusCode(
            '/login',
            ['GET', 'POST'],
            403,
            $client
        );

        // Check 405 unauthorized for other methods
        $this->checkForbiddenMethods(
            '/login',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Test request /login with role_admin user
     */
    public function testLoginWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Check 403 forbidden when GET&POST
        $this->checkResponseStatusCode(
            '/login',
            ['GET', 'POST'],
            403,
            $client
        );

        // Check 405 unauthorized for other methods
        $this->checkForbiddenMethods(
            '/login',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Test request /logout with anon user
     */
    public function testLogoutWithAnon() {
        // Create anon client
        $client = $this->createAnonClient();

        // Check 302 when GET /logout
        $this->checkRedirection(
            '/logout',
            '/login',
            ['GET'],
            $client
        );

        // Check 405 for other methods
        $this->checkForbiddenMethods(
            '/logout',
            ['GET'],
            $client
        );
    }

    /**
     * Test request /logout with role_user user
     */
    public function testLogoutWithUser() {
        // Create role_user client
        $client = $this->createRoleUserClient();

        // Request /logout in GET and expect redirection to /lg
        $this->checkRedirection(
            '/logout',
            '/login',
            ['GET'],
            $client
        );

        // Check 405 for other methods
        $this->checkForbiddenMethods(
            '/logout',
            ['GET'],
            $client
        );
    }

    /**
     * Test request /logout with role_admin user
     */
    public function testLogoutWithAdmin() {
        // Create role_user client
        $client = $this->createRoleAdminClient();

        // Request /logout in GET and expect redirection to /lg
        $this->checkRedirection(
            '/logout',
            '/login',
            ['GET'],
            $client
        );

        // Check 405 for other methods
        $this->checkForbiddenMethods(
            '/logout',
            ['GET'],
            $client
        );
    }

}
