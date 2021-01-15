<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Evento
 *
 * @author ernesto.ruales
 */
$app->group('/evento', function () use ($app) {
    $app->post('/unicos', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/EventoBO.php");
        $eventoBO = new EventoBO();
        $respuesta = $eventoBO->getEventosU();
        return json_encode($respuesta);
    });
    
    $app->post('/todos', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/EventoBO.php");
        $eventoBO = new EventoBO();
        $respuesta = $eventoBO->getEventos();
        return json_encode($respuesta);
    });
    
    $app->post('/grabar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/EventoBO.php");
        $eventoBO = new EventoBO();
        $respuesta = $eventoBO->grabarEvento();
        return json_encode($respuesta);
    });
});
