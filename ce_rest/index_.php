<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

//Inicio Bloque de configuraciones
$config['displayErrorDetails'] = false;
$config['addContentLengthHeader'] = false;

/* $config['db']['host']   = 'localhost';
  $config['db']['user']   = 'user';
  $config['db']['pass']   = 'password';
  $config['db']['dbname'] = 'exampleapp'; */
//Fin de bloque de configuraciones

$app = new \Slim\App(['settings' => $config]);

/*
  $container = $app->getContainer();
  $container['logger'] = function($c) {
  $logger = new \Monolog\Logger('my_logger');
  $file_handler = new \Monolog\Handler\StreamHandler('logs/app.log');
  $logger->pushHandler($file_handler);
  return $logger;
  };
 */

// API version 1
$app->group('/v1', function () use ($app) {

    //consultar edicion
    $app->get('/edicion/', function (Request $request, Response $response, array $args) {
        require_once("bdscripts/consultaEdiciones.php");
        return $respuesta;
    });


    //consultar asistente tecnico
    $app->get('/asistentetecnico/', function (Request $request, Response $response, array $args) {
        require_once("bdscripts/consultaAsistenteTecnico.php");
        return $respuesta;
    });

    //consultar emprendedores
    $app->get('/emprendedores/', function (Request $request, Response $response, array $args) {
        require_once("bdscripts/consultaEmprendedores.php");
        return $respuesta;
    });

    //consultar emprendedor
    $app->get('/emprendedores/{id_emprendedor}', function (Request $request, Response $response, array $args) {
        $id_emprendedor = $args["id_emprendedor"];
        require_once("bdscripts/consultaEmprendedor.php");
        return $respuesta;
    });

    //consultar archivos del emprendedor
    $app->get('/emprendedores/{id_persona}/{daemon}', function (Request $request, Response $response, array $args) {
        $id_persona = $args["id_persona"];
        $daemon = $args["daemon"];
        require_once("bdscripts/consultaArchivosEmprendedor.php");
        return $respuesta;
    });

    //consulta horario de un asistente tecnico
    $app->get('/asistentetecnico/horario/{id_asistencia_tecnica}', function (Request $request, Response $response, array $args) {
        $id_asistencia_tecnica = $args["id_asistencia_tecnica"];
        require_once("bdscripts/consultaAsistenteTecnicoHorario.php");
        return $respuesta;
    });

    //Insertar horario
    $app->post('/asistentetecnico/horario/', function (Request $request, Response $response, array $args) {
        $json = $request->getBody();
        $horarios = json_decode($json);
        require_once("bdscripts/insertHorario.php");
        return $respuesta;
    });

    //
    $app->get('/asistentetecnico/agenda/{id_persona}', function (Request $request, Response $response, array $args) {
        $id_persona = $args["id_persona"];
        require_once("bdscripts/consultaAsistenteTecnicoAgenda.php");
        return $respuesta;
    });

    //Iniciar reunión
    $app->post('/asistentetecnico/agenda/iniciar', function (Request $request, Response $response, array $args) {
        $json = $request->getBody();
        $reunion = json_decode($json);
        require_once("bdscripts/iniciarReunion.php");
        return $respuesta;
    });

    //Finalizar reunión
    $app->put('/asistentetecnico/agenda/finalizar', function (Request $request, Response $response, array $args) {
        $json = $request->getBody();
        $reunion = json_decode($json);
        require_once("bdscripts/finalizarReunion.php");
        return $respuesta;
    });


    //Insertar persona
    $app->post('/asistentetecnico/', function (Request $request, Response $response, array $args) {
        $json = $request->getBody();
        $asistenteTecnico = json_decode($json);
        //var_dump($asistenteTecnico);
        //die();	
        require_once("bdscripts/insertAsistenteTecnico.php");
        return $respuesta;
    });

    //prueba login
    $app->get('/pruebaLogin/', function (Request $request, Response $response, array $args) {
        require_once("bdscripts/pruebaLogin.php");
        return $respuesta;
    });


    //check user data
    $app->get('/checkuser/{jwt}', function (Request $request, Response $response, array $args) {
        $jwt = $args["jwt"];
        require_once("bdscripts/checkUser.php");
        return $respuesta;
    });
});

$app->run();
?>