<?php
//error_reporting(0);

date_default_timezone_set('America/Guayaquil');
header('Content-Type: text/html; charset=utf-8');
//Incluir la clase basedatos
require_once('../util/basedatos.php');

//obtener datos de cabecera
$headerValueArray = $request->getHeader('Authorization');
//Validar que no este vacia
if($headerValueArray!=null){
	$headerValueArray = $request->getHeader('Authorization');
	$accesstoken= substr($headerValueArray[0],7);		
}
else{
	//si esta vacio mandarlo como forbidden
	$accesstoken="403";
}
$base = new basedatos;
$base->Conectar();
$mensaje=$base->getMensajeError();

/*Comprobar si es un token valido de usuario desde la base de datos
//Comprobar si el request tiene un token valido
$sql="SELECT count(*) as valido,un.descripciónUnidad
FROM usuarios u
left join unidades un
on u.idConjunto=un.idConjunto and u.idUnidad=un.idUnidad
where u.idConjunto=? and u.idUnidad=? and u.nombreUsuario=? and u.usuarioAPIKEY like ?";
$valores=array($conjunto,$unidad,$usuario,$accesstoken);
$tipodatos=array("i","i","s","s");
$datosUser=$base->Select($sql,$valores,$tipodatos);		
$error=$base->getError();
$mensaje=$base->getMensajeError();
if($datosUser[0]["valido"]){*/
	
if($accesstoken=="123456"){
	$tabla="persona";
	$campos=array("nombre","apellido","fecha_nacimiento","id_genero","id_ciudad","email","telefono","id_situacion_laboral",
	"tipo_identificacion","identificacion","id_nivel_academico","id_usuario","direccion","id_ciudad_domicilio","fecha_registro",
	"fecha_modificacion","id_interes_centro_emprendimiento","telefono_fijo","cv","estado","uso_datos");
	$tipodatos=array("s","s","s","i","i","s","s","i","s","s","i","i","s","i","s","s","i","s","s","s","s");
	$valores=array($nombre,$apellido,$fecha_nacimiento,$id_genero,$id_ciudad,$email,$telefono,$id_situacion_laboral,$tipo_identificacion,
	$identificacion,$id_nivel_academico,$id_usuario,$direccion,$id_ciudad_domicilio,$fecha_registro,$fecha_modificacion,
	$id_interes_centro_emprendimiento,$telefono_fijo,$cv,$estado,$uso_datos);
	$base->Insertar($tabla,$campos,$tipodatos,$valores);
	$mensaje=$base->getMensajeError();
	$error=$base->getError();	
	if($error=="0"){	
		$respuesta["estado"]="200";
		$respuesta["noticias"]="éxito";			
		$respuesta["data"]="";
		$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE); 
		echo $JSON;				
	}
	else{
		$respuesta["estado"]="500";
		$respuesta["noticias"]="Ha ocurrido un error, porfavor intentelo mas tarde";
		$respuesta["data"]="$mensaje";
		$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE); 
		echo $JSON;			
	}		
}
else{
	$respuesta["estado"]="403";
	$respuesta["noticias"]="Forbidden";
	$respuesta["data"]="";
	$respuesta["paginacion"]=["pagina"=>1,"filas"=>1,"paginas"=>1,"regitros"=>0];
	$respuesta["benchmark"]=["tiempo"=>"","memoria"=>""];
	$JSON = json_encode($respuesta,JSON_UNESCAPED_UNICODE); 
	echo $JSON;		
}
$base->CerrarConexion();
?>