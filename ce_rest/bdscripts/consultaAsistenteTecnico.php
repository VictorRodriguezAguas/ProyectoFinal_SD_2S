<?php
header('Content-type: text/plain; charset=utf-8');
date_default_timezone_set('America/Guayaquil');
require_once('../util/basedatos.php');
//Se crea objeto de base de datos y se realiza conexion
$base = new basedatos;
$base->Conectar();

$sql="
select a.id as 'id_asistente_tecnico',a.fecha_registro as 'fecha_registro_asistente_tecnico',
a.estado as 'estado_asistente_tecnico',a.id_edicion,(select e.nombre from edicion e where a.id_edicion=e.id) as 'edicion',a.fecha_inicio,a.fecha_fin,a.id_usuario_registro,a.id_usuario_modifica,
a.fecha_modificacion as 'fecha_modificacion_asistente_tecnico',
p.id as 'id_persona',p.nombre,p.apellido,p.fecha_nacimiento,p.id_genero,(select g.nombre from genero g where g.id=p.id_genero) as 'genero',
p.id_ciudad,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad',p.email,p.telefono,p.id_situacion_laboral,
(select sl.nombre from situacion_laboral sl where sl.id=p.id_situacion_laboral) as 'situacion_laboral',p.tipo_identificacion,
p.identificacion,p.id_nivel_academico,(select nl.nombre from nivel_academico nl where nl.id=p.id_nivel_academico) as 'nivel_academico',
p.id_usuario,p.direccion,p.id_ciudad_domicilio,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad_domicilio',
p.fecha_registro as 'fecha_registro_persona',p.fecha_modificacion as 'fecha_modificacion_persona',p.telefono_fijo,p.cv,p.estado as 'estado_persona',p.uso_datos,
concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),usu.foto) as url_foto
from asistencia_tecnica a
left join persona p
on a.id_persona=p.id
left join ".basedatos::$baseSeguridad.".usuario usu
on p.id_usuario = usu.id
where a.estado='A' and p.estado='A';";

$asistentestecnicos=$base->Select($sql);
$error=$base->getError();
$mensaje=$base->getMensajeError();
if($error==0){
	$respuesta["estado"]="200";
	$respuesta["noticias"]="éxito";
	$respuesta["AsistenteTecnico"]=$asistentestecnicos;
	$JSON = json_encode($asistentestecnicos,JSON_UNESCAPED_UNICODE);
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