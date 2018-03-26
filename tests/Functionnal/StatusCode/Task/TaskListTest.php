<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskListTest extends StatusCode
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
    }

    /**
     * Check all other methods are forbidden for task_list
     */
    public function testUserListForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/tasks',
            ['GET']
        );
    }
}