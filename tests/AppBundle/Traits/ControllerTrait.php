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
        $this->assertEquals(
            $count,
            $crawler->filter('a:contains("' . $content . '")')->count()
        );

        // Check Link href
        $this->assertEquals(
            $href,
            $crawler->selectLink($content)->link()->getNode()->getAttribute('href')
        );
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

    private function createForm(string $buttonValue, array $fields, Crawler $crawler)
    {
        // Find submit button
        $buttonCrawlerNode = $crawler->selectButton($buttonValue);

        // Inquire input values
        return $buttonCrawlerNode->form($fields);
    }

}
