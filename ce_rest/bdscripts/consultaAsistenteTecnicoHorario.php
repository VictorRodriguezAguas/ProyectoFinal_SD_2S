<?php
header('Content-type: text/plain; charset=utf-8');
date_default_timezone_set('America/Guayaquil');
require_once('../util/basedatos.php');
//Se crea objeto de base de datos y se realiza conexion
$base = new basedatos;
$base->Conectar();

$sql="SELECT id_asistencia_tecnica,dia,hora_inicio,hora_fin
FROM  asistencia_tecnica_horario
WHERE id_asistencia_tecnica=?;";

$valores=array($id_asistencia_tecnica);
$tipodatos=array("i");
$horario=$base->Select($sql,$valores,$tipodatos);		
$error=$base->getError();
$mensaje=$base->getMensajeError();
if($error==0){
	$respuesta["estado"]="200";
	$respuesta["noticias"]="éxito";
	$respuesta["horario"]=$horario;
	$JSON = json_encode($horario,JSON_UNESCAPED_UNICODE);
	$respuesta = $JSON;	
}
else{
	//retornar error de servidor	
	$respuesta["estado"]="500";
	$respuesta["noticias"]="Ha ocurrido un error,  porfavor intentelo mas tarde";
	$respuesta["data"]="";
	$JSON = json_encode($respuesta);
	$respuesta=$JSON;	
}
$base->CerrarConexion();















?>