<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskCreateTest extends StatusCode
{
    /**
     * Check status codes when request task_create with anon client
     */
    public function testTaskCreateWithAnon() {
        // Request task_list and expect 302
        $this->checkRedirection(
            '/tasks/create',
            '/login',
            ['GET', 'POST'],
            $this->createAnonClient()
        );
    }

    /**
     * Check status codes when request task_create with role_user client
     */
    public function testTaskCreateWithUser() {
        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks/create',
            ['GET', 'POST'],
            200,
            $this->createRoleUserClient()
        );
    }

    /**
     * Check status codes when request task_create with role_admin client
     */
    public function testTaskCreateWithAdmin() {
        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks/create',
            ['GET', 'POST'],
            200,
            $this->createRoleAdminClient()
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
