<?php
namespace Tests\Functionnal\Traits;

trait ClientCreator
{

    /**
     * Create and return Anonymous client
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function createAnonClient(){

        return $this->initClient([]);

    }

    /**
     * Create and return ROLE_USER client
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function createRoleUserClient(){

        // Create&Return client
        return $this->initClient(
            // Inject role_user credentials
            $this->normalizeCredentials(
                'RoleUser',
                'pommepomme'
            )
        );

    }

    /**
     * Create and return ROLE_ADMIN client
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public function createRoleAdminClient(){

        // Create&Return client
        return $this->initClient(
        // Inject role_admin credentials
            $this->normalizeCredentials(
                'RoleAdmin',
                'pommepomme'
            )
        );

    }

    /**
     * Normalize credentials for client creation
     * Structure of array correspond to second parameter of static::createClient
     *
     * @param string $username Username of user
     * @param string $password Password of user
     * @return array           Normalized array for static::createClient
     */
    private function normalizeCredentials(string $username, string $password) {

        return [
            'PHP_AUTH_USER' => $username,
            'PHP_AUTH_PW'   => $password,
        ];

    }

    /**
     * Init a client with given credentials
     *
     * @param array $credentials                       Have to follow good structure
     * @return \Symfony\Bundle\FrameworkBundle\Client  Created client
     */
    private function initClient(array $credentials) {

        return static::createClient([], $credentials);

    }

}
