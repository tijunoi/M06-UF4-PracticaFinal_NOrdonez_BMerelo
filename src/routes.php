<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Controllers\PublicController;

// Routes

/*$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");


    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/

$container = $app->getContainer();

$authenticator = function ($request, \Slim\Middleware\TokenAuthentication $tokenAuth) use ($container){
    $token = $tokenAuth->findToken($request);

    \Src\Controllers\AuthController::validateToken($token,$container['em']);

    //no em guardo el valor i el paso perque aquest middleware no permet cridar a $next per passarli dades, aixi que tornem a cridar a la funcio
};


$app->add(new \Slim\Middleware\TokenAuthentication([
    'path' => '/getAll',
    'secure' => false,
    'authenticator' => $authenticator
]));

$app->get('/', function (Request $request, Response $response, array $args) {

    $data = array();
    $data["msg"] = "LSNote API v0.1";

    return $response->withJson($data,200);
});

$app->get('/getAll',PublicController::class . ':getAllAction');


