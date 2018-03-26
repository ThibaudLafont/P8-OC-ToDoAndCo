<?php
namespace Tests\Functionnal\Render\User;

use Tests\Functionnal\Render\BaseLayout;

class UserFormSubmitTest extends BaseLayout
{
    /**
     * @param array $data
     * @param array $messages
     *
     * @dataProvider userInvalidValues
     */
    public function testCreateWithInvalidValues(array $data, array $messages)
    {
        $this->checkUserFormRenderWithInvalidValues('/users/create', $data, $messages);
    }

    /**
     * @param array $data
     * @param array $messages
     *
     * @dataProvider userInvalidValues
     */
    public function testEditRenderWithInvalidValues(array $data, array $messages)
    {
        // Check render from dataProvider values
        $this->checkUserFormRenderWithInvalidValues('/users/1/edit', $data, $messages);
    }

    private function checkUserFormRenderWithInvalidValues(
        string $path,
        array $data,
        array $messages
    )
    {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', $path);

        // Select form from submit button
        $submit = $crawler->selectButton(
            $path === '/users/create' ? 'Ajouter' : 'Modifier'
        );
        $form = $submit->form($data);

        // Request form submission
        $crawler = $client->submit($form);

        // Check total number of errors
        $this->assertEquals(
            count($messages),
            $crawler->filter('span.help-block')->count()
        );
        // Foreach messages
        foreach($messages as $message) {
            // Check presence and content
            $this->assertEquals(
                1,
                $crawler->filter('span.help-block ul li:contains("' . $message . '")')->count()
            );
        }
    }

    public function getPersistedUserValues()
    {
        return [
            [
                '/users/1/edit',
                [
                    'username' => 'RoleAdmin',
                    'email'    => 'roleadmin@gmail.com',
                    'role'     => 'Administrateur'
                ]
            ],
            [
                '/users/2/edit',
                [
                    'username' => 'RoleUser',
                    'email'    => 'roleuser@gmail.com',
                    'role'     => 'Utilisateur'
                ]
            ]
        ];
    }

    public function userInvalidValues()
    {
        // NotBlank test
        $set1 = [
            'user' => [
                'user[username]' => '',
                'user[plainPassword][first]' => '',
                'user[plainPassword][second]'=> '',
                'user[email]' => '',
                'user[role]' => 'admin'
            ],
            'messages' => [
                'Vous devez saisir un nom d\'utilisateur.',
                'Veuillez renseigner un mot de passe',
                'Vous devez saisir une adresse email.'
            ]
        ];

        // Invalid mail test
        $set2 = [
            'user' => [
                'user[username]' => 'XXX',
                'user[plainPassword][first]' => 'xxx',
                'user[plainPassword][second]'=> 'xxx',
                'user[email]' => 'test',
                'user[role]' => 'admin'
            ],
            'messages' => [
                'Le format de l\'adresse n\'est pas correcte.'
            ]
        ];

        // unique username test
        $set3 = [
            'user' => [
                'user[username]' => 'RoleUser',
                'user[plainPassword][first]' => 'xxx',
                'user[plainPassword][second]'=> 'xxx',
                'user[email]' => 'xxx@gmail.com',
                'user[role]' => 'admin'
            ],
            'messages' => [
                'Ce nom d\'utilisateur n\'est pas disponible.'
            ]
        ];

        // unique email test
        $set4 = [
            'user' => [
                'user[username]' => 'XXX',
                'user[plainPassword][first]' => 'xxx',
                'user[plainPassword][second]'=> 'xxx',
                'user[email]' => 'roleuser@gmail.com',
                'user[role]' => 'admin'
            ],
            'messages' => [
                'Cette adresse mail n\'est pas disponible.'
            ]
        ];

        return [$set1, $set2, $set3, $set4];
    }
}
