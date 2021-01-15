<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->group('/mentoria', function () use ($app) {
    $app->post('/getMentores', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/MentorBO.php");
        $mentorBO = new MentorBO();
        $respuesta = $mentorBO->getMentoresXestado();
        return json_encode($respuesta);
    });
    
    $app->post('/agenda/reunion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ReunionBO.php");
        $reunionBO = new ReunionBO();
        $data = $reunionBO->getMeetingById();
        return json_encode($data);
    });
});

