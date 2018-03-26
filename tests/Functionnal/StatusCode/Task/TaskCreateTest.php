<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskCreateTest extends StatusCode
{
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
    }

    /**
     * Check all unauthorized methods
     */
    public function testTaskCreateForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/tasks/create',
            ['GET', 'POST']
        );
    }
}
