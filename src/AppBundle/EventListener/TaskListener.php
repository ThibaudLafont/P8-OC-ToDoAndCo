<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TaskListener
 *
 * @package AppBundle\EventListener
 */
class TaskListener
{
    /**
     * When a Trick is persisted or updated, this attribute is used in order to
     * fetch the user from session
     *
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * TrickListener constructor.
     * Assign tokenStorage Attribute though Depency Injection
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->setTokenStorage($tokenStorage);
    }

    /**
     * Task persist
     *
     * @param Task $task
     */
    public function prePersist(Task $task)
    {
        $this->assignUser($task);
    }

    /**
     * If Task is anonymous, assign logged User
     *
     * @param Task $task
     */
    public function preFlush(Task $task)
    {
        // If task was an Anon one, assign logged User
        if (is_null($task->getUser())) $this->assignUser($task);
    }

    /**
     * Assign logged User as Task author
     *
     * @param Task $task
     */
    private function assignUser(Task $task)
    {
        // Get the session
        $session = $this->getTokenStorage()->getToken();

        // Assign the user to the trick
        $task->setUser($session->getUser());
    }

    /**
     * Get tokenStorage
     *
     * @return TokenStorageInterface
     */
    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    /**
     * Set TokenStorage
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

}
