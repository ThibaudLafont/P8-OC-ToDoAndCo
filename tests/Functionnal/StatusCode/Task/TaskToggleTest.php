<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskToggleTest extends StatusCode
{
    
    public function testTaskToggleWithAnon() {
        // Create Client
        $client = $this->createAnonClient();

        // Request toggle task 1 and check redirection
        $this->checkRedirection(
            '/tasks/2/toggle',
            '/login',
            ['GET'],
            $client
        );
    }

    public function testTaskToggleWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();

        // Request toggle and expect 200
        $this->checkRedirection(
            '/tasks/2/toggle',
            '/tasks',
            ['GET'],
            $client,
            true
        );
    }

    public function testTaskToggleWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request toggle and expect 200
        $this->checkRedirection(
            '/tasks/2/toggle',
            '/tasks',
            ['GET'],
            $client,
            true
        );
    }

    /**
     * Check all unauthorized methods
     */
    public function testTaskToggleForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/tasks/2/toggle',
            ['GET']
        );
    }

}