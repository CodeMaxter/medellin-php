<?php
require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($loop);
$socket->on('connection', function ($conn) {
	echo "Client connected\n";

    $conn->write("Hello there!\n");
    $conn->write("Welcome to this amazing server!\n");
    $conn->write("Here's a tip: don't say anything.\n");

    $conn->on('data', function ($data) use ($conn) {
        $conn->close();
    });
});
$socket->listen(1337);

$loop->run();