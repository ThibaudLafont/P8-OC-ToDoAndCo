<?php
namespace Tests\AppBundle\EventListener;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\EventListener\TaskListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskListenerTest extends TestCase
{

    private $logged;
    private $listener;

    public function setUp()
    {
        // Create user
        $user = $this->createUserEntity();
        // Store user as logged
        $this->setLogged($user);

        // Init UsernamePasswordToken
        $token = new UsernamePasswordToken(
            $user,
            null,
            'main',
            ['ROLE_ADMIN']
        );

        // Init TokenStorage
        $storage = new TokenStorage();
        $storage->setToken($token);

        // Create Eventlistener
        $listener = new TaskListener($storage);
        $this->setListener($listener);
    }

    public function testPreFlushWithAnonTask()
    {
        // Create Task
        $task = new Task();

        // execute preFlush
        $this->getListener()->preFlush($task);

        // Attempt to get user by task object
        $this->assertEquals(
            $this->getLogged(),
            $task->getUser()
        );
    }

    public function testPreFlushWithOwnedTask()
    {
        // Create owner
        $owner = $this->createUserEntity();

        // Create Task
        $task = new Task();
        // Assign owner
        $task->setUser($owner);

        // execute preFlush
        $this->getListener()->preFlush($task);

        // Attempt to get owner by task object, not logged
        $this->assertEquals(
            $owner,
            $task->getUser()
        );
    }

    public function testPrePersist()
    {
        // Create Task
        $task = new Task();

        // execute preFlush
        $this->getListener()->prePersist($task);

        // Check date
        $this->assertInstanceOf(
            '\DateTime',
            $task->getCreatedAt()
        );
        // Check user assign
        $this->assertEquals(
            $this->getLogged(),
            $task->getUser()
        );
    }

    private function createUserEntity()
    {
        $user = new User();
        $user->setPlainPassword('pommepomme');
        $user->setRole('admin');

        return $user;
    }

    /**
     * @return mixed
     */
    public function getLogged() : User
    {
        return $this->logged;
    }

    /**
     * @param mixed $logged
     */
    public function setLogged(User $logged)
    {
        $this->logged = $logged;
    }

    /**
     * @return mixed
     */
    public function getListener() : TaskListener
    {
        return $this->listener;
    }

    /**
     * @param mixed $listener
     */
    public function setListener(TaskListener $listener)
    {
        $this->listener = $listener;
    }

}