<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/login', function () use ($app) {
    //consultar emprendedores
    $app->post('/', function (Request $request, Response $response, array $args) use($app) {
        require_once '../util/General.php';
        require_once("../util/URL.php");
        require_once("../bo/LoginBO.php");
        $loginBO = new LoginBO($request, $response);
        $res = $loginBO->validarLogin();
        if (General::tieneValor($res, "data")) {
            $res->token = getToken($request, $res->data);
            $response = $response->withHeader(TOKEN, $res->token);
        }
        $response->getBody()->write(json_encode($res));
        return $response;
    });
    $app->post('/api/', function (Request $request, Response $response, array $args) use($app) {
        require_once '../util/General.php';
        require_once("../util/URL.php");
        require_once("../bo/LoginBO.php");
        $loginBO = new LoginBO($request, $response);
        $res = $loginBO->validarLogin();
        if (General::tieneValor($res, "data")) {
            $res->token = getToken($request, $res->data);
            $res->data=null;
        }
        return json_encode($res);
    });
});
