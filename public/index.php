<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Ustawienie środowiska i debugowania
$env = $_SERVER['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

if ($debug) {
    umask(0000);
    Debug::enable();
}

// Tworzenie instancji Symfony Kernel
$kernel = new Kernel($env, $debug);
$request = Request::createFromGlobals();

// Przekazanie żądania do Symfony i uzyskanie odpowiedzi
$response = $kernel->handle($request);
$response->send();

// Zakończenie obsługi żądania
$kernel->terminate($request, $response);
