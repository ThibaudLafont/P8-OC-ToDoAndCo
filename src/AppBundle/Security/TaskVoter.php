<?php
namespace AppBundle\Security;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // Check if deletion
        if($attribute !== 'delete')   return false;
        // Check class of suject
        if(!$subject instanceof Task) return false;

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Get User from session
        $user = $token->getUser();

        // If $user not instanceof User return false
        if (!$user instanceof User) return false;

        // Store Task
        $task = $subject;

        // Return execution of canDelete
        return $this->canDelete($task, $user);
    }

    /**
     * Check Task assignation and User role to define if deletion is authorized
     *
     * @param Task $task  Task concerned
     * @param User $user  Logged User
     *
     * @return bool       Is User allowed to delete
     */
    private function canDelete(Task $task, User $user)
    {
        // If Anon task and ROLE_ADMIN User, authorize
        if(
            is_null($task->getUser()) &&
            $user->getRole() === 'admin'
        ) return true;

        // If owned task and logged user = task owner, authorize
        if($task->getUser() === $user) return true;

        // In other cases, don't authorize
        return false;
    }
}
