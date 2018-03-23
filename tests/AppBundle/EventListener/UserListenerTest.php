<?php
namespace Tests\AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\EventListener\UserListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserListenerTest extends TestCase
{

    /**
     * Test encoding
     *
     * @param string $plainPassword  Plain password to encode
     * @param string $encodedPassord Bcrypt encoded plainPassword
     *
     * @dataProvider valuesForSetPassword
     */
    public function testSetUserPassword(string $plainPassword, string $encodedPassord)
    {
          //////////////////
         // Not useful ? //
        //////////////////
//        $encoder = $this
//            ->getMockBuilder('Symfony\Component\Security\Core\Encoder\UserPasswordEncoder')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $listener = new UserListener($encoder);
//
//        $user = new User();
//        $user->setPlainPassword($plainPassword);

    }

    public function valuesForSetPassword()
    {
        return [
            ['pwdfortest', '$2y$10$IF6RNENAxOWUpZReGcn63.v6ciPSSJjRrvkD0TVJhGxNFljltRvh.'],
            ['additionnaltest', '$2y$10$vo/AdAp44p/1ezOYwkmbNuqziCjLBoE/pYlGYirBb3QGyf9wvICVK']
        ];
    }

}