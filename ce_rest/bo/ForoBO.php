<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ForoBO
 *
 * @author ernesto.ruales
 */
$newUrl = URL::getUrlLibreria();
require_once '../bo/BO.php';
require_once '../util/basedatos.php';
require_once '../dao/ForoDAO.php';
require_once '../util/General.php';
class ForoBO extends BO {
    //put your code here
    
    public function getForo() {
        $respuesta = General::validarParametros($_POST, ["id_foro"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {            
            $this->conectar();
            $foroDAO = new ForoDAO();
            $foroDAO->setConexion($this->conexion);
            $data = $foroDAO->getForo($_POST["id_foro"]);
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function getForos() {
        try {
            $param = new stdClass();
            if(General::tieneValor($_POST, "param")){
                $param = json_decode($_POST["param"]);
            }
            $this->conectar();
            $foroDAO = new ForoDAO();
            $foroDAO->setConexion($this->conexion);
            $data = $foroDAO->getForos($param);
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function grabarForo() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $foroDao = new ForoDAO();
        $foroDao->setConexion($this->conexion);
        $foro = json_decode($_POST["datos"]);
        if(General::tieneValor($foro, "id_foro")){
            $foroDao->update($foro);
        }else{
            $foro->id_foro = $foroDao->insert($foro);
        }
        $this->cerrarConexion();
        return General::setRespuestaOK($foro);
    }
    
    public function grabarRespuesta() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $foroDao = new ForoDAO();
        $foroDao->setConexion($this->conexion);
        $respuesta = json_decode($_POST["datos"]);
        if(General::tieneValor($respuesta, "id_respuesta")){
            $foroDao->updateRespuesta($respuesta);
        }else{
            $respuesta->id_respuesta = $foroDao->insertarRespuesta($respuesta);
        }
        $this->cerrarConexion();
        return General::setRespuestaOK($respuesta);
    }

    public function getRespuestasForo() {
        $respuesta = General::validarParametros($_POST, ["id_foro"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $foroDAO = new ForoDAO();
            $foroDAO->setConexion($this->conexion);
            $lista = $foroDAO->getRespuestas($_POST["id_foro"]);
            $data = General::generarArbolHash($lista, "id_respuesta_padre");
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }
    
}
