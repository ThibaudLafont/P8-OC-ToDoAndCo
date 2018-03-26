<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskEditTest extends StatusCode
{
    /**
     * Request task_edit with anonymous user
     */
    public function testTaskEditWithAnon() {
        // Request Edit and expect redirection
        $this->checkRedirection(
            '/tasks/4/edit',
            '/login',
            ['GET', 'POST'],
            $this->createAnonClient()
        );
    }

    /**
     * Request task_edit with role_user user
     */
    public function testTaskEditWithUser() {
        // Request edit and expect 200
        $this->checkResponseStatusCode(
            '/tasks/4/edit',
            ['GET', 'POST'],
            200,
            $this->createRoleUserClient()
        );
    }

    /**
     * Test task_edit with role_admin user
     */
    public function testTaskEditWithAdmin() {
        // Request edit and expect 200
        $this->checkResponseStatusCode(
            '/tasks/4/edit',
            ['GET', 'POST'],
            200,
            $this->createRoleAdminClient()
        );
    }

    /**
     * Check all unauthorized methods
     */
    public function testTaskListForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/tasks/4/edit',
            ['GET', 'POST']
        );
    }
}
