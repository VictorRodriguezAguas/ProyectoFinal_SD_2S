<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->group('/dashboard', function () use ($app) {
    $app->post('/getDashboardAdmin', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/DashboardBO.php");
        $dashboardBO = new DashboardBO();
        $respuesta = $dashboardBO->getDashboardAdmin();
        return json_encode($respuesta);
    });
    
    $app->post('/indicadoresXEtapa', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/DashboardBO.php");
        $dashboardBO = new DashboardBO();
        $respuesta = $dashboardBO->getIndicadoresXEtapa();
        return json_encode($respuesta);
    });
    
    $app->post('/getDatosPivot', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/DashboardBO.php");
        $dashboardBO = new DashboardBO();
        $respuesta = $dashboardBO->getDatosPivot();
        return json_encode($respuesta);
    });
    
    $app->post('/getIndicadoresSitucionLaboral', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/DashboardBO.php");
        $dashboardBO = new DashboardBO();
        $respuesta = $dashboardBO->getIndicadoresSitucionLaboral();
        return json_encode($respuesta);
    });
});

