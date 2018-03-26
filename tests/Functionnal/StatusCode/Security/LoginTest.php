<?php
namespace Tests\Functionnal\StatusCode\StatusCode;

use Tests\Functionnal\StatusCode\StatusCode;

class LoginTest extends StatusCode
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
    }
    /**
     * Check all unauthorized methods
     */
    public function testTaskDeleteForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/login',
            ['GET', 'POST']
        );
    }

}
