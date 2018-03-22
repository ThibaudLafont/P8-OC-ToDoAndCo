<?php
namespace Tests\Functionnal\StatusCode;

class TaskTest extends StatusCode
{

    /**
     * Check status codes when request task_list with anon client
     */
    public function testTaskListWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request task_list and expect 302
        $this->checkRedirection(
            '/tasks',
            '/login',
            ['GET'],
            $client
        );

        // Check other methods are forbidden
        $this->checkForbiddenMethods(
            '/tasks',
            ['GET'],
            $client
        );
    }

    /**
     * Check status codes when request task_list with role_user client
     */
    public function testTaskListWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks',
            ['GET'],
            200,
            $client
        );

        // Request with other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks',
            ['GET'],
            $client
        );
    }

    /**
     * Check status codes when request task_list with role_admin client
     */
    public function testTaskListWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks',
            ['GET'],
            200,
            $client
        );

        // Request with other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks',
            ['GET'],
            $client
        );
    }


    /**
     * Check status codes when request task_create with anon client
     */
    public function testTaskCreateWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request task_list and expect 302
        $this->checkRedirection(
            '/tasks/create',
            '/login',
            ['GET', 'POST'],
            $client
        );

        // Check other methods are forbidden
        $this->checkForbiddenMethods(
            '/tasks/create',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Check status codes when request task_create with role_user client
     */
    public function testTaskCreateWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks/create',
            ['GET', 'POST'],
            200,
            $client
        );

        // Request with other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks/create',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Check status codes when request task_create with role_admin client
     */
    public function testTaskCreateWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks/create',
            ['GET', 'POST'],
            200,
            $client
        );

        // Request with other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks/create',
            ['GET', 'POST'],
            $client
        );
    }
    
    public function testTaskToggleWithAnon() {}
    public function testTaskToggleWithUser() {}
    public function testTaskToggleWithAdmin() {}

    public function testTaskDeleteWithAnon() {}

    public function testAnonTaskDeleteWithUser() {}
    public function testAnonTaskDeleteWithAdmin() {}

    public function testOwnedTaskDeleteWithUser() {}
    public function testOwnedTaskDeleteWithAdmin() {}

    public function testNotOwnedTaskDeleteWithUser() {}
    public function testNotOwnedTaskDeleteWithAdmin() {}

}