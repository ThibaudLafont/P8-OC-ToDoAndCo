<?php
namespace Tests\Functionnal\Render\Task;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Functionnal\Render\BaseLayout;
use Tests\Functionnal\Traits\FormSubmit;

class TaskFormSubmitTest extends BaseLayout
{

    // Traits
    use FormSubmit;

    /**
     * @param array $data
     * @param array $messages
     *
     * @dataProvider taskInvalidValues
     */
    public function testCreateWithInvalidValues(array $data, array $messages)
    {
        $this->checkFormRenderWithInvalidValues(
            '/tasks/create',
            $data,
            $messages
        );
    }

    /**
     * @param array $data
     * @param array $messages
     *
     * @dataProvider taskInvalidValues
     */
    public function testEditWithInvalidValues(array $data, array $messages)
    {
        $this->checkFormRenderWithInvalidValues(
            '/tasks/1/edit',
            $data,
            $messages
        );
    }

    public function testValidTaskCreation()
    {
        // Test TaskForm valid submission
        $this->submitTaskFormWithValid('/tasks/create');
    }

    public function testValidTaskEdition()
    {
        // Test TaskForm valid submission
        $this->submitTaskFormWithValid('/tasks/7/edit');
    }

    public function testValidTaskDelete()
    {
        // Create client
        $client = $this->createRoleUserClient();
        $client->followRedirects(true);

        $crawler = $client->request('POST', '/tasks/7/delete');

        // Check flash message
        $this->assertEquals(
            1,
            $crawler->filter('div.alert-success')->count()
        );
    }

    private function submitTaskFormWithValid(string $path)
    {
        // Create client
        $client = $this->createRoleUserClient();
        $client->followRedirects(true);

        $data = [
            'task[title]' => 'Title',
            'task[content]' => 'Content'
        ];

        // Check valid submit
        $this->checkValidFormSubmit(
            $client,
            $path,
            $data
        );
    }

    public function taskInvalidValues()
    {
        // Check Not Blank
        return [
            [
                'task' => [
                    'task[title]' => '',
                    'task[content]' => ''
                ],
                'messages' => [
                    'Vous devez saisir un titre.',
                    'Vous devez saisir du contenu.'
                ]
            ]
        ];
    }

}
