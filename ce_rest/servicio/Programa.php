<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/programa', function () use ($app) {
    //consultar emprendedores
    $app->post('/programasInscritos', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->getProgramasPersona();
        return json_encode($respuesta);
    });
    
    $app->post('/actualizarActividad', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->actualizarActividadEtapa();
        return json_encode($respuesta);
    });
    
    $app->post('/actualizarInscripcion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->actualizarInscripcion();
        return json_encode($respuesta);
    });
    
    $app->post('/getProgramaInscrito', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $respuesta = General::validarParametros($_POST, ["id_sub_programa"]);
        if (!is_null($respuesta)) {
            return json_encode($respuesta);
        }
        $user = getUser($request);
        $fase = null;
        $id_persona = $user->id_persona;
        if (isset($_POST['fase'])) {
            $fase = $_POST["fase"];
        }
        if (isset($_POST['id_persona'])) {
            $id_persona = $_POST["id_persona"];
        }
        $programaBO = new ProgramaBO();
        $data = $programaBO->getProgramaxInscripcion(null, $_POST['id_sub_programa'], $id_persona, NULL, null, $fase, false);
        return json_encode(General::setRespuestaOK($data));
    });
    
    $app->post('/aprobarEtapa', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $user = getUser($request);
        $programaBO->setUser($user);
        $respuesta = $programaBO->aprobarFaseInscripcion();
        return json_encode($respuesta);
    });
    
    $app->post('/aprobarActividades', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->actualizarActividades();
        return json_encode($respuesta);
    });
    
    $app->post('/cambiarFase', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->cambiarFaseInscripcion();
        return json_encode($respuesta);
    });
    
    $app->post('/revertirActividad', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->revertirActividad();
        return json_encode($respuesta);
    });
    
    $app->post('/updateModule', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->updateModuloAtrevete();
        return json_encode($respuesta);
    });
    
    $app->post('/asignarActividades', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->asignarActividades();
        return json_encode($respuesta);
    });
    
    $app->post('/actualizarMensajeAprobacion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->actualizarMensajeAprobacion();
        return json_encode($respuesta);
    });
    
    $app->post('/actualizarNewOrdenActividades', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->actualizarNewOrdenActividades();
        return json_encode($respuesta);
    });
    
    $app->post('/asignarMentoria', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->asignarMentorias();
        return json_encode($respuesta);
    });
    
    $app->post('/getInscripcionEtapa', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->getInscripcionEtapa();
        return json_encode($respuesta);
    });
    
    $app->post('/getAsistenteTecnicoAsignado', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->getAsistenteTecnicoAsignado();
        return json_encode($respuesta);
    });
    
    $app->post('/getActividadXidAgenda', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->getActividadxIdAgenda();
        return json_encode($respuesta);
    });
    
    $app->post('/getMentoriasPendientes', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->getMentoriasPendientesEtapa();
        return json_encode($respuesta);
    });
    
    $app->post('/cambiarMentor', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/ProgramaBO.php");
        $programaBO = new ProgramaBO();
        $respuesta = $programaBO->cambiarMentores();
        return json_encode($respuesta);
    });
});


