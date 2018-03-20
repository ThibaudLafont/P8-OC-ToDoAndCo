<?php
namespace Tests\AppBundle\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait ControllerTrait
{
    /**
     * Test a link
     * Check if link present and link target
     *
     * @param int $count       Expected nbre of occurences
     * @param string $content  Content of link
     * @param string $href     Expect href
     * @param Crawler $crawler Actual crawler
     */
    private function checkLink(string $content, string $href, int $count, Crawler $crawler)
    {
        // Check Link presence
        if($this->assertEquals(
            $count,
            $crawler->filter('a:contains("' . $content . '")')->count()
        )) {
            // Check Link href
            $this->assertEquals(
                $href,
                $crawler->selectLink($content)->link()->getNode()->getAttribute('href')
            );
        }
    }

    /**
     * Test if method is authorized
     * Request though Http methods excepts not inquired in $authorized,
     * and check if return status code is 405
     *
     * @param string $uri        Path to request
     * @param array $authorized  Array with all authorized methods
     * @param $client            Client for request execution
     */
    private function checkUnauthorizedMethods(string $uri, array $authorized, $client)
    {
        // All HTTP methods
        $methods = [
            'GET',
            'POST',
            'DELETE',
            'PUT',
            'PATCH',
            'COPY',
            'HEAD',
            'OPTIONS',
            'LINK',
            'UNLINK',
            'PURGE',
            'LOCK',
            'UNLOCK',
            'PROPFIND',
            'VIEW'
        ];

        // Loop on every available methods
        foreach($methods as $method) {
            // Check if method is unauthorized
            if(!in_array($method, $authorized)){
                // Request path with current method
                $client->request($method, $uri);
                // Check if 405 status code
                $this->assertEquals(405, $client->getResponse()->getStatusCode());
            }
        }
    }

    /**
     * Verify presence and attributes of specific form
     *
     * @param string $filter    Selector of form
     * @param string $action    Action attribute value of form
     * @param string $method    Method attribute value of form
     * @param int $count        Expected occurences of form
     * @param Crawler $crawler  Crawler of response
     */
    private function checkForm(
        string $filter,
        string $action,
        string $method,
        int $count,
        Crawler $crawler
    )
    {
        // Check Form presence
        $this->assertEquals(
            $count,
            $crawler->filter($filter)->count()
        );
        // Check Action attribute value
        $this->assertEquals(
            $action,
            $crawler->filter($filter)->first()->attr('action')
        );
        // Check Method attribute value
        $this->assertEquals(
            $method,
            $crawler->filter($filter)->first()->attr('method')
        );
    }

    /**
     * Find and check attributes of specific input
     *
     * @param string $filter     Selector for input
     * @param string $type       Type attribute of input
     * @param string $id         Id of input
     * @param string $name       Name of input
     * @param int $count         Expected number of occurences
     * @param Crawler $crawler   Crawler
     */
    private function checkInput(
        string $filter,
        string $type,
        string $id,
        string $name,
        int $count,
        Crawler $crawler
    )
    {
        // Check input presence
        $this->assertEquals(
            $count,
            $crawler->filter($filter)->count()
        );
        // Check input type
        $this->assertEquals(
            $type,
            $crawler->filter($filter)->first()->attr('type')
        );
        // Check input id
        $this->assertEquals(
            $id,
            $crawler->filter($filter)->first()->attr('id')
        );
        // check input name
        $this->assertEquals(
            $name,
            $crawler->filter($filter)->first()->attr('name')
        );
    }

    /**
     * Check presence and consistence of specific label
     *
     * @param string $filter    Selector of label
     * @param string $for       For attribute of label
     * @param int $count        Expected number of occurences
     * @param Crawler $crawler  Crawler
     */
    private function checkLabel(string $filter, string $for, int $count, Crawler $crawler)
    {
        // Check presence
        $this->assertEquals(
            $count,
            $crawler->filter('label:contains("' . $filter . '")')->count()
        );
        // Check label for
        $this->assertEquals(
            $for,
            $crawler->filter('label:contains("' . $filter . '")')->attr('for')
        );
    }

    private function checkButton(string $filter, string $value, string $type, int $count, Crawler $crawler)
    {
        // Check presence
        $this->assertEquals(
            $count,
            $crawler->filter($filter)->count()
        );
        // Check button value
        $this->assertEquals(
            $value,
            $crawler->filter($filter)->getNode(0)->nodeValue
        );
        // Check type
        $this->assertEquals(
            $type,
            $crawler->filter($filter)->attr('type')
        );
    }

    /**
     * Create and hydrate form with fields values,
     * Return build form ready to be submitted
     *
     * @param string $buttonValue                  Value of submit button
     * @param array $fields                        Fields and values in array
     * @param Crawler $crawler                     Crawler
     *
     * @return \Symfony\Component\DomCrawler\Form
     */
    private function createForm(string $buttonValue, array $fields, Crawler $crawler)
    {
        // Find submit button
        $buttonCrawlerNode = $crawler->selectButton($buttonValue);

        // Inquire input values
        return $buttonCrawlerNode->form($fields);
    }

    private function checkBaseLinks(Crawler $crawler, bool $admin)
    {
        // Define if links should be present
        $count = $admin ? 1 : 0;

        // Check list-user button
        $this->checkLink(
            "Liste des utilisateurs",
            "/users",
            $count,
            $crawler
        );
        // Check add-user button
        $this->checkLink(
            "Créer un utilisateur",
            "/users/create",
            $count,
            $crawler
        );
        // Check if logout button is present
        $this->checkLink(
            "Se déconnecter",
            "/logout",
            1,
            $crawler
        );

    }

}
