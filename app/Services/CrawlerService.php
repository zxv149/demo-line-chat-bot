<?php
namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    /** @var Client  */
    private $client;

    public function __construct()
    {
        $this->client = app(Client::class);
    }

    /**
     * @param string $path
     * @return Crawler
     */
    public function getOriginalData(string $path): Crawler
    {
        $content = $this->client->get($path)->getBody()->getContents();
        $crawler = new Crawler();

        $crawler->addHtmlContent($content);

        return $crawler;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    public function getNewAnimationFromBaHa(Crawler $crawler): array
    {
        $target = $crawler->filterXPath('//div[contains(@class, "newanime")]')
            ->each(function (Crawler $node) {
                $date = $this->getDateForNewAnimationFromBaHa($node);
                $link = $this->getLinkForNewAnimationFromBaHa($node);
                $image = $this->getImageForNewAnimationFromBaHa($node);
                $info = $this->getInfoForNewAnimationFromBaHa($node);

                $response = [
                    'date' => array_first($date),
                    'directUri' => array_first($link),
                    'imagePath' => array_first($image),
                    'label' => array_first($info),
                ];
                return in_array(null, array_values($response), true) ? null : $response;
            });
        $target = array_filter($target, function ($d) {
            return null !== $d;
        });
        return $target;
    }

    private function getDateForNewAnimationFromBaHa(Crawler $node)
    {
        return $node->filterXPath('//span[contains(@class, "newanime-date")]')
            ->each(function (Crawler $node) {
                return $node->text();
            });
    }

    private function getLinkForNewAnimationFromBaHa(Crawler $node)
    {
        return $node->filterXPath('//a[contains(@class, "newanime__content")]')
            ->evaluate('string(@href)');
    }

    private function getImageForNewAnimationFromBaHa(Crawler $node)
    {
        return $node->filterXPath('//img[contains(@class, "lazyload")]')
            ->evaluate('string(@data-src)');
    }

    private function getInfoForNewAnimationFromBaHa(Crawler $node)
    {
        return $node->filterXPath('//p[contains(@class, "newanime-title")]')
            ->each(function (Crawler $node) {
                return $node->text();
            });
    }
}
