<?php
namespace Tests\Functionnal\StatusCode\StatusCode;

use Tests\Functionnal\StatusCode\StatusCode;

class LogoutTest extends StatusCode
{
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
