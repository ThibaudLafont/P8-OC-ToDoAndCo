<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testSetRoleWithInvalidValue()
    {
        // Create User
        $user = new User();

        // Expect Exception
        $this->expectException('InvalidArgumentException');

        // Try to set invalid role
        $user->setRole('xxxx');
    }

    /**
     * Test the return value of getRoles()
     *
     * @param string $setValue     Value for setRole
     * @param string $expectReturn Expected return of getRoles()
     *
     * @dataProvider valuesForGetRoles
     */
    public function testGetRoles(string $setValue, string $expectReturn)
    {
        // Create User
        $user = new User();

        // Set role
        $user->setRole($setValue);

        // Get and expect return value
        $this->assertEquals([$expectReturn], $user->getRoles());
    }

    public function valuesForGetRoles()
    {
        return [
            ['admin', 'ROLE_ADMIN'],
            ['user',  'ROLE_USER' ]
        ];
    }
}
