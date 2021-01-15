<?php
header('Content-type: text/plain; charset=utf-8');
date_default_timezone_set('America/Guayaquil');
require_once('../util/basedatos.php');
//Se crea objeto de base de datos y se realiza conexion
$base = new basedatos;
$base->Conectar();

$sql="
SELECT e.id as 'id_emprendedor',e.estado as 'estado_emprendedor',e.fecha_registro as 'fecha_registro_emprendedor',
e.id_usuario,e.acepta_terminos_registro,e.id_persona,e.estado_ce,p.nombre,p.apellido,p.fecha_nacimiento,
p.id_genero,(select g.nombre from genero g where g.id=p.id_genero) as 'genero',
p.id_ciudad,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad',p.email,p.telefono,p.id_situacion_laboral,
(select sl.nombre from situacion_laboral sl where sl.id=p.id_situacion_laboral) as 'situacion_laboral',p.tipo_identificacion,
p.identificacion,p.id_nivel_academico,(select nl.nombre from nivel_academico nl where nl.id=p.id_nivel_academico) as 'nivel_academico',
p.direccion,p.id_ciudad_domicilio,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad_domicilio',p.telefono_fijo,p.cv,
ee.id_emprendimiento,
emp.nombre as 'nombre_emprendimiento',emp.id_etapa_emprendimiento,
(select ete.nombre from etapa_emprendimiento ete where ete.id = emp.id_etapa_emprendimiento) as 'etapa_emprendimiento',
emp.cant_socios,emp.emprendimiento_formalizado,emp.ruc_rise,emp.razon_social,emp.nombre_comercial,
emp.id_tipo_emprendimiento, (select te.nombre from tipo_emprendimiento te where te.id=emp.id_tipo_emprendimiento) as 'tipo_emprendimiento',
emp.venta_mensual,emp.ganancia_anual,emp.id_lugar_comercializacion,
(select lc.nombre from lugar_comercializacion lc where lc.id = emp.id_lugar_comercializacion) as 'lugar_comercializacion',
emp.numero_labora,emp.utiliza_plataforma_electronica,emp.estado as 'estado_emprendimiento',emp.direccion as 'direccion_emprendimiento',emp.logo,
emp.opera_ruc_rise,emp.telefono_whatsapp,
ie.fase, (select etp.nombre from etapa etp where etp.id=ie.fase) as 'fase_nombre'
FROM emprendedor e
LEFT JOIN persona p
ON e.id_persona=p.id
LEFT JOIN emprendedor_emprendimiento ee
ON e.id=ee.id_emprendedor
LEFT JOIN emprendimiento emp
ON ee.id_emprendimiento=emp.id
LEFT JOIN inscripcion_edicion ie
ON ie.id_persona=p.id
where e.estado='A'
limit 0,1000";

$emprendedores=$base->Select($sql);
$error=$base->getError();
$mensaje=$base->getMensajeError();
if($error==0){
	$respuesta["estado"]="200";
	$respuesta["noticias"]="éxito";
	$respuesta["AsistenteTecnico"]=$emprendedores;
	$JSON = json_encode($emprendedores,JSON_UNESCAPED_UNICODE);
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