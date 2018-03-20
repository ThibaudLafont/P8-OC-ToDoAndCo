<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Traits\ControllerTrait;

class UserControllerTest extends WebTestCase
{
    // Traits
    use ControllerTrait;

    public function testListUsersWithoutCredentials()
    {
        // Create anon user
        $client = static::createClient();

        // Check GET request
        $client->request('GET', '/users');
        // Check if 302 statusCode
        $this->assertEquals(
            302,
            $client->getResponse()->getStatusCode()
        );
        // Check redirection
        $ext = $client->getRequest()->getSchemeAndHttpHost();     // Get host name
        $this->assertTrue(                                        // Check if redirected to /login
            $client->getResponse()->isRedirect($ext . "/login")
        );

        // Request /users with all other methods and expect 405 status code
        $this->checkUnauthorizedMethods('/users', ['GET', 'HEAD'], $client);
    }

    public function testListUsersWithUserCredentials()
    {
        // Create role_user user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleUser',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        // GET request
        $client->request('GET', '/users');
        // Check 403
        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode()
        );

        // Request /users with role_user user with all methods
        $this->checkUnauthorizedMethods('/users', ['GET', 'HEAD'], $client);
    }

    public function testListUsersWithAdminCredentials()
    {
        // Create role_admin user and request /users
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        $crawler = $client->request('GET', '/users');

        // Check statusCode
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        // Admin layout check
        $this->checkBaseLinks($crawler, true);

        // Check H1
        $this->assertEquals(
            1,
            $crawler->filter('h1:contains("Liste des utilisateurs")')->count()
        );

        // Check table presence
        $this->assertEquals(
            1,
            $crawler->filter('table')->count()
        );

        // Check nbre of users
        $this->assertEquals(
            2,
            $crawler->filter('tbody tr')->count()
        );

        // Check nbre of edit buttons
        $this->assertEquals(
            2,
            $crawler->filter('a:contains("Edit")')->count()
        );
    }

    public function testCreateUserWithoutCredentials()
    {
        // Create anon user
        $client = static::createClient();

        // Check GET request
        $client->request('GET', '/users/create');
        // Check if 302 statusCode
        $this->assertEquals(
            302,
            $client->getResponse()->getStatusCode()
        );
        // Check redirection
        $ext = $client->getRequest()->getSchemeAndHttpHost();     // Get host name
        $this->assertTrue(                                        // Check if redirected to /login
            $client->getResponse()->isRedirect($ext . "/login")
        );

        // Request /users with all other methods and expect 405 status code
        $this->checkUnauthorizedMethods('/users/create', ['GET', 'HEAD', 'POST'], $client);
    }

    public function testCreateUserWithUserCredentials()
    {
        // Create role_user user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleUser',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        // GET request
        $client->request('GET', '/users/create');

        // Check 403
        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode()
        );

        // Request /users with role_user user with all methods
        $this->checkUnauthorizedMethods('/users/create', ['GET', 'HEAD', 'POST'], $client);
    }

    public function testCreateUserDisplayWithAdminCredentials()
    {
        // Create role_admin user and request /users
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        $crawler = $client->request('GET', '/users/create');

        // Check statusCode
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        // Check base links
        $this->checkBaseLinks($crawler, true);

        // Check H1
        $this->assertEquals(
            1,
            $crawler->filter('h1:contains("Créer un utilisateur")')->count()
        );

        // Check User form
        $this->checkUserForm($crawler, '/users/create');
    }

    public function testValidCreateUserSubmitWithCredentials()
    {
        // Request /users/create with role_admin user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        $crawler = $client->request('GET', '/users/create');

        // Fill&Submit form with valid datas
        $form = $this->createUserForm(
            'NouvelUtilisateur',
            'pommepomme',
            'pommepomme',
            'test@gmail.com',
            'admin',
            $crawler,
            true
        );
        $client->followRedirects(true);
        $crawler = $client->submit($form);

        // Check redirection to /users
        $ext = $client->getRequest()->getSchemeAndHttpHost();     // Get host name
        $this->assertEquals(
            $ext . '/users',
            $crawler->getBaseHref()
        );

        // Check flash-message
        $this->assertEquals(
            1,
            $crawler->filter('div.alert-success:contains("L\'utilisateur a bien été ajouté.")')->count()
        );

        // Check presence of new user
        $this->assertEquals(
            1,
            $crawler->filter('td:contains("NouvelUtilisateur")')->count()
        );
    }

    public function testInvalidPasswordCreateUserSubmitWithCredentials()
    {
        // Store input values in $fields array
        $fields = [
            'username' => 'NewGuy',
            'password' => 'pommepomme',
            'password_repeat' => 'pommepomm',
            'email' => 'newguy@gmail.com',
            'role' => 'admin'
        ];

        // Create, submit and check form
        $crawler = $this->submitAndCheckInvalidUserCreateForm($fields);
        // Check error messages
        $this->assertEquals(
            1,
            $crawler->filter('span.help-block:contains("Les deux mots de passe doivent correspondre.")')->count()
        );
    }

    public function testInvalidEmailCreateUserSubmitWithCredentials()
    {
        // Store input values in $fields array
        $fields = [
            'username' => 'NewGuy',
            'password' => 'pommepomme',
            'password_repeat' => 'pommepomm',
            'email' => 'newguy@gmail',
            'role' => 'admin'
        ];

        // Create, submit and check form
        $crawler = $this->submitAndCheckInvalidUserCreateForm($fields);
        // Check error messages
        $this->assertEquals(
            1,
            $crawler->filter('span.help-block:contains("Le format de l\'adresse n\'est pas correcte.")')->count()
        );
    }

    public function testExistentEmailCreateUserSubmitWithCredentials()
    {
        // Store input values in $fields array
        $fields = [
            'username' => 'NewGuy',
            'password' => 'pommepomme',
            'password_repeat' => 'pommepomme',
            'email' => 'roleusertest@gmail.com',
            'role' => 'admin'
        ];

        // Create, submit and check form
        $crawler = $this->submitAndCheckInvalidUserCreateForm($fields);
        // Check error messages
        $this->assertEquals(
            1,
            $crawler->filter('span.help-block:contains("Cette adresse mail n\'est pas disponible.")')->count()
        );
    }

    public function testExistentUsernameCreateUserSubmitWithCredentials()
    {
        // Store input values in $fields array
        $fields = [
            'username' => 'RoleAdmin',
            'password' => 'pommepomme',
            'password_repeat' => 'pommepomme',
            'email' => 'roleadmin@gmail.com',
            'role' => 'admin'
        ];

        // Create, submit and check form
        $crawler = $this->submitAndCheckInvalidUserCreateForm($fields);
        // Check error messages
        $this->assertEquals(
            1,
            $crawler->filter('span.help-block:contains("Ce nom d\'utilisateur n\'est pas disponible.")')->count()
        );
    }

    public function testEditUserWithoutCredentials()
    {
        // Create anon user
        $client = static::createClient();

        // Check GET request
        $client->request('GET', '/users/1/edit');
        // Check if 302 statusCode
        $this->assertEquals(
            302,
            $client->getResponse()->getStatusCode()
        );
        // Check redirection
        $ext = $client->getRequest()->getSchemeAndHttpHost();     // Get host name
        $this->assertTrue(                                        // Check if redirected to /login
            $client->getResponse()->isRedirect($ext . "/login")
        );

        // Request /users with all other methods and expect 405 status code
        $this->checkUnauthorizedMethods('/users/1/edit', ['GET', 'HEAD', 'POST'], $client);
    }

    public function testEditUserWithUserCredentials()
    {
        // Create role_user user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleUser',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        // GET request
        $client->request('GET', '/users/1/edit');

        // Check 405
        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode()
        );

        // Request /users with role_user user with all methods
        $this->checkUnauthorizedMethods('/users/1/edit', ['GET', 'HEAD', 'POST'], $client);
    }

    public function testEditUserDisplayWithAdminCredentials()
    {
        // Request /users/id/edit with role_admin user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        $crawler = $client->request('GET', '/users/1/edit');

        // Check statusCode
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        // Check user-form
        $this->checkUserForm($crawler, '/users/1/edit');
    }

    public function testValidUserEditSubmit()
    {
        // Request /users/id/edit
        // Fill form & submit
        // Verify redirection to /users
    }

    private function checkUserForm(Crawler $crawler, string $action)
    {
        // Check form
        $this->checkForm(
            'form',
            $action,
            'post',
            1,
            $crawler
        );

        // Check username input
        $this->checkInput(
            'input#user_username',
            'text',
            'user_username',
            'user[username]',
            1,
            $crawler
        );
        // Check username label
        $this->checkLabel(
            "Nom d'utilisateur",
            "user_username",
            1,
            $crawler
        );

        // Check first password input
        $this->checkInput(
            'input#user_password_first',
            'password',
            'user_password_first',
            'user[password][first]',
            1,
            $crawler
        );
        // Check second password label
        $this->checkLabel(
            "Mot de passe",
            "user_password_first",
            1,
            $crawler
        );

        // Check second password input
        $this->checkInput(
            'input#user_password_second',
            'password',
            'user_password_second',
            'user[password][second]',
            1,
            $crawler
        );
        // Check second password label
        $this->checkLabel(
            "Tapez le mot de passe à nouveau",
            "user_password_second",
            1,
            $crawler
        );

        // Check email input
        $this->checkInput(
            'input#user_email',
            'email',
            'user_email',
            'user[email]',
            1,
            $crawler
        );
        // Check email label
        $this->checkLabel(
            "Adresse email",
            "user_email",
            1,
            $crawler
        );

        // Check role label
        $this->checkLabel(
            "Role",
            "user_role",
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

        // Check submit button
        $buttonValue = $action === '/users/create' ? 'Ajouter' : 'Modifier';
        $this->checkButton(
            'button:contains("' . $buttonValue . '")',
            $buttonValue,
            'submit',
            1,
            $crawler
        );

        // Return Crawler for addtionnal checks
        return $crawler;
    }

    private function createUserForm(
        string $username,
        string $password,
        string $passwordRepeat,
        string $email,
        string $role,
        Crawler $crawler,
        bool $creation
    )
    {
        // Store values in ordered array
        $fields = [
            'user[username]' => $username,
            'user[password][first]' => $password,
            'user[password][second]' => $passwordRepeat,
            'user[email]' => $email,
            'user[role]' => $role
        ];

        // Define button value
        $buttonValue = $creation ? 'Ajouter' : 'Modifier';

        // Create and return hydrated form
        return $this->createForm($buttonValue, $fields, $crawler);
    }

    private function submitAndCheckInvalidUserCreateForm(array $fields)
    {
        // Request /users/create with role_admin user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);
        $crawler = $client->request('GET', '/users/create');

        // Fill&Submit form with invalid repeat input
        $form = $this->createUserForm(
            $fields['username'],
            $fields['password'],
            $fields['password_repeat'],
            $fields['email'],
            $fields['role'],
            $crawler,
            true
        );
        $crawler = $client->submit($form);

        // Check redirection to /users/create
        $ext = $client->getRequest()->getSchemeAndHttpHost();     // Get host name
        $this->assertEquals(
            $ext . '/users/create',
            $crawler->getBaseHref()
        );

        // Check value of fields
        $this->checkValidFieldsValueAfterInvalidUserFormSubmit(
            $fields['username'],
            $fields['email'],
            $fields['role'],
            $crawler
        );

        // return crawler for additional checks
        return $crawler;
    }

    private function checkValidFieldsValueAfterInvalidUserFormSubmit(
        string $username = '',
        string $email = '',
        string $role = '',
        Crawler $crawler
    )
    {
        // Username
        $this->assertEquals(
            $username,
            $crawler->filter('input#user_username')->first()->attr('value')
        );
        // Password
        $this->assertEquals(
            '',
            $crawler->filter('input#user_password_first')->first()->attr('value')
        );
        // Repeat Password
        $this->assertEquals(
            '',
            $crawler->filter('input#user_password_second')->first()->attr('value')
        );
        // Email
        $this->assertEquals(
            $email,
            $crawler->filter('input#user_email')->first()->attr('value')
        );
        // Role
        $this->assertEquals(
            $role,
            $crawler->filter('option[selected]')->first()->attr('value')
        );

    }
}
