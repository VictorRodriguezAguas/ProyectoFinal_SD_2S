<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->group('/formulario', function () use ($app) {
    $app->post('/getFormulario', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/FormularioBO.php");
        $formularioBO = new FormularioBO();
        $respuesta = $formularioBO->getFormulario();
        return json_encode($respuesta);
    });
    
    $app->post('/getRegistro', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/FormularioBO.php");
        $formularioBO = new FormularioBO();
        $respuesta = $formularioBO->getRegistro();
        return json_encode($respuesta);
    });
    
    $app->post('/grabarRegistro', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/FormularioBO.php");
        $user = getUser($request);
        $formularioBO = new FormularioBO();
        $formularioBO->setUser($user);
        $respuesta = $formularioBO->grabarRegistro();
        return json_encode($respuesta);
    });
});