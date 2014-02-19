<?php

// downloading the two best technologies ever in parallel

require 'vendor/autoload.php';
require 'ErrorHandler.php';

//ErrorHandler::register(-1);
$loop = React\EventLoop\Factory::create();

$files = array(
    'node-v0.6.18.tar.gz' => 'http://nodejs.org/dist/v0.6.18/node-v0.6.18.tar.gz',
    'php-5.4.3.tar.gz' => 'http://downloads.php.net/tyrael/php-5.6.0alpha1.tar.gz',
);

foreach ($files as $file => $url) {
	$readStream = fopen($url, 'r');
	$writeStream = fopen($file, 'w');

	stream_set_blocking($readStream, 0);
	stream_set_blocking($writeStream, 0);

	//try {
		$read = new React\Stream\Stream($readStream, $loop);
		$write = new React\Stream\Stream($writeStream, $loop);
	//} catch (Exception $e) {
	//	print_r($e->getMessage());
	//}

	$read->on('end', function () use ($file, &$files) {
		unset($files[$file]);
		echo "Finished downloading $file\n";
	});

	$read->pipe($write);
}

$everySeconds = 5;
$loop->addPeriodicTimer($everySeconds, function ($timer) use (&$files) {
	if (0 === count($files)) {
		$timer->cancel();
	}

	foreach ($files as $file => $url) {
		$mbytes = filesize($file) / (1024 * 1024);
		$formatted = number_format($mbytes, 3);
		echo "$file: $formatted MiB\n";
	}
});

echo "This script will show the download status every $everySeconds seconds.\n";

$loop->run();