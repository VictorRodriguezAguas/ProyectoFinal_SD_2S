<?php
error_reporting(0);

date_default_timezone_set('America/Guayaquil');
header('Content-Type: text/html; charset=utf-8');
//Incluir la clase basedatos
require_once('../util/basedatos.php');

$base = new basedatos;
$base->Conectar();
$mensaje=$base->getMensajeError();


/*$sql="select id
from actividad_edicion
where id_agenda=? and estado='IN'";*/
$sql="select id
from actividad_edicion
where id_agenda=?";
$valores=array($reunion->id_agenda);
$tipodatos=array("i");
$actividad_edicion=$base->Select($sql,$valores,$tipodatos);		
$error=$base->getError();
$mensaje=$base->getMensajeError();
if($error==0){
	$id_actividad_inscripcion='';
	foreach ($actividad_edicion as $actividad){
		$id_actividad_inscripcion=$actividad["id"];
	}	
	if($id_actividad_inscripcion){
		$pvars   = array('accion' => 'actualizarActividad', 'id_actividad_inscripcion'=>$id_actividad_inscripcion,'estado'=>'AP');
		$timeout = 300;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://epico.gob.ec/centro_emprendimiento/app/servicio/centro_emprendimiento/Programa.php');
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$out = curl_exec($curl);
		curl_close ($curl);
		$pms = json_decode($out,true);

		$tabla="reunion";
		$campos=array("hora_fin","temas","acuerdos","observacion");
		$tipodatos=array("s","s","s","s");
		$valores=array($reunion->hora_fin_reunion,$reunion->temas,$reunion->acuerdos,$reunion->observacion);
		$campos_condicion=array("id_agenda");
		$campos_condicion_valor=array($reunion->id_agenda);
		$tipodatos_condicion=array("i");
		$base->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
		$mensaje=$base->getMensajeError();
		$error=$base->getError();							
		if($error==0){
			
			$pvars   = array('accion' => 'actualizarActividad', 'id_actividad_inscripcion'=>$id_actividad_inscripcion,'estado'=>'AP');
			$timeout = 300;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://epico.gob.ec/centro_emprendimiento/app/servicio/centro_emprendimiento/Programa.php');
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$out = curl_exec($curl);
			curl_close ($curl);
			$pms = json_decode($out,true);
			
			if($pms["codigo"]=="1"){
				$respuesta["estado"]="200";
				$respuesta["noticias"]="éxito";
				$respuesta["data"]="";
				$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE);
				$respuesta = $JSON;						
			}
			else{
				$respuesta["estado"]="200";
				$respuesta["noticias"]="No hubo actividad para finalizar";
				$respuesta["data"]="";
				$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE);
				$respuesta = $JSON;					
			}	
		}	
		else{
			$respuesta["estado"]="500";
			$respuesta["noticias"]=$mensaje;
			$respuesta["data"]="";
			$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE);
			$respuesta = $JSON;			
		}			
	}
	else{
		$respuesta["estado"]="500";
		$respuesta["noticias"]='No hay actividad correspondiente';
		$respuesta["data"]="";
		$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE);
		$respuesta = $JSON;			
	}		
}
else{
	$respuesta["estado"]="500";
	$respuesta["noticias"]=$mensaje;
	$respuesta["data"]="";
	$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE);
	$respuesta = $JSON;		
}
$base->CerrarConexion();
?>