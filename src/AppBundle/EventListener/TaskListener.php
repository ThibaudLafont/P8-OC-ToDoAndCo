<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TaskListener
 *
 * Execute actions when Doctrine work with Messages entities
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
        // Get the session
        $session = $this->tokenStorage->getToken();

        // Assign the user to the trick
       $task->setUser($session->getUser());
    }

    /**
     * @return TokenStorageInterface
     */
    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

}
