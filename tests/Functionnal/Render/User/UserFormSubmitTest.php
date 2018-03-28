<?php
namespace Tests\Functionnal\Render\User;

use Tests\Functionnal\Render\BaseLayout;
use Tests\Functionnal\Traits\FormSubmit;

class UserFormSubmitTest extends BaseLayout
{
    // Traits
    use FormSubmit;

    /**
     * @param array $data
     * @param array $messages
     *
     * @dataProvider userInvalidValues
     */
    public function testCreateWithInvalidValues(array $data, array $messages)
    {
        // Check render from dataProvider values
        $this->checkFormRenderWithInvalidValues(
            '/users/create',
            $data,
            $messages
        );
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
        $this->checkFormRenderWithInvalidValues(
            '/users/1/edit',
            $data,
            $messages
        );
    }

    public function testCreateValidSubmission()
    {
        // Create client
        $this->submitUserFormWithValid('/users/create');
    }

    public function testEditValidSubmission()
    {
        // Create client
        $this->submitUserFormWithValid('/users/3/edit');
    }

    private function submitUserFormWithValid(string $path)
    {
        // Create client
        $client = $this->createRoleAdminClient();
        $client->followRedirects(true);

        $data = [
            'user[username]' => 'XXXX',
            'user[plainPassword][first]' => 'xxx',
            'user[plainPassword][second]'=> 'xxx',
            'user[email]' => 'xxx@gmail.com',
            'user[role]' => 'admin'
        ];

        // Create form
        $this->checkValidFormSubmit($client, $path, $data);
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

        // Invalid repeat pwd
        $set5 = [
            'user' => [
                'user[username]' => 'XXX',
                'user[plainPassword][first]' => 'xxx',
                'user[plainPassword][second]'=> 'x',
                'user[email]' => 'xxx@gmail.com',
                'user[role]' => 'admin'
            ],
            'messages' => [
                'Les deux mots de passe doivent correspondre.'
            ]
        ];

        return [$set1, $set2, $set3, $set4, $set5];
    }
}
