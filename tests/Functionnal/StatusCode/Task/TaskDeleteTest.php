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
        // Request delete anonymous task and attempt 302 /login
        $this->checkRedirection(
            '/tasks/1/delete',
            '/login',
            ['GET'],
            $this->createAnonClient()
        );
    }

    public function testAnonTaskDeleteWithUser() {
        // In test_db, anon task id => 1
        // Request delete for anonymous task, attempt 403
        $this->checkResponseStatusCode(
            '/tasks/1/delete',
            ['GET'],
            403,
            $this->createRoleUserClient()
        );
    }

    public function testAnonTaskDeleteWithAdmin() {
        // In test_db, anon task id => 1
        // Request delete for anonymous task, attempt 302 to /tasks
        $this->checkRedirection(
            '/tasks/1/delete',
            '/tasks',
            ['GET'],
            $this->createRoleAdminClient(),
            true
        );
    }

    public function testNotOwnedTaskDeleteWithUser() {
        // In test_db, not owned task id => 2
        // Request delete and expect 302 to /tasks
        $this->checkResponseStatusCode(
            '/tasks/2/delete',
            ['GET'],
            403,
            $this->createRoleUserClient()
        );
    }

    public function testNotOwnedTaskDeleteWithAdmin() {
        // In test_db, not owned task id => 3
        // Request delete and expect 302 to /tasks
        $this->checkResponseStatusCode(
            '/tasks/3/delete',
            ['GET'],
            403,
            $this->createRoleAdminClient()
        );
    }

    public function testOwnedTaskDeleteWithUser() {
        // In test_db, owned task id => 3
        // Request delete and expect 302 to /tasks
        $this->checkRedirection(
            '/tasks/3/delete',
            '/tasks',
            ['GET'],
            $this->createRoleUserClient(),
            true
        );
    }

    public function testOwnedTaskDeleteWithAdmin() {
        // In test_db, owned task id => 2
        // Request delete and expect 302 to /tasks
        $this->checkRedirection(
            '/tasks/2/delete',
            '/tasks',
            ['GET'],
            $this->createRoleAdminClient(),
            true
        );
    }

}
