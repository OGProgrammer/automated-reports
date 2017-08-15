<?php

// Exit status:
// * 0 = success
// * 1 = Mail failed to send

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
use Research\Mailer;
use Research\Config;

$client = new Client();

$subject = "Daily Indexed Pages Report - " . date('m/d/Y g:i a e') . PHP_EOL;
$body = $subject;
$body .= '<table style="width:100%"><tr><th>Site</th><th>Indexed Total</th></tr>';

foreach ($targetSites as $site) {
    $query = urlencode(sprintf("site:%s", $site));
    $crawler = $client->request('GET', sprintf('https://www.google.com/search?q=%s', $query));
    $stats = $crawler->filterXPath("//*[@id='resultStats']")->text();
    $body .= sprintf("<tr><td>%s</td><td>%s</td></tr>", $site, $stats);
}

$body .= '</table>';

$config = new Config();
$mailConfigs = $config->getEmailConfigs();

$mailer = new Mailer($mailConfigs['smtp_host'], $mailConfigs['username'], $mailConfigs['password'], $mailConfigs['smtp_port']);

$to = $config->getBizAdmins();
$from = ['josh@research.com' => 'Joshua Copeland'];

$success = $mailer->sendEmail($to, $from, $subject, $body);

if ($success != count($to)) {
    echo "Failed";
    error_log('Email was not successfully sent to all recipients');
    die(1);
}

echo "Success";
exit(0);