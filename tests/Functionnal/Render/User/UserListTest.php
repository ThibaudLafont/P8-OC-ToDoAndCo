<?php
namespace Tests\Functionnal\Render\User;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Functionnal\Render\BaseLayout;

class UserListTest extends BaseLayout
{
    /**
     * Check render of user_list
     */
    public function testUserListListRender() {
        // Create role_admin Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/users');

        // Check admin base layout
        $this->checkAdminBaseLayout($crawler);

        // Check user_list base layout
        $this->checkUserListBaseLayout($crawler);
    }

    public function testClickUserEditLink()
    {
        // Create role_admin Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/users');

        // Check admin base layout
        $this->checkAdminBaseLayout($crawler);

        // Find user_edit links
        $link = $crawler->selectLink('Edit')->first()->link();

        // Test to click first link
        $crawler = $client->click($link);

        // Test go to user_edit
        $this->assertEquals(
            $client->getRequest()->getSchemeAndHttpHost() . '/users/1/edit',
            $crawler->getUri()
        );
    }

    private function checkUserListBaseLayout(Crawler $crawler)
    {
        // Check list-tasks button
        $this->checkLink(
            'Liste des tâches',
            '/tasks',
            1,
            $crawler
        );

        // Check add-user button
        $this->checkLink(
            'Créer un utilisateur',
            '/users/create',
            1,
            $crawler
        );

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

        // test_db contain 2 users
        $this->assertEquals(
            2,
            $crawler->filter('a:contains("Edit")')->count()
        );
    }
}
