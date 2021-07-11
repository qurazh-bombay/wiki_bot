<?php
declare(strict_types = 1);

require_once __DIR__ . '/bootstrap.php';

use App\App;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

try {
	$logger = new Logger('wiki_debug');
	$logger->pushHandler(new StreamHandler(__DIR__ . '/wiki_debug.log', Logger::DEBUG));

	(new App())->run();
} catch (\Throwable $e) {
	$msg = sprintf('message: %s | file: %s | line: %s', $e->getMessage(), $e->getFile(), $e->getLine());
	$logger->critical($msg);

	die();
}
