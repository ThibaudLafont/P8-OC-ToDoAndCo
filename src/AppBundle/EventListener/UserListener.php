<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
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
     * Before Message persist, assign user and creation date
     *
     * @param User $user
     */
    public function preFlush(User $user)
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
