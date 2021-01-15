<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->group('/dashboardMentor', function () use ($app) {
    $app->post('/getResumen', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/DashboardMentorBO.php");
        $dashboardBO = new DashboardMentorBO();
        $dashboardBO->setUser(getUser($request));
        $respuesta = $dashboardBO->getResumenMentor();
        return json_encode($respuesta);
    });
});

