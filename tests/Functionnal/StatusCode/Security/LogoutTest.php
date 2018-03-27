<?php
namespace Tests\Functionnal\StatusCode\StatusCode;

use Tests\Functionnal\StatusCode\StatusCode;

class LogoutTest extends StatusCode
{
    /**
     * Test request /logout with anon user
     */
    public function testLogoutWithAnon() {
        // Check 302 when GET /logout
        $this->checkRedirection(
            '/logout',
            '/login',
            ['GET'],
            $this->createAnonClient()
        );
    }

    /**
     * Test request /logout with role_user user
     */
    public function testLogoutWithUser() {
        // Request /logout in GET and expect redirection to /lg
        $this->checkRedirection(
            '/logout',
            '/login',
            ['GET'],
            $this->createRoleUserClient()
        );
    }

    /**
     * Test request /logout with role_admin user
     */
    public function testLogoutWithAdmin() {
        // Request /logout in GET and expect redirection to /lg
        $this->checkRedirection(
            '/logout',
            '/login',
            ['GET'],
            $this->createRoleAdminClient()
        );
    }

    /**
     * Check all unauthorized methods
     */
    public function testTaskDeleteForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/logout',
            ['GET']
        );
    }

}
