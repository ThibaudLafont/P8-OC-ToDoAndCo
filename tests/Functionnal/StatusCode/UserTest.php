<?php
namespace Tests\Functionnal\StatusCode;

class UserTest extends StatusCode
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

        // Check forbidden for other methods
        $this->checkForbiddenMethods(
            '/users',
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

        // Check other methods return 405
        $this->checkForbiddenMethods(
            '/users',
            ['GET'],
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

        // Check other methods return 405
        $this->checkForbiddenMethods(
            '/users',
            ['GET'],
            $client
        );
    }

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

        // Check 405 for other methods
        $this->checkForbiddenMethods(
            '/users/create',
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

        // Check other methods return 405
        $this->checkForbiddenMethods(
            '/users/create',
            ['GET', 'POST'],
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

        // Check forbidden methods
        $this->checkForbiddenMethods(
            '/users/create',
            ['GET', 'POST'],
            $client
        );
    }

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

        // Check other methods are forbidden
        $this->checkForbiddenMethods(
            '/users/1/edit',
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

        // Check other methods return 405
        $this->checkForbiddenMethods(
            '/users/1/edit',
            ['GET', 'POST'],
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

        // Check forbidden methods
        $this->checkForbiddenMethods(
            '/users/1/edit',
            ['GET', 'POST'],
            $client
        );
    }

}
