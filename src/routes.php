<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Controllers\NotesController;

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
    'path' => '/api',
    'passthrough' =>  '/api/getToken',
    'secure' => false,
    'authenticator' => $authenticator
]));


$app->get('/', function (Request $request, Response $response, array $args) {

    $data = array();
    $data["msg"] = "LSNote API v0.1";

    return $response->withJson($data,200);
});

$app->group('/api', function () use ($app) {
    //Auth
    $app->post('/getToken', \Src\Controllers\AuthController::class . ':getTokenAction');

    //Notes
    $app->get('/getAll',NotesController::class . ':getAllAction');

    $app->get('/getPublic',NotesController::class . ':getPublicAction');

    $app->get('getOne',NotesController::class . ':getOneAction');

    $app->post('/insert', NotesController::class . ':insertAction');

    $app->delete('/remove', NotesController::class . ':deleteAction');

    $app->get('/getAllWithTag',NotesController::class . ':getAllWithTagAction');

    $app->put('/addTagOnNote',NotesController::class . ':addTagOnNoteAction');

    $app->delete('/deleteTagOnNote', NotesController::class . ':deleteTagOnNoteAction');

    $app->put('/updateNote', NotesController::class . ':updateNoteAction');

    $app->put('/flipPrivate', NotesController::class . ':flipPrivateAction');

    $app->post('/test', NotesController::class . ':testAction');

});



