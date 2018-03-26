<?php
namespace Tests\Functionnal\StatusCode\StatusCode;

use Tests\Functionnal\StatusCode\StatusCode;

class LoginTest extends StatusCode
{
    /**
     * Test request /login with anon user
     */
    public function testLoginWithAnon() {
        // Check 200 status code when GET
        $this->checkResponseStatusCode(
            '/login',
            ['GET', 'POST'],
            200,
            $this->createAnonClient()
        );
    }

    /**
     * Test request /login with role_user user
     */
    public function testLoginWithUser() {
        // Check 403 forbidden when GET&POST
        $this->checkResponseStatusCode(
            '/login',
            ['GET', 'POST'],
            403,
            $this->createRoleUserClient()
        );
    }

    /**
     * Test request /login with role_admin user
     */
    public function testLoginWithAdmin() {
        // Check 403 forbidden when GET&POST
        $this->checkResponseStatusCode(
            '/login',
            ['GET', 'POST'],
            403,
            $this->createRoleAdminClient()
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
