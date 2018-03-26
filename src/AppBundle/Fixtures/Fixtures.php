<?php
namespace AppBundle\Fixtures;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class Fixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Parse DataSet and loadUsers()
        $users = Yaml::parse(file_get_contents(__DIR__ . '/DataSet/User.yaml'));
        $this->loadUsers($users, $manager);

        // Flush to be able to fetch task owner
        $manager->flush();

        // Parse DataSet and loadTasks()
        $tasks = Yaml::parse(file_get_contents(__DIR__ . '/DataSet/Task.yaml'));
        $this->loadTasks($tasks, $manager);

        // Flush
        $manager->flush();
    }

    /**
     * Load Users form parsed YAML file
     *
     * @param array $users           Normalized Users
     * @param ObjectManager $manager
     */
    private function loadUsers(array $users, ObjectManager $manager)
    {
        // loop on every User
        foreach($users as $username => $attributes)
        {
            // Create and hydrate User entity
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($attributes['email']);
            $user->setPlainPassword($attributes['password']);
            $user->setRole($attributes['role']);

            // Persist User
            $manager->persist($user);
        }
    }

    /**
     * Load Tasks from parsed YAML file
     *
     * @param array $tasks           Normalized Tasks
     * @param ObjectManager $manager
     */
    private function loadTasks(array $tasks, ObjectManager $manager)
    {
        // loop on every entry
        foreach($tasks as $title => $attributes){
            // Create new DateTime from string
            $createdAt = new \DateTime($attributes['created_at']);

            // Create Task Entity and hydrate
            $task = new Task();
            $task->setTitle($title);
            $task->setContent($attributes['content']);
            $task->toggle($attributes['is_done']);
            $task->setCreatedAt($createdAt);

            // Check if Task is anonymous
            if(!is_null($attributes['user'])) {
                // If not fetch User in DB
                $user = $manager->getRepository(User::class)
                    ->findOneBy(['username' => $attributes['user']]);

                // Set User to Task
                $task->setUser($user);
            }

            // Persist new Task
            $manager->persist($task);
        }
    }
}
