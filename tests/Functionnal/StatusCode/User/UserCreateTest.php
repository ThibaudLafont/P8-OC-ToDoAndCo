<?php
namespace Tests\Functionnal\StatusCode\User;

use Tests\Functionnal\StatusCode\StatusCode;

class UserCreateTest extends StatusCode
{
    /**
     * Request user_create with anon client
     */
    public function testUserCreateWithAnon() {
        // Check 302 when request in GET&POST
        $this->checkRedirection(
            '/users/create',
            '/login',
            ['GET', 'POST'],
            $this->createAnonClient()
        );
    }

    /**
     * Request user_create with role_user client
     */
    public function testUserCreateWithUser() {
        // Request in GET&POST and expect 403
        $this->checkResponseStatusCode(
            '/users/create',
            ['GET', 'POST'],
            403,
            $this->createRoleUserClient()
        );
    }

    /**
     * Request user_create with role_admin client
     */
    public function testUserCreateWithAdmin() {
        // Request by GET&POST and expect 200
        $this->checkResponseStatusCode(
            '/users/create',
            ['GET', 'POST'],
            200,
            $this->createRoleAdminClient()
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
