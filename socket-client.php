<?php
require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$client = stream_socket_client('tcp://127.0.0.1:1337');
$conn = new React\Socket\Connection($client, $loop);
//$conn->pipe(new React\Stream\Stream(STDOUT, $loop));
$conn->on('data', function ($data) use ($conn) {
	echo "$data\n";
});
$conn->write("Hello World!\n");

$loop->run();