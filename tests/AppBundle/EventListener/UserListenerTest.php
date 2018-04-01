<?php
namespace Tests\AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\EventListener\UserListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserListenerTest extends TestCase
{
    /**
     * @var UserListener
     */
    private $listener;

    /**
     * @var BCryptPasswordEncoder
     */
    private $bcrypter;

    public function setUp()
    {
        // Create Factory
        $factory = new EncoderFactory([
            'AppBundle\Entity\User' => [
                'class' => 'Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder',
                'arguments' => [0=>13]
            ]
        ]);
        // Create encoder
        $encoder = new UserPasswordEncoder($factory);

        // Create listener
        $listener = new UserListener($encoder);
        $this->setListener($listener);

        // Bcrypt encoder for verification
        $bcrypter = new BCryptPasswordEncoder(13);
        $this->setBcrypter($bcrypter);
    }

    public function testUserPrePersist()
    {
        // Create User & assign plainPassword
        $user = new User();
        $user->setPlainPassword('pommepomme');

        // Execute prePersist
        $this->getListener()->preFlush($user);

        // Expect password correspond to bcrypted plainPassword
        $this->assertTrue(
            $this->getBcrypter()->isPasswordValid(
                $user->getPassword(),
                'pommepomme',
                ''
            )
        );
    }

    public function testPreFlushWithPlainPassword()
    {
        // Create User & assign plainPassword
        $user = new User();
        $user->setPlainPassword('pommepomme');

        // Execute prePersist
        $this->getListener()->preFlush($user);

        // Expect password correspond to bcrypted plainPassword
        $this->assertTrue(
            $this->getBcrypter()->isPasswordValid(
                $user->getPassword(),
                'pommepomme',
                ''
            )
        );
    }

    public function testPreFlushWithoutPlainPassword()
    {
        // Create User & assign plainPassword
        $user = new User();

        // Execute prePersist
        $this->getListener()->preFlush($user);

        // Attempt empty pwd
        $this->assertEquals(
            null,
            $user->getPassword()
        );
    }

    /**
     * @return UserListener
     */
    public function getListener() : UserListener
    {
        return $this->listener;
    }

    /**
     * @param UserListener $listener
     */
    public function setListener(UserListener $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @return BCryptPasswordEncoder
     */
    public function getBcrypter(): BCryptPasswordEncoder
    {
        return $this->bcrypter;
    }

    /**
     * @param BCryptPasswordEncoder $bcrypter
     */
    public function setBcrypter(BCryptPasswordEncoder $bcrypter)
    {
        $this->bcrypter = $bcrypter;
    }

}