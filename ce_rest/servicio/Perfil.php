<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/perfil', function () use ($app) {
    $app->post('/menu', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/MenuBO.php");
        $user = getUser($request);
        $menuBO = new MenuBO();
        $id_perfil = null;
        if(General::tieneValor($_POST, "perfil")){
            $id_perfil = $_POST["perfil"];
        }
        $data = $menuBO->getMenuPorUsuario($user->id, 3, $id_perfil);
        return json_encode($data);
    });
    $app->post('/emprendedor', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/PersonaBO.php");
        $user = getUser($request);
        $personaBO = new PersonaBO();
        $personaBO->setUser($user);
        $respuesta = $personaBO->getPerfil();
        //$response->getBody()->write(json_encode($user));
        return json_encode($respuesta);
    });
    $app->post('/grabarFotoPerfil', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/UsuarioBO.php");
        $user = getUser($request);
        $usuarioBO = new UsuarioBO();
        $usuarioBO->setUser($user);
        $respuesta = $usuarioBO->grabarFotoPerfil();
        //$response->getBody()->write(json_encode($user));
        return json_encode($respuesta);
    });

    $app->post('/grabarArchivoReunion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once '../util/General.php';
        require_once("../bo/UsuarioBO.php");
        $user = getUser($request);
        $usuarioBO = new UsuarioBO();
        $usuarioBO->setUser($user);
        $respuesta = $usuarioBO->saveMeetingFile();
        return json_encode($respuesta);
    });
    
});