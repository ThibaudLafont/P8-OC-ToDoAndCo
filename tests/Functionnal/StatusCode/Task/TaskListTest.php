<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskListTest extends StatusCode
{
    /**
     * Check status codes when request task_list with anon client
     */
    public function testTaskListWithAnon() {
        // Request task_list and expect 302
        $this->checkRedirection(
            '/tasks',
            '/login',
            ['GET'],
            $this->createAnonClient()
        );
    }

    /**
     * Check status codes when request task_list with role_user client
     */
    public function testTaskListWithUser() {
        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks',
            ['GET'],
            200,
            $this->createRoleUserClient()
        );
    }

    /**
     * Check status codes when request task_list with role_admin client
     */
    public function testTaskListWithAdmin() {
        // Request task_list and expect 200
        $this->checkResponseStatusCode(
            '/tasks',
            ['GET'],
            200,
            $this->createRoleAdminClient()
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