<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/rubrica', function () use ($app) {
    $app->post('/rubricaEvaluacion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/RubricaBO.php");
        $rubricaBO = new RubricaBO();
        $respuesta = $rubricaBO->getRubricaEvaluacion();
        return json_encode($respuesta);
    });
    
    $app->post('/getMensaje', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/RubricaBO.php");
        $rubricaBO = new RubricaBO();
        $respuesta = $rubricaBO->getMensaje();
        return json_encode($respuesta);
    });
});
