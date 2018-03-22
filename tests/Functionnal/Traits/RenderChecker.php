<?php
namespace Tests\Functionnal\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait RenderChecker
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
    protected function checkLink(string $content, string $href, int $count, Crawler $crawler)
    {
        // Check Link presence
        $this->assertEquals(
            $count,
            $crawler->filter('a:contains("' . $content . '")')->count()
        );


        // Check Link href value
        $this->assertEquals(
            $href,
            $crawler->selectLink($content)->link()->getNode()->getAttribute('href')
        );
    }

    protected function checkImg(string $alt, string $src, int $count, Crawler $crawler)
    {
        // Check presence
        $this->assertEquals(
            $count,
            $crawler->selectImage($alt)->count()
        );

        // Check value of src attribute
        $this->assertEquals(
            $src,
            $crawler->selectImage($alt)->getNode(0)->getAttribute('src')
        );
    }

    protected function checkTitle(string $content, int $level, Crawler $crawler)
    {
        // Check presence & content
        $this->assertEquals(
            1,
            $crawler
                ->filter('h' . $level . ':contains("' . $content . '")')
                ->count()
        );
    }

    protected function checkForm(string $action, string $method, Crawler $crawler)
    {
        // Check form presence & action attribute
        $this->assertEquals(
            1,
            $crawler->filter('form[action="' . $action . '"]')->count()
        );

        // Check Method attribute value
        $this->assertEquals(
            $method,
            $crawler->filter('form[action="' . $action . '"]')->first()->attr('method')
        );
    }

    /**
     * Check presence and consistence of specific label
     *
     * @param string $content   Selector of label
     * @param string $for       For attribute of label
     * @param int $count        Expected number of occurences
     * @param Crawler $crawler  Crawler
     */
    protected function checkLabel(string $content, string $for, int $count, Crawler $crawler)
    {
        // Check presence
        $this->assertEquals(
            $count,
            $crawler->filter('label:contains("' . $content . '")')->count()
        );

        // Check label for
        $this->assertEquals(
            $for,
            $crawler->filter('label:contains("' . $content . '")')->attr('for')
        );
    }

    /**
     * Find and check attributes of specific input
     *
     * @param string $type       Type attribute of input
     * @param string $id         Id of input
     * @param string $name       Name of input
     * @param int $count         Expected number of occurences
     * @param Crawler $crawler   Crawler
     */
    protected function checkInput(
        string $type,
        string $id,
        string $name,
        int $count,
        Crawler $crawler
    )
    {
        // Check input presence and id value
        $this->assertEquals(
            $count,
            $crawler->filter('input#' . $id)->count()
        );

        // Check input type
        $this->assertEquals(
            $type,
            $crawler->filter('input#' . $id)->first()->attr('type')
        );

        // check input name
        $this->assertEquals(
            $name,
            $crawler->filter('input#' . $id)->first()->attr('name')
        );
    }

    protected function checkButton(string $content, string $type, int $count, Crawler $crawler)
    {
        // Check presence and content
        $this->assertEquals(
            $count,
            $crawler->filter('button:contains("' . $content . '")')->count()
        );

        // Check type
        $this->assertEquals(
            $type,
            $crawler->filter('button:contains("' . $content . '")')->attr('type')
        );
    }

}
