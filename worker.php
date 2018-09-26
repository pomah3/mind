<?php
	require __DIR__."/vendor/autoload.php";
	
	use Workerman\Worker;
	echo "Start!\n";

	$ws_worker = new Worker("websocket://0.0.0.0:2346");
	$ws_worker->count = 4;

	$ws_worker->onConnect = function($connection) {
		$connection->send(json_encode(["type"=>"begin"]));
	};

	$ws_worker->onMessage = function($connection, $data) {

	};

	$ws_worker->onClose = function($connection) {
		echo "Connection closed\n";
	};

	Worker::runAll();
?>
