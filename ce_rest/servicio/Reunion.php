<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->group('/reunion', function () use ($app) {    
    $app->post('/getReunionxAgenda', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ReunionBO.php");
        $reunionBO = new ReunionBO();
        $data = $reunionBO->getMeetingById();
        return json_encode($data);
    });
    
    $app->post('/iniciar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ReunionBO.php");
        $reunionBO = new ReunionBO();
        $data = $reunionBO->insertarReunion();
        return json_encode($data);
    });
    
    $app->post('/actualizar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ReunionBO.php");
        $reunionBO = new ReunionBO();
        $data = $reunionBO->actualizarReunion();
        return json_encode($data);
    });
});

