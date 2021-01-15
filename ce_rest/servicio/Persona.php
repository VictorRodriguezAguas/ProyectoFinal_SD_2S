<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->group('/persona', function () use ($app) {
    //consultar emprendedores
    $app->post('/insertar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $post = $request->getBody();
        $personaBO = new PersonaBO();
        $data = $personaBO->insertarPersona();
        return json_encode($data);
    });
    
    $app->post('/actualizar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $personaBO = new PersonaBO();
        $data = $personaBO->insertarPersona();
        return json_encode($data);
    });
    
    $app->post('/getPersonasxActividad', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $personaBO = new PersonaBO();
        $data = $personaBO->getPeronasXActividad();
        return json_encode($data);
    });
    
    $app->post('/getPersona', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $personaBO = new PersonaBO();
        $data = $personaBO->getPersona();
        return json_encode($data);
    });

    $app->post('/getPersonas', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $personaBO = new PersonaBO();
        $data = $personaBO->getPersonas();
        return json_encode($data);
    });

    $app->post('/getPersonaXIdent', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $personaBO = new PersonaBO();
        $data = $personaBO->getPersonaXIdent();
        return json_encode($data);
    });
    
    $app->post('/getRedesSociales', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $personaBO = new PersonaBO();
        $data = $personaBO->getRedesSocialesPersona();
        return json_encode($data);
    });
});

