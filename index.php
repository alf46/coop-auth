<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Psr\Log\LoggerInterface;

require __DIR__ . '/vendor/autoload.php';
require "src/middlewares/auth.php";

// Models
require "src/models/user.php";

// Contracts
require "src/contracts/auth.php";
require "src/contracts/user.php";
require "src/contracts/mail.php";

// Services
require "src/services/auth.php";
require "src/services/user.php";
require "src/services/mail.php";

// Controllers
require 'src/controllers/auth.php';
require 'src/controllers/user.php';

// Utils
require "config.php";
require "src/utils/body.php";
require "src/utils/json.php";
require "src/utils/auth.php";
require "src/utils/password.php";
require "storage/db.class.php";
require "storage/dbconn.php";

$app = AppFactory::create();
$app->addRoutingMiddleware();

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$customErrorHandler = function ($request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails, ?LoggerInterface $logger = null) use ($app) {
    $payload = ['error' => $exception->getMessage()];
    $response = $app->getResponseFactory()->createResponse();
    return json($response, $payload);
};

$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);
$app->addBodyParsingMiddleware();

$app->group("/api/v1/auth", function (RouteCollectorProxy $group) {
    $group->post('', \AuthController::class . ':Login');
    $group->post('/forgot', \AuthController::class . ':Forgot');
    $group->post('/recovery', \AuthController::class . ':Recovery');
});

$app->group("/api/v1/user", function (RouteCollectorProxy $group) {
    $group->post('', \UserController::class . ':Create');
    $group->get('/{id}', \UserController::class . ':Get');
})->add(AuthMiddleware::class);

$app->group("/api/v1/me", function (RouteCollectorProxy $group) {
    $group->get('', \AuthController::class . ':Info');
})->add(AuthMiddleware::class);

$app->run();