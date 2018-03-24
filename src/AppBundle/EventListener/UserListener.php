<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class MessageListener
 *
 * Execute actions when Doctrine work with Messages entities
 *
 * @package AppBundle\EventListener
 */
class UserListener
{

    /**
     * Password encoder
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserListener constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * User persist
     *
     * @param User $user
     */
    public function prePersist(User $user)
    {
        // Set user password
        $this->setUserPassword($user);
    }

    /**
     * User preFlush
     *
     * @param User $user
     */
    public function preFlush(User $user)
    {
        // Check if plainPassword is set
        if($user->getPlainPassword() !== null){
            // Set user password
            $this->setUserPassword($user);
        }
    }

    /**
     * Encode and set password from plainPassword
     *
     * @param $user User
     */
    private function setUserPassword(User $user)
    {
        // Set user password
        $user->setPassword(
        // Encode pwd
            $this->encoder->encodePassword(
                $user,
                $user->getPlainPassword() // From plainPassword
            )
        );
    }
}
