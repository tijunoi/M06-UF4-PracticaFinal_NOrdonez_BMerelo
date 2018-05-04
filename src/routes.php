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

$app->get('/', function (Request $request, Response $response, array $args) {

    $data = array();
    $data["msg"] = "LSNote API v0.1";

    return $response->withJson($data,200);
});

$app->get('/getAll',PublicController::class . ':getAllAction');


