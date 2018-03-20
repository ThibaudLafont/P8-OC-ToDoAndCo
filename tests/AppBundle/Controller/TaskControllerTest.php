<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Traits\ControllerTrait;

class TaskControllerTest extends WebTestCase
{
    // Traits
    use ControllerTrait;

    public function testListTasksWithoutCredentials()
    {
        // Request route with non-authenticate user
        $client = static::createClient();
        $client->request('GET', '/tasks');

        // Check if redirected
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
        // Check response status code
        $this->assertEquals(
            302,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testListTasksWithUserCredentials()
    {
        // Request path with authenticated role_user user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleUser',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);

        // Check Task list
        $crawler = $this->checkTaskList($client);

        // Check base links
        $this->checkBaseLinks($crawler, false);
    }

    public function testListTasksWithAdminCredentials()
    {
        // Request path with authenticated role_admin user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'RoleAdmin',
            'PHP_AUTH_PW'   => 'pommepomme',
        ]);

        // Check Task list
        $crawler = $this->checkTaskList($client);

        // Check base links
        $this->checkBaseLinks($crawler, true);

    }

    public function testCreateTaskWithoutCredentials()
    {
        // Request /tasks/create with annon user with all methods
        // Check forbidden
        // Check redirection to /login
    }

    public function testCreateTaskDisplayWithUserCredentials()
    {
        // Same as below with role_user user
        // Does not expect add-user button
    }

    public function testCreateTaskDisplayWithAdminCredentials()
    {
        // Request /tasks/create with auth user
        // Check 200 status code
        // Check add-user button
        // Check task-form
    }

    public function testInvalidCreateTaskWithUserCredentials()
    {
        // Request /tasks/create with role_user user
        // Fill&Submit form with invalid datas
        // Check redirection to /tasks/create
        // Check flash-message
        // Check value of valid fields
        // Check error messages
    }

    public function testInvalidCreateTaskWithAdminCredentials()
    {
        // Same as below with role_admin user
        // Check add-user button
    }

    public function testValidCreateTaskWithUserCredentials()
    {
        // Request /tasks/create with role_user user
        // Fill&Submit form with valid datas
        // Check redirection to /tasks
        // Check flash-message
        // Check presence of new user
    }

    public function testValidCreateTaskWithAdminCredentials()
    {
        // Same as below with role_admin user
        // Check add-user button
    }

    public function testDeleteAnonTaskWithoutCredentials()
    {
        // Request /tasks/id/delete with anon user with all methods
        // Check forbidden
        // Check redirection to /login
    }

    public function testDeleteAnonTaskWithUserCredentials()
    {
        // Request /tasks/id/delete with anon user with all methods
        // Check forbidden
        // Check redirection to /tasks
    }

    public function testDeleteAnonTaskWithAdminCredentials()
    {
        // Request /tasks/id/delete witch user who post task
        // Check redirection to /tasks
        // Check flash-message
        // Check nbre of tasks
    }

    public function testDeleteRelatedTaskWithoutCredentials()
    {
        // Request /tasks/id/delete with anon user with all methods
        // Check forbidden
        // Check redirection to /login
    }

    public function testDeleteRelatedTaskWithBadCredentials()
    {
        // Request /tasks/id/delete witch user does not post task with all methods
        // Check forbidden
        // Check redirection to /tasks
        // Check nbre of tasks
    }

    public function testDeleteRelatedTaskWithCredentials()
    {
        // Request /tasks/id/delete witch user who post task
        // Check redirection to /tasks
        // Check flash-message
        // Check nbre of tasks
    }

    public function testToggleTaskWithoutCredentials()
    {
        // Request /tasks/id/toggle with annon user with all methods
        // Check forbidden
        // Check redirection to /login
    }

    public function testToggleTaskWithUserCredentials()
    {
        // Request /tasks/id/toggle with role_user user
        // Check redirection to /tasks
        // Check flash-message
        // Check if reactive-task button is present
    }

    public function testToggleTaskWithAdminCredentials()
    {
        // Same as below with role_admin user
        // Check add-user button
    }

    public function testEditTaskWithoutCredentials()
    {
        // Request /tasks/id/edit with anon user with all methods
        // Check forbidden
        // Check redirection to /login
    }

    public function testEditTaskWithBadCredentials()
    {
        // Request /tasks/id/edit with non-creator user with all methods
        // Check forbidden
        // Check redirection to /tasks
    }

    public function testEditTaskDisplayWithCredentials()
    {
        // Request /tasks/is/edit with author
        // Check statusCode
        // Check task-form display
    }

    public function testInvalidEditTaskActionWithUserCredentials()
    {
        // Request /tasks/id/edit with role_user author
        // Fill&submit wrong datas
        // Check value of valid fields
        // Check flash-message
        // Check errors
    }

    public function testInvalidEditTaskActionWithAdminCredentials()
    {
        // Same as below with role_admin user
        // Check add-user button
    }

    public function testValidEditTaskActionWithUserCredentials()
    {
        // Request /tasks/id/edit with role_user author
        // Fill&submit valid datas
        // Check redirection to /tasks
        // Check flash-message
        // Check if new task is listed
    }

    public function testValidEditTaskActionWithAdminCredentials()
    {
        // Same as below with role_admin user
        // Check add-user button
    }

    private function checkTaskList(Client $client)
    {
        // Request path
        $crawler = $client->request('GET', '/tasks');

        // Check add-task link
        $this->checkLink(
            "Créer une tâche",
            "/tasks/create",
            1,
            $crawler
        );

        // Check presence of 2 tasks
        $this->assertEquals(
            2,
            $crawler->filter('div.task-parent')->count()
        );

        // TODO : after rework, expect nbre of tasks related to user + anon tasks

        // Check presence of 2 delete buttons
        $this->checkButton(
            'button:contains("Supprimer")',
            'Supprimer',
            '',
            2,
            $crawler
        );

        // TODO : check presence of 2 edit buttons

        // Check one of them is done
        $this->assertEquals(
            1,
            $crawler->filter('button:contains("Marquer non terminée")')->count()
        );

        // Check one of them is pending
        $this->assertEquals(
            1,
            $crawler->filter('button:contains("Marquer comme faite")')->count()
        );

        // Return Crawler for additional checks
        return $crawler;

    }

    private function checkTaskFormDisplay()
    {
        // Check logout button presence
        // Check form presence
        // Check title input&label
        // Check content input&label
        // Check submit button
    }
}
