<?php
namespace Tests\Functionnal\StatusCode;

class Homepage extends StatusCode
{

    /**
     * Request homepage with Anonymous client,
     * Check 302 when GET and 405 other
     */
    public function testHomepageWithAnon() {

        // Create anon client
        $client = $this->createAnonClient();

        // Request / by GET and check status code
        $this->checkRedirection(
            '/',
            '/login',
            'GET',
            $client
        );

        // Check all other methods and expect 405
        $this->checkForbiddenMethods(
            '/',
            ['GET'],
            $client
        );

    }

    /**
     * Request homepage with User client,
     * Check 302 when GET and 405 other
     */
    public function testHomepageWithUser() {

        // Create User client
        $client = $this->createRoleUserClient();

        // Request / by GET and expect 200
        $this->checkResponseStatusCode(
            '/',
            'GET',
            200,
            $client
        );

        // Request / by other methods and expect 405
        $this->checkForbiddenMethods(
            '/',
            ['GET'],
            $client
        );

    }

    /**
     * Request homepage with Admin client,
     * Check 302 when GET and 405 other
     */
    public function testHomepageWithAdmin() {

        // Create Admin client
        $client = $this->createRoleAdminClient();

        // Request / by GET and expect 200 status
        $this->checkResponseStatusCode(
            '/',
            'GET',
            200,
            $client
        );

        // Request / by other methods and expect 405
        $this->checkForbiddenMethods(
            '/',
            ['GET'],
            $client
        );

    }

}