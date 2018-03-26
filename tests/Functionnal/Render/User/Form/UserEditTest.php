<?php
namespace Tests\Functionnal\Render\User\Form;

class UserEditTest extends UserFormRender
{
    /**
     * Check render and hydratation of user_form in user_edit
     *
     * @param string $action Action of form
     * @param array $data    Expected data witch hydrate edit form
     *
     * @dataProvider getUserValues
     */
    public function testEditRenderWithAdmin(string $action, array $data) {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', $action);

        // Check UserForm
        $this->checkUserFormRender($action, $crawler, $data);
    }

    public function getUserValues()
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
}
