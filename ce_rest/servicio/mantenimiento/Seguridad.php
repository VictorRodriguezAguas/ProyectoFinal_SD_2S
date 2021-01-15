<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->post('/getMenus', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MenuBO.php");
    $menuBO = new MenuBO();
    $res = $menuBO->consultarMenu();
    return json_encode($res);
});

$app->post('/getPerfiles', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/PerfilBO.php");
    $perfilBO = new PerfilBO();
    $res = $perfilBO->consultarPerfiles();
    return json_encode($res);
});

$app->post('/grabarMenu', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MenuBO.php");
    $menuBO = new MenuBO();
    $res = $menuBO->grabarMenu();
    return json_encode($res);
});

$app->post('/grabarPerfil', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/PerfilBO.php");
    $perfilBO = new PerfilBO();
    $res = $perfilBO->grabarPerfil();
    return json_encode($res);
});

$app->post('/getMenuPerfilSelected', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/MenuBO.php");
    $menuBO = new MenuBO();
    $res = $menuBO->getMenuxPerfilSelected();
    return json_encode($res);
});

$app->post('/asignarMenuPerfil', function (Request $request, Response $response, array $args) {
    require_once("../util/URL.php");
    require_once("../bo/PerfilBO.php");
    $perfilBO = new PerfilBO();
    $res = $perfilBO->asignarMenuPerfil();
    return json_encode($res);
});

