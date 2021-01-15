<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->post('/getActividades', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/ProgramaBO.php");
    $programaBO = new ProgramaBO();
    $res = $programaBO->getActividades();
    return json_encode($res);
});

$app->post('/grabarActividadEtapa', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/ProgramaBO.php");
    $programaBO = new ProgramaBO();
    $res = $programaBO->grabarActividadEtapa();
    return json_encode($res);
});

$app->post('/grabarActividadesEtapa', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/ProgramaBO.php");
    $programaBO = new ProgramaBO();
    $res = $programaBO->grabarActividadesEtapa();
    return json_encode($res);
});

$app->post('/getEtapas', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/ProgramaBO.php");
    $programaBO = new ProgramaBO();
    $res = $programaBO->getEtapasXSubPrograma();
    return json_encode($res);
});

$app->post('/grabarEtapa', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/ProgramaBO.php");
    $programaBO = new ProgramaBO();
    $res = $programaBO->grabarEtapa();
    return json_encode($res);
});

$app->post('/getMensajeEstadoActividadEtapa', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/ProgramaBO.php");
    $programaBO = new ProgramaBO();
    $res = $programaBO->getMensajePersonalizadoXIdActividadEtapa();
    return json_encode($res);
});

