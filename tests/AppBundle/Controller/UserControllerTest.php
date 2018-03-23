<?php
namespace Tests\AppBundle\Controller;

use AppBundle\Controller\UserController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends TestCase
{

    public function testInvalidCreate() {

        $post = [
            'username' => 'Test',
            'plainPassword' => [
                'first' => 'aa',
                'second'=> 'aa'
            ],
            'email' => 'email@test.com',
            'role'  => 'admin'
        ];

        $request = new Request([], $post);

        $controller = new UserController();

//        $controller->createAction($request);

    }

}