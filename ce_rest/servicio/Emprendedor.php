<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/emprendedor', function () use ($app) {
    //consultar emprendedores
    $app->post('/emprendedoresCE', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendedorBO.php");
        $emprendedorBO = new EmprendedorBO();
        $data = $emprendedorBO->getEmprendedoresCE();
        return json_encode($data);
    });
    
    $app->post('/insertar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendedorBO.php");
        $emprendedorBO = new EmprendedorBO();
        $respuesta = $emprendedorBO->insertarEmprendedor();
        return json_encode($respuesta);
    });
    
    $app->post('/actualizar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendedorBO.php");
        $emprendedorBO = new EmprendedorBO();
        $respuesta = $emprendedorBO->actualizarEmprendedor();
        return json_encode($respuesta);
    });
    
    $app->post('/getEmprendedor', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendedorBO.php");
        $emprendedorBO = new EmprendedorBO();
        $respuesta = $emprendedorBO->getEmprendedorPorId();
        return json_encode($respuesta);
    });
    
    $app->post('/getEmprendedorXidPersona', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendedorBO.php");
        $emprendedorBO = new EmprendedorBO();
        $respuesta = $emprendedorBO->getEmprendedorPorIdPersona();
        return json_encode($respuesta);
    });
    
    $app->post('/getEmprendedores', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendedorBO.php");
        $emprendedorBO = new EmprendedorBO();
        $respuesta = $emprendedorBO->getEmprendedores();
        return json_encode($respuesta);
    });
    
    $app->get('/{id_emprendedor}', function (Request $request, Response $response, array $args) {
        $id_emprendedor = $args["id_emprendedor"];
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/EmprendedorBO.php");
        $emprendedorBO = new EmprendedorBO();
        $respuesta = $emprendedorBO->getEmprendedorAT($id_emprendedor);
        return json_encode($respuesta);
    });
});
