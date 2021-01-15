<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->post('/getMentores', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MentorBO.php");
    $mentorBO = new MentorBO();
    $res = $mentorBO->getMentores();
    return json_encode($res);
});

$app->post('/getMentorPersona', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MentorBO.php");
    $mentorBO = new MentorBO();
    $res = $mentorBO->getMentorPersona();
    return json_encode($res);
});

$app->post('/getMentor', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MentorBO.php");
    $mentorBO = new MentorBO();
    $res = $mentorBO->getMentor();
    return json_encode($res);
});

$app->post('/grabarHorariosMentor', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MentorBO.php");
    $mentorBO = new MentorBO();
    $res = $mentorBO->insertHorarios();
    return json_encode($res);
});

$app->post('/grabarMentor', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MentorBO.php");
    $mentorBO = new MentorBO();
    $res = $mentorBO->guardarMentor();
    return json_encode($res);
});

$app->post('/grabarPeriodoMentor', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MentorBO.php");
    $mentorBO = new MentorBO();
    $res = $mentorBO->grabarPeriodoMentor();
    return json_encode($res);
});

$app->post('/getMentoresAllInfo', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MentorBO.php");
    $mentorBO = new MentorBO();
    $res = $mentorBO->getMentoresAllInfo();
    return json_encode($res);
});

