<?php
namespace Tests\Functionnal\Render\Task;

use Symfony\Component\DomCrawler\Crawler;
use Tests\Functionnal\Render\BaseLayout;
use Tests\Functionnal\Traits\InvalidFormSubmit;

class TaskFormSubmitTest extends BaseLayout
{

    // Traits
    use InvalidFormSubmit;

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
