<?php
namespace Tests\Functionnal\Render\User;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Functionnal\Render\BaseLayout;

class UserFormRequestTest extends BaseLayout
{
    /**
     * Check render of user_form in user_create action
     */
    public function testCreateRender()
    {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/users/create');

        // Check UserForm
        $this->checkUserFormRender('/users/create', $crawler);
    }

    /**
     * Check render and hydratation of user_form in user_edit
     *
     * @param string $action Action of form
     * @param array $data    Expected data witch hydrate edit form
     *
     * @dataProvider getPersistedUserValues
     */
    public function testEditRender(string $action, array $data)
    {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', $action);

        // Check UserForm
        $this->checkUserFormRender($action, $crawler, $data);
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
    /**
     * Check base render of User form
     *
     * @param string $action    Action attribute of form
     * @param Crawler $crawler  Crawler
     * @param array $values     If edit action, inquire attempted values here
     */
    protected function checkUserFormRender(
        string $action,
        Crawler $crawler,
        array $values = []
    )
    {
        // Check form
        $this->checkForm(
            $action,
            'post',
            $crawler
        );

        // Check username label
        $this->checkLabel(
            'Nom d\'utilisateur',
            'user_username',
            1,
            $crawler
        );
        // Check username input
        $this->checkInput(
            'text',
            'user_username',
            'user[username]',
            1,
            $crawler,
            isset($values['username']) ? $values['username'] : null
        );

        // Check password_first label
        $this->checkLabel(
            'Mot de passe',
            'user_plainPassword_first',
            1,
            $crawler
        );
        // Check password_first input
        $this->checkInput(
            'password',
            'user_plainPassword_first',
            'user[plainPassword][first]',
            1,
            $crawler
        );

        // Check password_repeat label
        $this->checkLabel(
            'Tapez le mot de passe à nouveau',
            'user_plainPassword_second',
            1,
            $crawler
        );
        // Check password_repeat input
        $this->checkInput(
            'password',
            'user_plainPassword_second',
            'user[plainPassword][second]',
            1,
            $crawler
        );

        // Check email label
        $this->checkLabel(
            'Adresse email',
            'user_email',
            1,
            $crawler
        );
        // Check email input
        $this->checkInput(
            'email',
            'user_email',
            'user[email]',
            1,
            $crawler,
            isset($values['email']) ? $values['email'] : null
        );

        // Check Role select
        $this->checkRoleSelect(
            $crawler,
            isset($values['role']) ? $values['role'] : null
        );

        // Check submit button
        $buttonValue = $action === '/users/create' ? 'Ajouter' : 'Modifier';
        $this->checkButton(
            $buttonValue,
            'submit',
            1,
            $crawler
        );
    }

    protected function checkRoleSelect(Crawler $crawler, $role)
    {
        // Check role label
        $this->checkLabel(
            'Type d\'utilisateur',
            'user_role',
            1,
            $crawler
        );

        // Check role select
        $this->assertEquals(
            1,
            $crawler->filter('select#user_role')->count()
        );

        // Check role option 1
        $this->assertEquals(
            1,
            $crawler->filter('option:contains("Administrateur")')->count()
        );

        // Check role option 2
        $this->assertEquals(
            1,
            $crawler->filter('option:contains("Utilisateur")')->count()
        );

        // if value[role] isset, check if good option is selected
        if(!is_null($role)) {
            $this->assertEquals(
                'selected',
                $crawler->filter('option:contains("' . $role . '")')->first()->attr('selected')
            );
        }
    }
}
