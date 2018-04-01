<?php
namespace Tests\Functionnal\Render\Task;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Functionnal\Render\BaseLayout;

class TaskFormRequestTest extends BaseLayout
{
    public function testCreateRender()
    {
        // Create Client and request task_add
        $client = $this->createRoleUserClient();
        $crawler = $client->request('GET', '/tasks/create');

        // Check render
        $this->checkTaskFormBaseLayout('/tasks/create', $crawler);
    }

    public function testCreateRenderWithAdmin()
    {
        // Create Client and request task_add
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/tasks/create');

        // Check render
        $this->checkTaskFormBaseLayout('/tasks/create', $crawler);
    }

    /**
     * @param string $action
     * @param array $data
     *
     * @dataProvider getPersistedTaskValues
     */
    public function testEditRenderWithUser(string $action, array $data)
    {
        // Create Client and request task_add
        $client = $this->createRoleUserClient();
        $crawler = $client->request('GET', $action);

        // Check form render, expect it's hydrated
        $this->checkTaskFormBaseLayout($action, $crawler, $data);
    }

    /**
     * @param string $action
     * @param array $data
     *
     * @dataProvider getPersistedTaskValues
     */
    public function testEditRenderWithAdmin(string $action, array $data)
    {
        // Create Client and request task_add
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', $action);

        // Check form render, expect it's hydrated
        $this->checkTaskFormBaseLayout($action, $crawler, $data);
    }

    public function getPersistedTaskValues()
    {
        return [
            [
                '/tasks/1/edit',
                [
                    'title' => 'Tâche 1',
                    'content'    => 'Contenu de la tâche 1'
                ]
            ],
            [
                '/tasks/2/edit',
                [
                    'title' => 'Tâche 2',
                    'content'    => 'Contenu de la tâche 2'
                ]
            ],
            [
                '/tasks/3/edit',
                [
                    'title' => 'Tâche 3',
                    'content'    => 'Contenu de la tâche 3'
                ]
            ]
        ];
    }

    private function checkTaskFormBaseLayout(
        string $action,
        Crawler $crawler,
        array $data = []
    )
    {
        // Check form
        $this->checkForm(
            $action,
            'post',
            $crawler
        );

        // Check title label
        $this->checkLabel(
            'Titre',
            'task_title',
            1,
            $crawler
        );
        // Check title input
        $this->checkInput(
            'text',
            'task_title',
            'task[title]',
            1,
            $crawler,
            isset($data['title']) ? $data['title'] : null
        );

        // Check content label
        $this->checkLabel(
            'Contenu',
            'task_content',
            1,
            $crawler
        );
        // Check content input
        $this->checkInput(
            'textarea',
            'task_content',
            'task[content]',
            1,
            $crawler,
            isset($data['content']) ? $data['content'] : null
        );

        // Check submit button
        $this->checkButton(
            $action === '/tasks/create' ? 'Ajouter' : 'Modifier',
            'submit',
            1,
            $crawler
        );
    }

}
