<?php
namespace Tests\Functionnal\Render\User\Form;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Functionnal\Render\BaseLayout;

abstract class UserFormRender extends BaseLayout
{
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