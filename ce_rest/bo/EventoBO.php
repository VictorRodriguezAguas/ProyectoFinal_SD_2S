<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventoBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/EventoDAO.php';
require_once '../util/General.php';
class EventoBO extends BO {
    //put your code here
    
    public function getEventos() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $eventoDAO = new EventoDAO();
            $eventoDAO->setConexion($this->conexion);
            $parametro = new stdClass();
            $parametro->fecha_desde = $_POST['fecha_desde'];
            $parametro->id_tipo_evento = $_POST['id_tipo_evento'];
            $parametro->codigo = $_POST['codigo'];
            $parametro->estado = $_POST['estado'];
            $parametro->id_etapa = $_POST['id_etapa'];
            $parametro->id_persona = $_POST['id_persona'];
            $data = $eventoDAO->getEventos($parametro);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getEventosU() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $eventoDAO = new EventoDAO();
            $eventoDAO->setConexion($this->conexion);
            $parametro = new stdClass();
            $parametro->fecha_desde = $_POST['fecha_desde'];
            $parametro->id_tipo_evento = $_POST['id_tipo_evento'];
            $parametro->codigo = $_POST['codigo'];
            $parametro->estado = $_POST['estado'];
            $data = $eventoDAO->getEventosU($parametro);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function grabarEvento() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $eventoDAO = new EventoDAO();
            $eventoDAO->setConexion($this->conexion);
            $evento = json_decode($_POST["datos"]);
            if(!General::tieneValor($evento, "id_evento")){
                $evento->id=$eventoDAO->insertar($evento);
                $evento->id_evento = $evento->id;
                if($this->conexion->getError() != '0'){
                    return General::setRespuesta("0", $this->conexion->getMensajeError(), null);
                }
            }else{
                $eventoDAO->actualizar($evento);
            }
            $this->cerrarConexion();
            return General::setRespuestaOK($evento);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
}
