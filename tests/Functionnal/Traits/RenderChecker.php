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

}
