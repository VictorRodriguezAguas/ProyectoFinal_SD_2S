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
$app->group('/mail', function () use ($app) {
    $app->post('/send', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/MailBO.php");
        $mailBO = new MailBO();
        $respuesta = $mailBO->sendMail();
        return json_encode($respuesta);
    });
});
