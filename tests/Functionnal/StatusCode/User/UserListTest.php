<?php
namespace Tests\Functionnal\StatusCode\User;

use Symfony\Bundle\FrameworkBundle\Client;
use Tests\Functionnal\StatusCode\StatusCode;

class UserListTest extends StatusCode
{

    /**
     * Request user_list with anon client
     */
    public function testUserListWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request user_list by GET and expect 302
        $this->checkRedirection(
            '/users',
            '/login',
            ['GET'],
            $client
        );
    }

    /**
     * Request users_list with role_user client
     */
    public function testUserListWithUser() {
        // Create role_user Client
        $client = $this->createRoleUserClient();

        // Request users_list by GET and expect 403
        $this->checkResponseStatusCode(
            '/users',
            ['GET'],
            403,
            $client
        );
    }

    /**
     * Request users_list with role_admin client
     */
    public function testUserListWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Check 200 return when GET request
        $this->checkResponseStatusCode(
            '/users',
            ['GET'],
            200,
            $client
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
