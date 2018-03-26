<?php
namespace Tests\Functionnal\StatusCode\User;

use Tests\Functionnal\StatusCode\StatusCode;

class UserCreateTest extends StatusCode
{
    /**
     * Request user_create with anon client
     */
    public function testUserCreateWithAnon() {
        // Create anon client
        $client = $this->createAnonClient();

        // Check 302 when request in GET&POST
        $this->checkRedirection(
            '/users/create',
            '/login',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Request user_create with role_user client
     */
    public function testUserCreateWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request in GET&POST and expect 403
        $this->checkResponseStatusCode(
            '/users/create',
            ['GET', 'POST'],
            403,
            $client
        );
    }

    /**
     * Request user_create with role_admin client
     */
    public function testUserCreateWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request by GET&POST and expect 200
        $this->checkResponseStatusCode(
            '/users/create',
            ['GET', 'POST'],
            200,
            $client
        );
    }

    /**
     * Check all forbidden methods for user_create with all user types
     */
    public function testUserCreateForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/users/create',
            ['GET', 'POST']
        );
    }
}
