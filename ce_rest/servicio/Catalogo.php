<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->group('/catalogo', function () use ($app) {
    //consultar emprendedores

    $app->post('/listaNivelEstudio', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getNivelEstudio();
        return json_encode($res);
    });
    
    $app->post('/listaEtiquetas', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEtiquetas();
        return json_encode($res);
    });
    
    $app->post('/listaGenero', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getGeneros();
        return json_encode($res);
    });
    
    $app->post('/listaSituacionLaboral', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getSituacionLaboral();
        return json_encode($res);
    });
    
    $app->post('/listaTipoEmprendimiento', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getTiposEmprendimiento();
        return json_encode($res);
    });
    
    $app->post('/listaEtapaEmprendimiento', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEtapasEmprendimientos();
        return json_encode($res);
    });
    
    $app->post('/listaNivelAcademico', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getNivelAcademico();
        return json_encode($res);
    });
    
    $app->post('/listaRedesSociales', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getRedSocial();
        return json_encode($res);
    });
    
    $app->post('/listaLugarComercializacion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getLugarComercializacion();
        return json_encode($res);
    });
    
    $app->post('/listaCanalVentas', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getCanalVentas();
        return json_encode($res);
    });
    
    $app->post('/listaEmpresaDelivery', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEmpresaDelivery();
        return json_encode($res);
    });
    
    $app->post('/listaActividadesComplentarias', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getActividadesComplementarias();
        return json_encode($res);
    });
    
    $app->post('/listaPersonaSocietaria', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getPersonaSocietaria();
        return json_encode($res);
    });
    
    $app->post('/listaInteresCentroEmprendimiento', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getInteresCentroEmprendimiento();
        return json_encode($res);
    });
    
    $app->post('/listaRazonesNoEmprender', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getRazonesNoEmpreder();
        return json_encode($res);
    });
    
    $app->post('/listaEjesMentoria', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEjesMentoria();
        return json_encode($res);
    });
    
    $app->post('/listaCiudad', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getCiudades();
        return json_encode($res);
    });
    
    $app->post('/eventosEpico', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEventosEpico();
        $response->getBody()->write(json_encode($res));
        //return json_encode($obj);
        return $response;
    });
    
    $app->post('/listaEtapasxSubPrograma', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEtapaxSubPrograma();
        return json_encode($res);
    });
    
    $app->post('/listaAplicaciones', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getAplicaciones();
        return json_encode($res);
    });
    
    $app->post('/listaPrograma', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getProgramas();
        return json_encode($res);
    });
    
    $app->post('/listaSubPrograma', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getSubPrograma();
        return json_encode($res);
    });
    
    $app->post('/listaEtapa', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEtapaxSubPrograma();
        return json_encode($res);
    });
    
    $app->post('/listaTipoActividad', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getTipoActividad();
        return json_encode($res);
    });
    
    $app->post('/listaActividadesSubPrograma', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getActividadesxSubPrograma();
        return json_encode($res);
    });
    
    $app->post('/listaAplicacionExterna', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getAplicacionesExternas();
        return json_encode($res);
    });
    
    $app->post('/listaTipoEjecucion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getTipoEjecucion();
        return json_encode($res);
    });
    
    $app->post('/listaArchivoNemonico', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getListaNemonicoFile();
        return json_encode($res);
    });
    
    $app->post('/listaRubricas', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getRubricas();
        return json_encode($res);
    });
    
    $app->post('/listaTipoEvento', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getTipoEvento();
        return json_encode($res);
    });
    
    $app->post('/listaEstadoActividad', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEstadoActividad();
        return json_encode($res);
    });
    
    $app->post('/listaMotivoCancelar', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getMotivoCancelar();
        return json_encode($res);
    });
    
    $app->post('/listaTipoAsistencia', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getTipoAsistencia();
        return json_encode($res);
    });

    $app->post('/listaInstitucion', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getInstitucion();
        return json_encode($res);
    });
    
    $app->post('/listaFeriados', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getFeriados();
        return json_encode($res);
    });
    
    $app->post('/listaUbicaciones', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getUbicaciones();
        return json_encode($res);
    });
    
    $app->post('/listaMentores', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getMentores();
        return json_encode($res);
    });
    
    $app->post('/listaEdiciones', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getEdiciones();
        return json_encode($res);
    });
    
    $app->post('/getNemonicoFile', function (Request $request, Response $response, array $args) {
        require_once("../util/URL.php");
        require_once("../bo/CatalogoBO.php");
        $catalogoBO = new CatalogoBO();
        $res = $catalogoBO->getNemonicoFile();
        return json_encode($res);
    });
});
