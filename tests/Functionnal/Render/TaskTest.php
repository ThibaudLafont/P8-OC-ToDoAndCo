<?php
namespace Tests\Functionnal\Render;

use Symfony\Component\DomCrawler\Crawler;

class TaskTest extends BaseLayout
{

    /**
     * Test render of Task list with role_user
     */
    public function testListRenderWithUser() {
        // Create Client and request
        $client = $this->createRoleUserClient();
        $crawler = $client->request('GET', '/tasks');

        // Check base display
        $this->checkUserBaseLayout($crawler);

        // Check task_list base layout
        $this->checkListBaseLayout($crawler);

        // We know User own 1 Task and should not be able to edit Anon one
        // => attempt 1 delete button
        $this->checkDeleteForm(1, $crawler);

    }

    /**
     * Test render Task list with role_admin
     */
    public function testListRenderWithAdmin(){
        // Create Client and request /tasks
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/tasks');

        // Check base display
        $this->checkUserBaseLayout($crawler);

        // Check task_list base layout
        $this->checkListBaseLayout($crawler);

        // We know User own 1 Task and should be able to edit Anon one
        // => attempt 2 delete button
        $this->checkDeleteForm(2, $crawler);
    }

    public function testEditRender() {}

    public function testCreateRenderWithUser() {}
    public function testCreateRenderWithAdmin(){}

    /**
     * Task list base layout check
     *
     * @param Crawler $crawler Crawler to check
     */
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

        // Get Tasks
        $tasks = $crawler->filter('div.task-item');
        // task_item div count (test base contain 3 tasks)
        $this->assertEquals(
            3,
            $tasks->count()
        );

        // Check task-item render
        $this->checkTaskItemDiv($tasks);
    }

    /**
     * Check task item render from filtered Crawler
     *
     * @param $tasks Filtered Crawler (containing task items)
     */
    private function checkTaskItemDiv($tasks) {
        // Loop on every task-item
        $tasks->each(function($node, $i){
            // Check Title
            $this->assertEquals(
                1,
                $node->filter('h4')->count()
            );
            // Check Edit Link
            $this->assertEquals(
                1,
                $node->filter('a')->count()
            );
            // Check Author username display
            $this->assertEquals(
                1,
                $node->filter('em.author')->count()
            );
            // Check Content display
            $this->assertEquals(
                1,
                $node->filter('p.task-content')->count()
            );
            // Check toggle form presence
            $this->assertEquals(
                1,
                $node->filter('form.toggle-task')->count()
            );
        });
    }

    /**
     * Check presence and number of delete forms in Task list
     *
     * @param int $count       Attempted number of delete forms
     * @param Crawler $crawler Crawler to check
     */
    private function checkDeleteForm(int $count, Crawler $crawler)
    {
        // Check Nbre of delete forms
        $this->assertEquals(
            $count,
            $crawler->filter('button:contains("Supprimer")')->count()
        );
    }

    private function checkCreateBaseLayout() {}

}
