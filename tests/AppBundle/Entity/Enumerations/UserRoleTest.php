<?php
namespace Tests\AppBundle\Entity\Enumerations;

use AppBundle\Entity\Enumerations\UserRole;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{

    public function testGetValueWithInvalidKey()
    {
        // Expect Exception
        $this->expectException('Symfony\Component\Config\Definition\Exception\InvalidTypeException');

        // Get with invalid key
        UserRole::getValue('xxx');
    }

    /**
     * Test getValue() with valid key
     *
     * @param string $key   Key for param
     * @param string $value Expected value
     *
     * @dataProvider validKeysForGetValue
     */
    public function testGetValueWithValidKey(string $key, string $value)
    {
        // Expect value when getValue(key)
        $this->assertEquals(
            $value,
            UserRole::getValue($key)
        );
    }

    public function validKeysForGetValue()
    {
        return [
            ['admin', 'ROLE_ADMIN'],
            ['user' , 'ROLE_USER']
        ];
    }

}