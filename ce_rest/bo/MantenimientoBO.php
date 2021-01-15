<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../bo/BO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../util/General.php';

class MantenimientoBO extends BO {
    
    public function consultarDatos() {
        $respuesta = General::validarParametros($_POST, ["estado", "nombre", "tb"]);
        if(!is_null($respuesta)){
            return $respuesta;
        }
        $this->conectar();
        $consultasDAO = new ConsultasDAO();
        $consultasDAO->setConexion($this->conexion);
        $data = $consultasDAO->getDatosTB($_POST["nombre"], $_POST["estado"], $_POST["tb"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function grabarDatos() {
        $respuesta = General::validarParametros($_POST, ["datos", "tb"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $datos = json_decode($_POST["datos"]);
        $campos=null;
        if(General::tieneValor($_POST, "campos")){
            $campos=  json_decode($_POST["campos"]);
        }
        if (General::tieneValor($datos, "id")) {
            return $this->actualizar($datos, $_POST["tb"], $campos);
        } else {
            return $this->crear($datos, $_POST["tb"], $campos);
        }
    }
    
    public function crear($datos, $tabla, $campos) {
        try {
            $this->conectar();
            if(is_null($campos)){
                $campos=array("nombre","estado");
            }
            $valores = array();
            $tipodatos = array();
            foreach ($campos as &$campo){
                $valores[]=$datos->{$campo};
                $tipodatos[]="s";
            }
            $this->conexion->Insertar($tabla,$campos,$tipodatos,$valores);
            return General::setRespuesta("1", "Ingresado con éxito", $datos);
        } catch (Exception $e) {
            return General::setRespuestaError($e);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function actualizar($datos, $tabla, $campos) {
        try {
            $this->conectar();
            if(is_null($campos)){
                $campos=array("nombre","estado");
            }
            $valores = array();
            $tipodatos = array();
            foreach ($campos as &$campo){
                $valores[]=$datos->{$campo};
                $tipodatos[]="s";
            }
            $campos_condicion=array("id");
            $campos_condicion_valor=array($datos->id);
            $tipodatos_condicion=array("i");
            $this->conexion->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
		
            /*$consultasDAO = new ConsultasDAO();
            $consultasDAO->setConexion($this->conexion);
            $consultasDAO->actualizarDatosTB($datos, $tb);
            $this->cerrarConexion();*/
            return General::setRespuesta("1", "Actualizado con éxito", $datos);
        } catch (Exception $e) {
            return General::setRespuestaError($e);
        } finally {
            $this->cerrarConexion();
        }
    }
}
