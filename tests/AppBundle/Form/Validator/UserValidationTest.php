<?php
namespace Tests\AppBundle\Form\Validator;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserValidationTest extends KernelTestCase
{

    /**
     * Test Validation of User
     *
     * @param array $data     Data to hydrate User
     * @param array $messages Expected error messages
     *
     * @dataProvider invalidUserValues
     */
    public function testInvalidUser(array $data, array $messages) {

        // Get Validator though kernel
        self::bootKernel();
        $validator = static::$kernel->getContainer()->get('validator');

        // Create and hydrate User
        $user = new \AppBundle\Entity\User();
        $user->setUsername($data['username']);
        $user->setPlainPassword($data['plainPassword']);
        $user->setEmail($data['email']);
        $user->setRole($data['role']);

        // Validate User
        $violations = $validator->validate($user);

        // Expect same number of violations as messages
        $this->assertEquals(
            count($violations),
            count($messages)
        );

        foreach($violations as $violation)
        {
            $this->assertTrue(
                in_array($violation->getMessage(), $messages)
            );
        }

    }

    public function invalidUserValues()
    {
        // First data_set
        $set1 = [
            'user' => [
                'username' => '',
                'plainPassword' => '',
                'email' => '',
                'role' => 'admin'
            ],
            'messages' => [
                'Vous devez saisir un nom d\'utilisateur.',
                'Veuillez renseigner un mot de passe',
                'Vous devez saisir une adresse email.'
            ]
        ];

        // Second data_set
        $set2 = [
            'user' => [
                'username' => 'XXX',
                'plainPassword' => 'xxx',
                'email' => 'test',
                'role' => 'admin'
            ],
            'messages' => [
                'Le format de l\'adresse n\'est pas correcte.'
            ]
        ];

        // Assembly and return
        return [
            [$set1['user'], $set1['messages']],
            [$set2['user'], $set2['messages']]
        ];
    }

}
