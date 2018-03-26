<?php
namespace Tests\Functionnal\StatusCode\Task;

use Tests\Functionnal\StatusCode\StatusCode;

class TaskToggleTest extends StatusCode
{
    
    public function testTaskToggleWithAnon() {
        // Request toggle task 1 and check redirection
        $this->checkRedirection(
            '/tasks/4/toggle',
            '/login',
            ['GET'],
            $this->createAnonClient()
        );
    }

    public function testTaskToggleWithUser() {
        // Request toggle and expect 200
        $this->checkRedirection(
            '/tasks/4/toggle',
            '/tasks',
            ['GET'],
            $this->createRoleUserClient(),
            true
        );
    }

    public function testTaskToggleWithAdmin() {
        // Request toggle and expect 200
        $this->checkRedirection(
            '/tasks/4/toggle',
            '/tasks',
            ['GET'],
            $this->createRoleAdminClient(),
            true
        );
    }

    /**
     * Check all unauthorized methods
     */
    public function testTaskToggleForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/tasks/4/toggle',
            ['GET']
        );
    }

}