<?php
namespace Tests\Functionnal\StatusCode;

class HomepageTest extends StatusCode
{
    /**
     * Request homepage with Anonymous client,
     * Check 302 when GET and 405 other
     */
    public function testHomepageWithAnon() {
        // Request / by GET and check status code
        $this->checkRedirection(
            '/',
            '/login',
            ['GET'],
            $this->createAnonClient()
        );
    }

    /**
     * Request homepage with User client,
     * Check 302 when GET and 405 other
     */
    public function testHomepageWithUser() {
        // Request / by GET and expect 200
        $this->checkResponseStatusCode(
            '/',
            ['GET'],
            200,
            $this->createRoleUserClient()
        );
    }

    /**
     * Request homepage with Admin client,
     * Check 302 when GET and 405 other
     */
    public function testHomepageWithAdmin() {
        // Request / by GET and expect 200 status
        $this->checkResponseStatusCode(
            '/',
            ['GET'],
            200,
            $this->createRoleAdminClient()
        );
    }

    /**
     * Check all unauthorized methods
     */
    public function testTaskDeleteForbiddenMethods()
    {
        $this->checkForbiddenMethodsWithAllUserTypes(
            '/',
            ['GET']
        );
    }
}
