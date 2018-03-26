<?php
namespace Tests\Functionnal\StatusCode\StatusCode;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskDeleteTest extends StatusCode
{
    /**
     * Check all unauthorized methods
     */
    public function testTaskDeleteForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/tasks/1/delete',
            ['GET']
        );
    }

    public function testTaskDeleteWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request delete anonymous task and attempt 302 /login
        $this->checkRedirection(
            '/tasks/1/delete',
            '/login',
            ['GET'],
            $client
        );
    }

    public function testAnonTaskDeleteWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // In test_db, anon task id => 1
        // Request delete for anonymous task, attempt 403
        $this->checkResponseStatusCode(
            '/tasks/1/delete',
            ['GET'],
            403,
            $client
        );
    }

    public function testAnonTaskDeleteWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // In test_db, anon task id => 1
        // Request delete for anonymous task, attempt 302 to /tasks
        $this->checkRedirection(
            '/tasks/1/delete',
            '/tasks',
            ['GET'],
            $client,
            true
        );
    }

    public function testNotOwnedTaskDeleteWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // In test_db, not owned task id => 2
        // Request delete and expect 302 to /tasks
        $this->checkResponseStatusCode(
            '/tasks/2/delete',
            ['GET'],
            403,
            $client
        );
    }

    public function testNotOwnedTaskDeleteWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // In test_db, not owned task id => 3
        // Request delete and expect 302 to /tasks
        $this->checkResponseStatusCode(
            '/tasks/3/delete',
            ['GET'],
            403,
            $client
        );
    }

    public function testOwnedTaskDeleteWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // In test_db, owned task id => 3
        // Request delete and expect 302 to /tasks
        $this->checkRedirection(
            '/tasks/3/delete',
            '/tasks',
            ['GET'],
            $client,
            true
        );
    }

    public function testOwnedTaskDeleteWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // In test_db, owned task id => 2
        // Request delete and expect 302 to /tasks
        $this->checkRedirection(
            '/tasks/2/delete',
            '/tasks',
            ['GET'],
            $client,
            true
        );
    }

}
