<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/agenda', function () use ($app) {
    //consultar emprendedores

    $app->post('/agendaPersonal', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $res = $agendaBO->getAgendaxPersona();
        return json_encode($res);
    });
    
    $app->post('/getHorarioAT', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $res = $agendaBO->getHorarioDisponibilidadAT();
        return json_encode($res);
    });
    
    $app->post('/insertarAgendaAT', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $respuesta = $agendaBO->insertarAgendaAT();
        return json_encode($respuesta);
    });
    
    $app->post('/insertarAgendaEvento', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $respuesta = $agendaBO->insertarAgendaEvento();
        return json_encode($respuesta);
    });
    
    $app->post('/cancelar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $agendaBO->setUser(getUser($request));
        $respuesta = $agendaBO->cancelarAgenda();
        return json_encode($respuesta);
    });
    
    $app->post('/getAgenda', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $res = $agendaBO->getAgenda();
        return json_encode($res);
    });
    
    $app->post('/getHorarioMentor', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $res = $agendaBO->getHorarioMentor();
        return json_encode($res);
    });
    
    $app->post('/insertarAgendaMentor', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $agendaBO->setUser(getUser($request));
        $respuesta = $agendaBO->insertarAgendaMentor();
        return json_encode($respuesta);
    });
    
    $app->post('/getAgendaPersona2', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/AgendaBO.php");
        $agendaBO = new AgendaBO();
        $agendaBO->setUser(getUser($request));
        $respuesta = $agendaBO->getAgendaxPersona2();
        return json_encode($respuesta);
    });
});

