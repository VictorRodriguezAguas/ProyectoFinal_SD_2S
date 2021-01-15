<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/emprendimiento', function () use ($app) {
    //consultar emprendedores
    $app->post('/insertar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->insertarEmprendimiento();
        return json_encode($respuesta);
    });
    
    $app->post('/insertarPorPasos', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $user = getUser($request);
        $emprendimientoBO->setUser($user);
        $respuesta = $emprendimientoBO->insertarEmprendimientoPorPasos();
        return json_encode($respuesta);
    });
    
    $app->post('/getRedesSociales', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->getRedesSocialesEmprendimiento();
        return json_encode($respuesta);
    });
    
    $app->post('/getLugarComercializacion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->getLugarComercializacionXEmprendimiento();
        return json_encode($respuesta);
    });
    
    $app->post('/getCanalesVentas', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->getCanalesVentasXEmprendimiento();
        return json_encode($respuesta);
    });
    
    $app->post('/getEmpresaDelivery', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->getEmpresaDeliveryXEmprendimiento();
        return json_encode($respuesta);
    });
    
    $app->post('/getTipoFinancimiento', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->getTipoFinancimientoXEmprendimiento();
        return json_encode($respuesta);
    });
    
    $app->post('/getActividadesComplementarias', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->getActividadesComplementariasXEmprendimiento();
        return json_encode($respuesta);
    });
    
    $app->post('/getEmprendimiento', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendimientoBO.php");
        $emprendimientoBO = new EmprendimientoBO();
        $respuesta = $emprendimientoBO->getEmprendimiento();
        return json_encode($respuesta);
    });
});

