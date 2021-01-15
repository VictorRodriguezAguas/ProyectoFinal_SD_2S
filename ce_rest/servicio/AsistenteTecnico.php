<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->group('/asistentetecnico', function () use ($app) {
    //consultar emprendedores

    $app->get('/', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/AsistenteTecnicoBO.php");
        $asistenteTecnicoBO = new AsistenteTecnicoBO();
        $respuesta = $asistenteTecnicoBO->consultaAsistenteTecnico();
        return json_encode($respuesta);
    });
    
    $app->get('/horario/{id_asistencia_tecnica}', function (Request $request, Response $response, array $args) {
        $id_asistencia_tecnica = $args["id_asistencia_tecnica"];
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/AsistenteTecnicoBO.php");
        $asistenteTecnicoBO = new AsistenteTecnicoBO();
        $respuesta = $asistenteTecnicoBO->consultaAsistenteTecnicoHorario($id_asistencia_tecnica);
        return json_encode($respuesta);
    });
    
    $app->post('/', function (Request $request, Response $response, array $args) {
        $json = $request->getBody();
        $asistenteTecnico = json_decode($json);
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/AsistenteTecnicoBO.php");
        $asistenteTecnicoBO = new AsistenteTecnicoBO();
        $respuesta = $asistenteTecnicoBO->grabarAsistenteTecnico($asistenteTecnico);
        return json_encode($respuesta);
    });
    
    $app->post('/eliminar/', function (Request $request, Response $response, array $args) {
        $json = $request->getBody();
        $asistenteTecnico = json_decode($json);
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/AsistenteTecnicoBO.php");
        $asistenteTecnicoBO = new AsistenteTecnicoBO();
        $respuesta = $asistenteTecnicoBO->eliminarAsistenteTecnico($asistenteTecnico);
        return json_encode($respuesta);
    });
    
    $app->post('/horario/', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/AsistenteTecnicoBO.php");
        $asistenteTecnicoBO = new AsistenteTecnicoBO();
        $respuesta = $asistenteTecnicoBO->insertHorario();
        return json_encode($respuesta);
    });
    
    $app->get('/agenda/{id_persona}', function (Request $request, Response $response, array $args) {
        $id_persona = $args["id_persona"];
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/AsistenteTecnicoBO.php");
        $asistenteTecnicoBO = new AsistenteTecnicoBO();
        $respuesta = $asistenteTecnicoBO->consultaAsistenteTecnicoAgenda($id_persona);
        return json_encode($respuesta);
    });
    
    /*$app->post('/agenda/reunion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ReunionBO.php");
        $reunionBO = new ReunionBO();
        $data = $reunionBO->getMeetingById();
        return json_encode($data);
    });*/
});

