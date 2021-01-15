<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/foro', function () use ($app) {
    //consultar emprendedores

    $app->post('/getForo', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/ForoBO.php");
        $foroBO = new ForoBO();
        $foroBO->setUser(getUser($request));
        $respuesta = $foroBO->getForo();
        return json_encode($respuesta);
    });
    
    $app->post('/getForos', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/ForoBO.php");
        $foroBO = new ForoBO();
        $foroBO->setUser(getUser($request));
        $respuesta = $foroBO->getForos();
        return json_encode($respuesta);
    });
    
    $app->post('/grabarForo', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/ForoBO.php");
        $foroBO = new ForoBO();
        $foroBO->setUser(getUser($request));
        $respuesta = $foroBO->grabarForo();
        return json_encode($respuesta);
    });
    
    $app->post('/grabarRespuesta', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/ForoBO.php");
        $foroBO = new ForoBO();
        $foroBO->setUser(getUser($request));
        $respuesta = $foroBO->grabarRespuesta();
        return json_encode($respuesta);
    });
    
    $app->post('/getRespuestas', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/ForoBO.php");
        $foroBO = new ForoBO();
        $foroBO->setUser(getUser($request));
        $respuesta = $foroBO->getRespuestasForo();
        return json_encode($respuesta);
    });
});

