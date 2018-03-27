<?php
namespace Tests\Functionnal\StatusCode\User;

use Tests\Functionnal\StatusCode\StatusCode;

class UserEditTest extends StatusCode
{
    /**
     * Request user_edit with anon client
     */
    public function testUserEditWithAnon() {
        // Request user_edit by GET&POST and expect 302
        $this->checkRedirection(
            '/users/1/edit',
            '/login',
            ['GET', 'POST'],
            $this->createAnonClient()
        );
    }

    /**
     * Request user_edit with role_user client
     */
    public function testUserEditWithUser() {
        // Request in GET&POST and expect 403
        $this->checkResponseStatusCode(
            '/users/1/edit',
            ['GET', 'POST'],
            403,
            $this->createRoleUserClient()
        );
    }

    /**
     * Request user_edit with role_admin client
     */
    public function testUserEditWithAdmin() {
        // Request by GET&POST and expect 200
        $this->checkResponseStatusCode(
            '/users/1/edit',
            ['GET', 'POST'],
            200,
            $this->createRoleAdminClient()
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
