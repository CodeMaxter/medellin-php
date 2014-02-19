<?php

// http client making a request to github api

require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$dnsResolverFactory = new React\Dns\Resolver\Factory();
$dnsResolver = $dnsResolverFactory->createCached('8.8.8.8', $loop);

$factory = new React\HttpClient\Factory();
$client = $factory->create($loop, $dnsResolver);

$request = $client->request('GET', 'https://api.github.com/repos/reactphp/react/commits');
$request->on('response', function ($response) {
    $buffer = '';

    $response->on('data', function ($data) use (&$buffer) {
        $buffer .= $data;
        echo ".";
    });

    $response->on('end', function () use (&$buffer) {
        $decoded = json_decode($buffer, true);
		echo "\nLast 10 commits on react:\n";
		for ($index = 0; $index < 10; ++$index) {
			$latest = $decoded[$index]['commit'];
			$author = $latest['author']['name'];
			$date = date('F j, Y', strtotime($latest['author']['date']));

			echo "\n";
			echo "{$author} on {$date}\n";
			echo "{$latest['message']}\n";
		}
    });
});

$request->on('end', function ($error, $response) {
    echo $error;
});

$request->end();

$loop->run();

