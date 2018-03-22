<?php
namespace Tests\Functionnal\Render;

use Symfony\Component\DomCrawler\Crawler;

class TaskTest extends BaseLayout
{

    public function testListRenderWithUser() {
        // Create Client
        $client = $this->createRoleUserClient();
        $crawler = $client->request('GET', '/tasks');

        // Check base display
        $this->checkUserBaseLayout($crawler);

        // Check task_list base layout
        $this->checkListBaseLayout($crawler);
    }
    public function testListRenderWithAdmin(){}

    public function testEditRenderWithTaskOwner() {}

    public function testCreateRenderWithUser() {}
    public function testCreateRenderWithAdmin(){}

    private function checkListBaseLayout(Crawler $crawler) {
        // Main_Img
        $this->checkImg(
            'todo list',
            '/img/todolist_content.jpg',
            1,
            $crawler
        );
        
        // add_task link
        $this->checkLink(
            'Créer une tâche',
            '/tasks/create',
            1,
            $crawler
        );
        // task_item div count (test base contain 2 tasks)
        // Task item div content
    }
    private function checkCreateBaseLayout() {}

    private function checkTaskItemDiv() {
        // Check title
        // Check edit link
        // Check content
        // Check toogle_task form
            // Check submit button (test base contain one task done and one pending)
        // Check delete_task form
            // Check presence/non-presence
            // Check submit button
    }

}
