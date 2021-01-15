<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormularioBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/FormularioDAO.php';
require_once '../util/General.php';

class FormularioBO extends BO {

    //put your code here

    public function getFormulario() {
        $respuesta = General::validarParametros($_POST, ["id_formulario"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $formularioDAO = new FormularioDAO();
            $formularioDAO->setConexion($this->conexion);
            $data = $formularioDAO->getFormulario($_POST["id_formulario"]);
            if (is_null($data)) {
                $data = $formularioDAO->getFormularioxCodigo($_POST["id_formulario"]);
            }
            if (is_null($data)) {
                return General::setRespuesta("0", "No existe el formulario");
            }
            $data->campos = $formularioDAO->getCampos($data->id_formulario);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getRegistro() {
        $respuesta = General::validarParametros($_POST, ["id_registro"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $formularioDAO = new FormularioDAO();
            $formularioDAO->setConexion($this->conexion);
            $data = $formularioDAO->getRegistro($_POST["id_registro"]);
            if (is_null($data)) {
                return General::setRespuesta("0", "No existe registro");
            }
            $data->campos = $formularioDAO->getCamposRegistro($data->id_registro_formulario);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function grabarRegistro() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $registro = json_decode($_POST["datos"]);
            $this->conectar();
            $formularioDAO = new FormularioDAO();
            $formularioDAO->setConexion($this->conexion);
            if(!General::tieneValor($registro, "id_registro_formulario")){
                $registro->id_usuario_registro = $this->user->id_usuario;
                $registro->id_registro_formulario = $formularioDAO->insertRegistro($registro);
            }
            foreach ($registro->campos as $campo){
                $campo->id_registro = $registro->id_registro_formulario;
                $campoRegistro = $formularioDAO->getCampoRegistro($campo->id_registro, $campo->id_formulario_campo);
                if(is_null($campoRegistro)){
                    $formularioDAO->insertCampoRegistro($campo);
                }
                else{
                    $formularioDAO->actualizarCampoRegistro($campo);
                }
            }
            return General::setRespuestaOK($registro);
        } 
        catch (Exception $ex){
            return General::setRespuestaError($ex);
        }finally {
            $this->cerrarConexion();
        }
    }
}
