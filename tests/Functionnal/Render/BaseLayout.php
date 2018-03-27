<?php
namespace Tests\Functionnal\Render;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Functionnal\Traits\ClientCreator;
use Tests\Functionnal\Traits\RenderChecker;

abstract class BaseLayout extends WebTestCase
{
    // Traits
    use ClientCreator;
    use RenderChecker;

    /**
     * Base layout for anon user
     */
    protected function checkAnonBaseLayout(Crawler $crawler)  {
        // Check To Do List App link
        $this->checkLink(
            'To Do List app',
            '/',
            1,
            $crawler
        );

        // Check OC logo img
        $this->checkImg(
            'OpenClassrooms',
            '/img/Logo_OpenClassrooms.png',
            1,
            $crawler
        );

        // Check Copyright
        $this->assertEquals(
            1,
            $crawler->filter('p:contains("Copyright © OpenClassrooms")')->count()
        );
    }

    /**
     * Base layout for role_user user
     */
    protected function checkUserBaseLayout(Crawler $crawler)  {
        // Check anonBaseLayout
        $this->checkAnonBaseLayout($crawler);

        // if task route, attempt homepage link
        if(strpos($crawler->getUri(), 'tasks')) {
            // homepage link
            $this->checkLink(
                'Accueil',
                '/',
                1,
                $crawler
            );
        // Else attempt create_task link
        } else {
            // create_task link
            $this->checkLink(
                'Liste des tâches',
                '/tasks',
                1,
                $crawler
            );
        }

        // Check Logout link
        $this->checkLink(
            'Se déconnecter',
            '/logout',
            1,
            $crawler
        );
    }

    /**
     * Base layout for role_admin user
     */
    protected function checkAdminBaseLayout(Crawler $crawler) {
        // Check userBaseLayout
        $this->checkUserBaseLayout($crawler);

        // if users_list route, expect user_create link
        if(preg_match('/(users)$/', $crawler->getUri())) {
            // homepage link
            $this->checkLink(
                'Créer un utilisateur',
                '/users/create',
                1,
                $crawler
            );
        // Else attempt users_list link
        } else {
            // users_list link
            $this->checkLink(
                'Liste des utilisateurs',
                '/users',
                1,
                $crawler
            );
        }
    }

}