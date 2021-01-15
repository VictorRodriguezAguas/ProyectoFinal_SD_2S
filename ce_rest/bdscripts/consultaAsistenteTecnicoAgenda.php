<?php

header('Content-type: text/plain; charset=utf-8');
date_default_timezone_set('America/Guayaquil');
require_once('../util/basedatos.php');
//Se crea objeto de base de datos y se realiza conexion
$base = new basedatos;
$base->Conectar();

/* $sql="select a.id as 'id_agenda', a.id_persona,  (select concat(p.nombre,' ',p.apellido) from persona p where p.id=a.id_persona) as 'persona1',
  a.id_persona2,(select concat(p.nombre,p.apellido) from persona p where p.id=a.id_persona2) as 'persona2',
  a.tipo as 'tipo_agenda',a.fecha as 'fecha_agenda',a.hora as 'hora_agenda',a.estado as 'estado_agenda',a.tema as 'tema_agenda',a.id_evento,
  r.id as 'id_reunion',r.hora_inicio as 'hora_inicio_reunion',r.hora_fin as 'hora_fin_reunion',r.acuerdos,r.temas,r.observacion,r.fecha_agendada,r.hora_inicio_agenda,r.hora_fin_agendad
  from agenda a
  left  join reunion r
  on a.id=r.id_agenda
  where id_persona = ?"; */

$sql = "
select a.id as 'id_agenda', 
    a.id_persona, concat(p.nombre,' ',p.apellido) as 'persona1', p.telefono,
    a.id_persona2, concat(p2.nombre,' ',p2.apellido) as 'persona2',
a.tipo as 'tipo_agenda',a.fecha as 'fecha_agenda',a.hora_inicio as 'hora_inicio_agenda',a.hora_fin as 'hora_fin_agenda',a.estado as 'estado_agenda',a.tema as 'tema_agenda',a.id_evento,
b.id as id_reunion, b.hora_inicio as hora_inicio_reunion, b.hora_fin as hora_fin_reunion,
b.estado as estado_reunion, a.*, ifnull(c.nombre, 'Sin modalidad') as tipo_asistencia
from agenda a
inner join persona p on p.id = a.id_persona
inner join persona p2 on p2.id = a.id_persona2
left outer join reunion b on b.id_agenda = a.id
left outer join tipo_asistencia c on c.id = a.id_tipo_asistencia
where id_persona2 = ? and a.estado <> 'CA';";


$valores = array($id_persona);
$tipodatos = array("i");
$agenda = $base->Select($sql, $valores, $tipodatos);
$error = $base->getError();
$mensaje = $base->getMensajeError();
//$respuesta=new stdClass();
if ($error == 0) {
    $respuesta["estado"] = "200";
    $respuesta["noticias"] = "éxito";
    $respuesta["horario"] = $agenda;
    $respuesta["data"] = $agenda;
    $JSON = json_encode($agenda, JSON_UNESCAPED_UNICODE);
    $respuesta = $JSON;
} else {
    //retornar error de servidor	
    $respuesta["estado"] = "500";
    $respuesta["noticias"] = "Ha ocurrido un error,  porfavor intentelo mas tarde";
    $respuesta["data"] = "";
    $JSON = json_encode($respuesta);
    $respuesta = $JSON;
}
$base->CerrarConexion();
?>