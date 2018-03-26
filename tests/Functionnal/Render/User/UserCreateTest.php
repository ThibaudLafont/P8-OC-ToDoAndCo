<?php
namespace Tests\Functionnal\Render\User;

class UserCreateTest extends UserFormRender
{
    /**
     * Check render of user_form in user_create action
     */
    public function testCreateRenderWithAdmin() {
        // Create Client
        $client = $this->createRoleAdminClient();
        $crawler = $client->request('GET', '/users/create');

        // Check UserForm
        $this->checkUserFormRender('/users/create', $crawler);
    }

}
