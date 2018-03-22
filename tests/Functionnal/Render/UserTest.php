<?php
namespace Tests\Functionnal\Render;

use Symfony\Component\DomCrawler\Crawler;

class UserTest extends BaseLayout
{

    /**
     * Check render of user_list
     */
    public function testListRenderWithAdmin() {

        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/users');

        // Check admin base layout
        $this->checkAdminBaseLayout($crawler);

        // Check H1
        $this->checkTitle(
            'Liste des utilisateurs',
            1,
            $crawler
        );
        // Check table presence
        $this->assertEquals(
            1,
            $crawler->filter('table')->count()
        );
        // check edit_link 1
        $this->checkLink(
            'Edit',
            '/users/1/edit',
            1,
            $crawler
        );
        // check edit_link 2
        $this->checkLink(
            'Edit',
            '/users/2/edit',
            1,
            $crawler
        );

    }

    /**
     * Check render of user_form in user_create action
     */
    public function testCreateRenderWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/users/create');

        // Check UserForm
        $this->checkUserForm('/users/create', $crawler);
    }

    /**
     * Check render and hydratation of user_form in user_edit
     */
    public function testEditRenderWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/users/1/edit');

        // Expected values (from db_test)
        $user = [
            'username' => 'RoleAdmin',
            'email'    => 'roleadmintest@gmail.com',
            'role'     => 'Administrateur'
        ];

        // Check UserForm
        $this->checkUserForm('/users/1/edit', $crawler, $user);
    }

    /**
     * @param string $action    Action attribute of form
     * @param Crawler $crawler  Crawler
     * @param array $values     If edit action, inquire attempted values here
     */
    private function checkUserForm(string $action, Crawler $crawler, array $values = [])
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
        // Check role option
        $this->assertEquals(
            1,
            $crawler->filter('option:contains("Administrateur")')->count()
        );
        // Check role option
        $this->assertEquals(
            1,
            $crawler->filter('option:contains("Utilisateur")')->count()
        );

        // if value[role] isset, check if select is selected
        if(isset($values['role'])) {
            $this->assertEquals(
                'selected',
                $crawler->filter('option:contains("' . $values['role'] . '")')->first()->attr('selected')
            );
        }

        // Check submit button
        $buttonValue = $action === '/users/create' ? 'Ajouter' : 'Modifier';
        $this->checkButton(
            $buttonValue,
            'submit',
            1,
            $crawler
        );
    }

}
