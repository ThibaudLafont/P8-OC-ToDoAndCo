<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskEditTest extends StatusCode
{
    /**
     * Request task_edit with anonymous user
     */
    public function testTaskEditWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request Edit and expect redirection
        $this->checkRedirection(
            '/tasks/2/edit',
            '/login',
            ['GET', 'POST'],
            $client
        );
    }

    /**
     * Request task_edit with role_user user
     */
    public function testTaskEditWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request edit and expect 200
        $this->checkResponseStatusCode(
            '/tasks/2/edit',
            ['GET', 'POST'],
            200,
            $client
        );
    }

    /**
     * Test task_edit with role_admin user
     */
    public function testTaskEditWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request edit and expect 200
        $this->checkResponseStatusCode(
            '/tasks/2/edit',
            ['GET', 'POST'],
            200,
            $client
        );
    }

    /**
     * Check all unauthorized methods
     */
    public function testTaskListForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/tasks/2/edit',
            ['GET', 'POST']
        );
    }
}
