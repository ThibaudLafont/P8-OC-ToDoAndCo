<?php
namespace Tests\Functionnal\Render;

use Symfony\Component\DomCrawler\Crawler;

class HomepageTest extends BaseLayout
{

    /**
     * Check rendering of response when role_user request Homepage
     */
    public function testHomepageUserRender() {
        // Create Client
        $client = $this->createRoleUserClient();
        $crawler = $client->request('GET', '/');

        // Execute userBaseLayout
        $this->checkUserBaseLayout($crawler);

        // Execute homepageBaseLayout
        $this->checkHomepageBaseLayout($crawler);
    }

    /**
     * Check rendering of response when role_admin request Homepage
     */
    public function testHomepageAdminRender() {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/');

        // Execute userBaseLayout
        $this->checkAdminBaseLayout($crawler);

        // Execute homepageBaseLayout
        $this->checkHomepageBaseLayout($crawler);

        // As Admin user, attempt list_users link
        $this->checkLink(
            'Liste des utilisateurs',
            '/users',
            1,
            $crawler
        );
    }

    private function checkHomepageBaseLayout(Crawler $crawler) {
        // Check title
        $this->checkTitle(
            "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !",
            1,
            $crawler
        );

        // Check Main_Img img
        $this->checkImg(
            'todo list',
            '/img/todolist_homepage.jpg',
            1,
            $crawler
        );

        // Check add-task link
        $this->checkLink(
            'Créer une nouvelle tâche',
            '/tasks/create',
            1,
            $crawler
        );

        // Check list_tasks link
        $this->checkLink(
            'Consulter la liste des tâches à faire',
            '/tasks',
            1,
            $crawler
        );

        // Check list_ended_tasks link
        $this->checkLink(
            'Consulter la liste des tâches terminées',
            '',
            1,
            $crawler
        );
    }
}
