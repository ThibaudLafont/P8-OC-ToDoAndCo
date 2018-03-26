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
    
    public function testTaskToggleWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request toggle task 1 and check redirection
        $this->checkRedirection(
            '/tasks/1/toggle',
            '/login',
            ['GET'],
            $client
        );

        // Check other methods are forbidden
        $this->checkForbiddenMethods(
            '/tasks/1/toggle',
            ['GET'],
            $client
        );
    }

    public function testTaskToggleWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request toggle and expect 200
        $this->checkRedirection(
            '/tasks/1/toggle',
            '/tasks',
            ['GET'],
            $client,
            true
        );

        // Request by other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks/1/toggle',
            ['GET'],
            $client
        );
    }

    public function testTaskToggleWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request toggle and expect 200
        $this->checkRedirection(
            '/tasks/1/toggle',
            '/tasks',
            ['GET'],
            $client,
            true
        );

        // Request by other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks/1/toggle',
            ['GET'],
            $client
        );
    }

    public function testTaskEditWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request Edit and expect redirection
        $this->checkRedirection(
            '/tasks/1/edit',
            '/login',
            ['GET', 'POST'],
            $client
        );

        // Attempt 405 for other methods
        $this->checkForbiddenMethods(
            '/tasks/1/edit',
            ['GET', 'POST'],
            $client
        );
    }

    public function testTaskEditWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Anon Task id in test_db => 1
        // Request edit and expect 200
        $this->checkResponseStatusCode(
            '/tasks/1/edit',
            ['GET', 'POST'],
            200,
            $client
        );

        // Request by other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks/1/edit',
            ['GET', 'POST'],
            $client
        );
    }

    public function testTaskEditWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Anon Task id in test_db => 1
        // Request edit and expect 200
        $this->checkResponseStatusCode(
            '/tasks/1/edit',
            ['GET', 'POST'],
            200,
            $client
        );

        // Request by other methods and expect 405
        $this->checkForbiddenMethods(
            '/tasks/1/edit',
            ['GET', 'POST'],
            $client
        );
    }

    public function testTaskDeleteWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request delete task and attempt 302 /login
        $this->checkRedirection(
            '/tasks/1/delete',
            '/login',
            ['GET'],
            $client
        );

        // Attempt 405 for other methods
        $this->checkForbiddenMethods(
            '/tasks/1/delete',
            ['GET'],
            $client
        );
    }

    public function testAnonTaskDeleteWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request delete for anonymous task, attempt 403
        $this->checkResponseStatusCode(
            '/tasks/1/delete',
            ['GET'],
            403,
            $client
        );

        // Attempt 405 for other methods
        $this->checkForbiddenMethods(
            '/tasks/1/delete',
            ['GET'],
            $client
        );
    }

    public function testAnonTaskDeleteWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Attempt 405 for other methods
        $this->checkForbiddenMethods(
            '/tasks/1/delete',
            ['GET'],
            $client
        );

        // Request delete for anonymous task, attempt 302 to /tasks
//        $this->checkRedirection(
//            '/tasks/1/delete',
//            '/tasks',
//            ['GET'],
//            $client
//        );
    }

    public function testOwnedTaskDeleteWithUser() {}
    public function testOwnedTaskDeleteWithAdmin() {}

    public function testNotOwnedTaskDeleteWithUser() {}
    public function testNotOwnedTaskDeleteWithAdmin() {}

}