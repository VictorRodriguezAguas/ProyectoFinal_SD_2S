<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/ticket', function () use ($app) {
    //consultar emprendedores

    $app->post('/tipo', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/TicketBO.php");
        $ticketBO = new TicketBO();
        $res = $ticketBO->getTicketTypes();
        return json_encode($res);
    });

    $app->post('/categoria', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/TicketBO.php");
        $ticketBO = new TicketBO();
        $res = $ticketBO->getTicketCategories();
        return json_encode($res);
    });

    $app->post('/subcategoria', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/TicketBO.php");
        $ticketBO = new TicketBO();
        $res = $ticketBO->getTicketSubcategories();
        return json_encode($res);
    });

    $app->post('/estado', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/TicketBO.php");
        $post = $request->getBody();
        $ticketBO = new TicketBO();
        $res = $ticketBO->getTicketStatus();
        return json_encode($res);
    });

    $app->post('/perfil', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/TicketBO.php");
        $ticketBO = new TicketBO();
        $res = $ticketBO->getTicketProfile();
        return json_encode($res);
    });

    $app->post('/ticketxusuario', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $ticketBO = new TicketBO();
        $data = $ticketBO->getTicketsByUser();
        return json_encode($data);
    });

    $app->post('/ticketxperfil', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $user = getUser($request);
        $ticketBO = new TicketBO();
        $data = $ticketBO->getTicketsByPerfil($user->id);
        return json_encode($data);
    });

    $app->post('/ticketxparam', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $post = $request->getBody();
        $ticketBO = new TicketBO();
        $data = $ticketBO->getTicketsByParams();
        return json_encode($data);
    });

    $app->post('/createdticketxparam', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $user = getUser($request);
        $post = $request->getBody();
        $ticketBO = new TicketBO();
        $data = $ticketBO->getCreatedTicketsByParams($user->id);
        return json_encode($data);
    });

    $app->post('/xparamHistorical', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $post = $request->getBody();
        $ticketBO = new TicketBO();
        $data = $ticketBO->getTicketsByParamsHistorical();
        return json_encode($data);
    });

    $app->post('/insertar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $ticketBO = new TicketBO();
        $data = $ticketBO->insertarTicket();
        return json_encode($data);
    });

    $app->post('/actualizarTicketAtendido', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $ticketBO = new TicketBO();
        $data = $ticketBO->updateTicketByAttended();
        return json_encode($data);
    });

    $app->post('/finalizarTicketAtendido', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $post = $request->getBody();
        $ticketBO = new TicketBO();
        $data = $ticketBO->doneTicketByAttended();
        return json_encode($data);
    });

    $app->post('/eliminarTicketAtendido', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/TicketBO.php");
        $post = $request->getBody();
        $ticketBO = new TicketBO();
        $data = $ticketBO->deleteTicketByAttended();
        return json_encode($data);
    });

    $app->post('/agenda/guardarArchivo', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/UsuarioBO.php");
        $user = getUser($request);
        $usuarioBO = new UsuarioBO();
        $usuarioBO->setUser($user);
        $respuesta = $usuarioBO->grabarFotoPerfil();
        return json_encode($respuesta);
    });

});
