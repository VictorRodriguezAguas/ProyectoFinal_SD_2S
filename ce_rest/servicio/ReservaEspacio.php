<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/reserva-espacio', function () use ($app) {
    $app->get('/index/{date}', function (Request $request, Response $response, array $args) {
        $date = $args["date"];
        require_once("../bo/ReservaEspacioBO.php");
        $reserva_espacio_bo = new ReservaEspacioBO();
        $res = $reserva_espacio_bo->index($date);
        return json_encode($res);
    });

    $app->get('/espacios-reservados/{espacio}', function (Request $request, Response $response, array $args) {
        $id_espacio = $args["espacio"];
        require_once("../bo/ReservaEspacioBO.php");
        $reserva_espacio_bo = new ReservaEspacioBO();
        $res = $reserva_espacio_bo->indexReservation($id_espacio);
        return json_encode($res);
    });

    $app->get('/obtener-personas/{espacio}', function (Request $request, Response $response, array $args) {
        $id_espacio = $args["espacio"];
        require_once("../bo/ReservaEspacioBO.php");
        $reserva_espacio_bo = new ReservaEspacioBO();
        $res = $reserva_espacio_bo->getPerson($id_espacio);
        return json_encode($res);
    });

    $app->get('/direccion-epico', function (Request $request, Response $response, array $args) {
        require_once("../bo/ReservaEspacioBO.php");
        $reserva_espacio_bo = new ReservaEspacioBO();
        $res = $reserva_espacio_bo->getAddress();
        return json_encode($res);
    });

    $app->post('/grabar', function (Request $request, Response $response, array $args) {
        require_once("../bo/ReservaEspacioBO.php");
        $user = getUser($request);
        $reserva_espacio_bo = new ReservaEspacioBO();
        $reserva_espacio_bo->setUser($user);
        $respuesta = $reserva_espacio_bo->grabarReservacion();
        return json_encode($respuesta);
    });

    $app->get('/espacios-disponibles/{espacio}', function (Request $request, Response $response, array $args) {
        $id_espacio = $args["espacio"];
        require_once("../bo/ReservaEspacioBO.php");
        $reserva_espacio_bo = new ReservaEspacioBO();
        $res = $reserva_espacio_bo->indexAvailable($id_espacio);
        return json_encode($res);
    });
});

