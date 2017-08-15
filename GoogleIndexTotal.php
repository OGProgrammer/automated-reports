<?php

$targetSites = [
    'developer.research.com',
    'sites.research.com',
    'pt.research.com',
    '*.research.com',
    'research.com',
    'www.research.com'
];

require 'vendor/autoload.php';

use Goutte\Client;

$client = new Client();

echo "Daily Indexed Pages Report - " . date('m/d/Y g:i a e') . PHP_EOL;

foreach ($targetSites as $site) {
    $query = urlencode(sprintf("site:%s", $site));
    $crawler = $client->request('GET', sprintf('https://www.google.com/search?q=%s', $query));
    $stats = $crawler->filterXPath("//*[@id='resultStats']")->text();
    echo sprintf("site: %s \tgoogle indexed pages: %s \n", $site, $stats);
}
