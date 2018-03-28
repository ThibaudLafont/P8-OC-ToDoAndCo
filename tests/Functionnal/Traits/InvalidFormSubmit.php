<?php
namespace Tests\Functionnal\Traits;

use Symfony\Bundle\FrameworkBundle\Client;

trait InvalidFormSubmit
{
    private function checkFormRenderWithInvalidValues(
        string $path,
        array $data,
        array $messages
    )
    {
        // Create Client
        $client = $this->createRoleAdminClient();

        // Request form submission
        $form = $this->getForm($client, $path, $data);

        // Store crawler
        $crawler = $client->submit($form);

        // Check total number of errors
        $this->assertEquals(
            count($messages),
            $crawler->filter('span.help-block')->count()
        );
        // Foreach messages
        foreach($messages as $message) {
            // Check presence and content
            $this->assertEquals(
                1,
                $crawler->filter('span.help-block ul li:contains("' . $message . '")')->count()
            );
        }
    }

    private function getForm(
        Client $client,
        string $path,
        array $data
    ) {
        // Build Crawler
        $crawler = $client->request('GET', $path);

        // Select form from submit button
        $submit = $crawler->selectButton(
            strpos($path, 'create') ? 'Ajouter' : 'Modifier'
        );
        return $submit->form($data);
    }
}
