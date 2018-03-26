<?php
namespace Tests\Functionnal\StatusCode\User;

use Tests\Functionnal\StatusCode\StatusCode;

class UserListTest extends StatusCode
{

    /**
     * Request user_list with anon client
     */
    public function testUserListWithAnon() {
        // Request user_list by GET and expect 302
        $this->checkRedirection(
            '/users',
            '/login',
            ['GET'],
            $this->createAnonClient()
        );
    }

    /**
     * Request users_list with role_user client
     */
    public function testUserListWithUser() {
        // Request users_list by GET and expect 403
        $this->checkResponseStatusCode(
            '/users',
            ['GET'],
            403,
            $this->createRoleUserClient()
        );
    }

    /**
     * Request users_list with role_admin client
     */
    public function testUserListWithAdmin() {
        // Check 200 return when GET request
        $this->checkResponseStatusCode(
            '/users',
            ['GET'],
            200,
            $this->createRoleAdminClient()
        );
    }

    /**
     * Check all forbidden methods for user_list with all user types
     */
    public function testUserListForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/users',
            ['GET']
        );
    }

}
