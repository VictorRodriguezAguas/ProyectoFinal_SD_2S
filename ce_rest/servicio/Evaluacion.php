<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/evaluacion', function () use ($app) {
    $app->post('/getEvaluacion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EvaluacionBO.php");
        $evaluacionBO = new EvaluacionBO();
        $respuesta = $evaluacionBO->getEvaluacion();
        return json_encode($respuesta);
    });
    
    $app->post('/getEvaluacionXIds', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EvaluacionBO.php");
        $evaluacionBO = new EvaluacionBO();
        $respuesta = $evaluacionBO->getEvaluacionXIds();
        return json_encode($respuesta);
    });
    
    $app->post('/grabarEvaluacion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EvaluacionBO.php");
        $evaluacionBO = new EvaluacionBO();
        $respuesta = $evaluacionBO->grabarEvaluacion();
        return json_encode($respuesta);
    });
});
