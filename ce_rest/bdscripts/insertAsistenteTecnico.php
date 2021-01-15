<?php

//error_reporting(0);

date_default_timezone_set('America/Guayaquil');
header('Content-Type: text/html; charset=utf-8');
//Incluir la clase basedatos
require_once('../util/basedatos.php');

  //obtener datos de cabecera
  $headerValueArray = $request->getHeader('Authorization');

  $base = new basedatos;
  $base->Conectar();
  $mensaje = $base->getMensajeError();

  try {
      $sql = "select id, id_usuario from persona where identificacion=?";
      $valores = array($asistenteTecnico->identificacion);
      $tipodatos = array("s");
      $idpersona = $base->Select($sql, $valores, $tipodatos);
      $error = $base->getError();
      $mensaje = $base->getMensajeError();

      if ($error == "0") {
          $tabla = "asistencia_tecnica";
          $campos = array("id_persona", "fecha_registro", "estado", "id_edicion",
              "fecha_inicio", "fecha_fin", "id_usuario_registro");
          $tipodatos = array("i", "s", "s", "i", "s", "s", "i");
          $valores = array($idpersona[0]->id, date("Y-m-d H:i:s"), "A", $asistenteTecnico->id_edicion, $asistenteTecnico->fecha_inicio, $asistenteTecnico->fecha_fin, $asistenteTecnico->id_usuario_registro);

          $base->Insertar($tabla, $campos, $tipodatos, $valores);
          if(General::tieneValor($idpersona, "id_usuario")){
              $usuarioDAO = new UsuarioDAO();
              $usuarioDAO->setConexion($base);
              $usuarioDAO->insertarUsuarioPerfil($idpersona[0]->id_usuario, "11");
          }
          $mensaje = $base->getMensajeError();
          $error = $base->getError();
          if ($error == "0") {
              $respuesta->estado = "200";
              $respuesta->noticias = "Registro de Asistente Técnico éxitoso.";
              $respuesta->data = "";
          }else{
              $respuesta->estado = "500";
              $respuesta->noticias = "Ha ocurrido un error en el registro del asistente tecnico";
              $respuesta->data = "$mensaje";
          }
      }   
      $JSON = json_encode($respuesta, JSON_UNESCAPED_UNICODE);
      $respuesta = $JSON;
      $base->CerrarConexion();

  } catch(Exception $e) {
      $respuesta->estado = "201";
      $respuesta->noticias = "Ha ocurrido un error en el registro del asistente tecnico";
      $respuesta->data = "$mensaje";
      $JSON = json_encode($respuesta, JSON_UNESCAPED_UNICODE);
      $respuesta = $JSON;
    echo 'Message: ' .$e->getMessage();
  }

?>