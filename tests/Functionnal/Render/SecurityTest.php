<?php
namespace Tests\Functionnal\Render;

use Symfony\Component\DomCrawler\Crawler;

class SecurityTest extends BaseLayout
{

    public function testAnonLoginRender()  {
        // Create Client & request
        $client = $this->createAnonClient();
        $crawler = $client->request('GET', '/login');

        // Check userBaseLayout
        $this->checkAnonBaseLayout($crawler);

        // Check loginBaseLayout
        $this->checkLoginBaseLayout($crawler);
    }

    private function checkLoginBaseLayout(Crawler $crawler) {
        // Check Main_Img img
        $this->checkImg(
            'todo list',
            '/img/todolist_homepage.jpg',
            1,
            $crawler
        );

        // Check form
        $this->checkForm(
            '/login_check',
            'post',
            $crawler
        );

        // Check username label
        $this->checkLabel(
            'Nom d\'utilisateur :',
            'username',
            1,
            $crawler
        );
        // Check username input
        $this->checkInput(
            'text',
            'username',
            '_username',
            1,
            $crawler
        );

        // Check pwd label
        $this->checkLabel(
            'Mot de passe :',
            'password',
            1,
            $crawler
        );
        // Check pwd input
        $this->checkInput(
            'password',
            'password',
            '_password',
            1,
            $crawler
        );

        // Check submit button
        $this->checkButton(
            'Se connecter',
            'submit',
            1,
            $crawler
        );
    }
}
