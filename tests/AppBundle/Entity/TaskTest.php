<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class TaskTest extends TestCase
{

    /**
     * @covers Task::getUserUsername
     */
    public function testAnonTaskGetUserUsername()
    {
        // Anonymous task
        $task = new Task();

        $this->assertEquals(
            'Anonymous',
            $task->getUserUsername()
        );
    }

    /**
     * @covers Task::getUserUsername
     */
    public function testOwnedTaskGetUserUsername()
    {
        // First test with owned task
        $user = new User();
        $user->setUsername('Username');
        $task = new Task();
        $task->setUser($user);

        $this->assertEquals(
            'Username',
            $task->getUserUsername()
        );
    }

    /**
     * @covers Task::setCreatedAt()
     * @dataProvider invalidCreateAtValues
     */
    public function testSetTaskCreateAtWithInvalid($value)
    {
        $task = new Task();

        // Expect TypeError
        $this->expectException('\TypeError');
        $task->setCreatedAt($value);
    }
    public function invalidCreateAtValues()
    {
        return [['lalala'], [1], [true], [new User()]];
    }

    /**
     * @covers Task::setCreatedAt()
     * @covers Task::getCreatedAt()
     */
    public function testGetTaskCreateAt()
    {
        $task = new Task();
        $task->setCreatedAt(new \DateTime('now'));

        $this->assertInstanceOf('\DateTime', $task->getCreatedAt());
    }

    /**
     * @covers Task::toggle()
     * @dataProvider invalidToggleValues
     */
    public function testToggleTaskWithInvalid($value)
    {
        $task = new Task();

        // Expect TypeError
        $this->expectException('\TypeError');
        $task->toggle($value);
    }
    /**
     * DataProvider
     * @return array
     */
    public function invalidToggleValues()
    {
        return [['lalala'], [new \DateTime()], [34]];
    }

}
