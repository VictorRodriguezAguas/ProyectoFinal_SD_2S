<?php
header('Content-type: text/plain; charset=utf-8');
date_default_timezone_set('America/Guayaquil');
require_once('../util/basedatos.php');
//Se crea objeto de base de datos y se realiza conexion
$base = new basedatos;
$base->Conectar();

$sql="
SELECT ie.id as 'id_incripcion_edicion',iea.archivo as 'modelo_negocio_archivo',
(SELECT valor FROM parametro_sistema WHERE nombre = 'RUTA_ARCHIVOS_URL') as url_archivo
FROM inscripcion_edicion ie
LEFT JOIN incripcion_edicion_archivos iea
ON ie.id = iea.id_inscripcion_edicion
WHERE ie.id_persona = ? and nemonico like ?";

$valores=array($id_persona,$daemon);
$tipodatos=array("i","s");
$archivos=$base->Select($sql,$valores,$tipodatos);		
$error=$base->getError();
$mensaje=$base->getMensajeError();

if($error==0){
	$respuesta["estado"]="200";
	$respuesta["noticias"]="éxito";
	$respuesta["Archivos"]=$archivos;
	$JSON = json_encode($archivos,JSON_UNESCAPED_UNICODE);
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