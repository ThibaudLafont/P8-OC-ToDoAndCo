<?php
namespace Tests\Functionnal\StatusCode\User;

use Symfony\Bundle\FrameworkBundle\Client;
use Tests\Functionnal\StatusCode\StatusCode;

class UserEditTest extends StatusCode
{
    /**
     * Request user_edit with anon client
     */
    public function testUserEditWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request user_edit by GET&POST and expect 302
        $this->checkRedirection(
            '/users/1/edit',
            '/login',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Request user_edit with role_user client
     */
    public function testUserEditWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request in GET&POST and expect 403
        $this->checkResponseStatusCode(
            '/users/1/edit',
            ['GET', 'POST'],
            403,
            $client
        );
    }

    /**
     * Request user_edit with role_admin client
     */
    public function testUserEditWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request by GET&POST and expect 200
        $this->checkResponseStatusCode(
            '/users/1/edit',
            ['GET', 'POST'],
            200,
            $client
        );
    }

    /**
     * Check all forbidden methods for user_edit with all user types
     */
    public function testUserCreateForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/users/1/edit',
            ['GET', 'POST']
        );
    }
}
