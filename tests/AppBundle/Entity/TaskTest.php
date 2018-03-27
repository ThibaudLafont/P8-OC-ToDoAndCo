<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    /**
     * Test return value of Task::getUserUsername
     *
     * @param Task $task
     *
     * @param string $username
     * @dataProvider valuesForUserUsername
     */
    public function testGetUserUsername(Task $task, string $username)
    {
        $this->assertEquals(
            $username,
            $task->getUserUsername()
        );
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function valuesForUserUsername()
    {
        // First test with owned task
        $user = new User();
        $user->setUsername('Username');
        $task1 = new Task();
        $task1->setUser($user);

        // Second with Anonymous task
        $task2 = new Task();

        return [
            [$task1, 'Username'],
            [$task2, 'Anonymous']
        ];
    }

}
