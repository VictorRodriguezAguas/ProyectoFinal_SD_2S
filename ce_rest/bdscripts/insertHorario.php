<?php

//error_reporting(0);

date_default_timezone_set('America/Guayaquil');
header('Content-Type: text/html; charset=utf-8');
//Incluir la clase basedatos
require_once('../util/basedatos.php');

$base = new basedatos;
$base->Conectar();
$mensaje = $base->getMensajeError();

$tabla = "asistencia_tecnica_horario";
$campos_condicion = array("id_asistencia_tecnica");
$campos_condicion_valor = array($horarios[0]->id_asistencia_tecnica);
$tipodatos_condicion = array("i");
$base->Eliminar($tabla, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion);
$mensaje = $base->getMensajeError();
$error = $base->getError();


foreach ($horarios as $horario) {
    $tabla = "asistencia_tecnica_horario";
    $campos = array("id_asistencia_tecnica", "dia", "hora_inicio", "hora_fin");
    $tipodatos = array("i", "s", "s", "s");
    $valores = array($horario->id_asistencia_tecnica, $horario->dia, $horario->hora_inicio, $horario->hora_fin);
    $base->Insertar($tabla, $campos, $tipodatos, $valores);
    $mensaje = $base->getMensajeError();
    $error = $base->getError();
}

if ($error == 0) {
    $respuesta["estado"] = "200";
    $respuesta["noticias"] = "éxito";
    $respuesta["data"] = "";
    $JSON = json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    $respuesta = $JSON;
} else {
    $respuesta["estado"] = "500";
    $respuesta["noticias"] = "Error: " . $mensaje;
    $respuesta["data"] = "";
    $JSON = json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    $respuesta = $JSON;
}

$base->CerrarConexion();
?>