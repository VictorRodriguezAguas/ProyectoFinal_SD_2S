<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/mantenimiento', function () use ($app) {
    $app->post('/consultarDatos', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/MantenimientoBO.php");
        $mantenimientoBO = new MantenimientoBO();
        $res = $mantenimientoBO->consultarDatos();
        return json_encode($res);
    });
    
    $app->post('/grabarDatos', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/MantenimientoBO.php");
        $mantenimientoBO = new MantenimientoBO();
        $res = $mantenimientoBO->grabarDatos();
        return json_encode($res);
    });
    
    require "./mantenimiento/Seguridad.php";
    require "./mantenimiento/Mnt-Programa.php";
    require "./mantenimiento/Mnt-Mentor.php";
});
