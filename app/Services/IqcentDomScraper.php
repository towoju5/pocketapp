<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class IqcentDomScraper
{
    public function getTickData(string $symbol, string $from, string $to)
    {
        $url = "https://iqcent.com/trade-api/api/ticks?symbol={$symbol}&from={$from}&to={$to}";

        $client = HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/*,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br, zstd',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Cache-Control' => 'max-age=0',
                'DNT' => '1',
                'Upgrade-Insecure-Requests' => '1',
            ]
        ]);

        $response = $client->request('GET', $url);
        $content = $response->getContent(); // If it's HTML or JSON

        // If HTML parsing is needed:
        // $crawler = new Crawler($content);
        // $crawler->filter('some-css-selector')->each(fn($node) => ...);

        return $content; // or parse as JSON: json_decode($content, true)
    }
}
