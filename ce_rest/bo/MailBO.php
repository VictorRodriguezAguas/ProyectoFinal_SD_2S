<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../bo/BO.php';
require_once '../util/basedatos.php';
require_once '../util/General.php';
require_once '../util/Correo.php';
require_once '../dao/CorreoDAO.php';
require_once '../dao/ConsultasDAO.php';

/**
 * Description of MailBO
 *
 * @author ernesto.ruales
 */
class MailBO extends BO {

    //put your code here

    public function sendMail() {
        $respuesta = General::validarParametros($_POST, ["cod_trama", "datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $datosTrama = json_decode($_POST["datos"]);
        try{
            $mailBO = new MailBO();
            $this->conectar();
            $mailBO->setConexion($this->conexion);
            $email = null;
            $asunto = null;
            $tipo = null;
            $fromName = null;
            if(General::tieneValor($_POST, "asunto")) $asunto = $_POST["asunto"];
            if(General::tieneValor($_POST, "email")) $email = $_POST["email"];
            if(General::tieneValor($_POST, "tipo")) $tipo = $_POST["tipo"];
            if(General::tieneValor($_POST, "fronName")) $fromName = $_POST["fronName"];

            if(!$mailBO->enviarCorreoTrama($_POST["cod_trama"], $datosTrama, $email, $asunto, $tipo, $fromName)){
                return General::setRespuesta('0', "No se pudo enviar el correo");
            }
        }
        catch(Exception $ex){
            return General::setRespuesta('0', "No se pudo enviar el correo");
        }
        return General::setRespuestaOK(NULL);
    }

    public function enviarCorreoTrama($cod_trama, $datosTrama, $email, $asunto, $tipo, $fromName = null) {
        try {
            $trama_conf = $this->getTrama($cod_trama, $datosTrama);
            $correo = new stdClass();
            $correo->destinatario = $email;
            if(!is_null($trama_conf->destinatario)){
                $correo->destinatario = $trama_conf->destinatario;
            }
            if(is_null($correo->destinatario)){
                throw new Exception("No tiene configurado el destinatario", "001");
            }
            $correo->asunto = $asunto;
            if(!is_null($trama_conf->asunto)){
                $correo->asunto = $trama_conf->asunto;
            }
            $correo->fromName = $fromName;
            if(!is_null($trama_conf->fromName)){
                $correo->fromName = $trama_conf->fromName;
            }
            $correo->texto_correo = $trama_conf->trama;
            $correo->tipo = $tipo;
            //$this->enviarCorreoInmediato($correo, null);
            return $this->enviarCorreoInmediato($correo, null);
            //return $trama_conf;
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    function getTrama($codigo, $values) {
        $this->conectar();
        $consultasDAO = new ConsultasDAO();
        $consultasDAO->setConexion($this->conexion);
        $trama_conf = $consultasDAO->getTrama($codigo);
        $trama = $trama_conf->trama;
        $trama_conf->destinatario = null;
        if (!is_null($trama_conf->sql)) {
            $sql = $trama_conf->sql;
            foreach ($values as $nombre => $valor) {
                $sql = str_replace('<<' . $nombre . '>>', $valor, $sql);
            }
            $datos = $consultasDAO->getData($sql);
            $campos_sql = explode(",", $trama_conf->campos_sql);
            $campos_trama = explode(",", $trama_conf->campos_trama);
            if (count($campos_sql) != count($campos_trama)) {
                throw new Exception('001', 'Los campos sql no son iguales a los campos de la trama');
            }
            for ($i = 0; $i < count($campos_sql); $i++) {
                if(General::tieneValor($datos, $campos_sql[$i])){
                    $trama = str_replace('<<' . $campos_trama[$i] . '>>', $datos->{$campos_sql[$i]}, $trama);
                }else{
                    $trama = str_replace('<<' . $campos_trama[$i] . '>>', '***', $trama);
                }
            }
            $trama_conf->values = $datos;
            $trama_conf->param = $values;
            foreach ($datos as $nombre => $valor) {
                if($trama_conf->campo_email == $nombre){
                    $trama_conf->destinatario = $valor;
                }
            }

            $trama_conf->campos_sql = $campos_sql;
            $trama_conf->campos_trama = $campos_trama;
        }
        $trama_conf->trama = $trama;
        return $trama_conf;
    }

    function getTramaJSON($codigo, $values = []) {
        $trama_conf = $this->getTrama($codigo, $values);
        $trama = json_decode($trama_conf->trama);
        $data = get_object_vars($trama);
        return $data;
    }

    public function enviarCorreoEnCola() {
        $respuesta = General::validarParametros($_POST, ["max_registros"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $correoDAO = new CorreoDAO();
        $correoDAO->setConexion($this->conexion);
        $this->enviarCorreoMasivo($correoDAO->getColaCorreos('E', "3", $_POST["max_registros"]), $correoDAO);
        $this->enviarCorreoMasivo($correoDAO->getColaCorreos('P', null, $_POST["max_registros"]), $correoDAO);
        $this->cerrarConexion();
        return General::setRespuesta("1", "Proceso culminado con éxito", "");
    }

    private function enviarCorreoMasivo($correos, $correoDAO) {
        $mail = new Correo();
        foreach ($correos as &$correo) {
            if (is_null($correo->archivo)) {
                if ($mail->enviarCorreo($correo->destinatario, $correo->texto_correo, $correo->asunto, $correo->fromName)) {
                    $correo->estado = 'C';
                    $correo->error = null;
                    $correo->fecha_envio = 'now()';
                    $correoDAO->actualizarCorreo($correo);
                } else {
                    $correo->estado = 'E';
                    $correo->error = $mail->error;
                    $correo->fecha_envio = 'null';
                    $correoDAO->actualizarCorreo($correo);
                }
            } else {
                $archivo = new stdClass();
                $archivo->url = ArchivosBO::getURLArchivo() . $correo->archivo;
                $archivo->nombre = $correo->nombre_archivo;
                if ($mail->enviarCorreoArchivo($correo->destinatario, $correo->texto_correo, $correo->asunto, $archivo, $correo->fromName)) {
                    $correo->estado = 'C';
                    $correo->error = null;
                    $correo->fecha_envio = 'now()';
                    $correoDAO->actualizarCorreo($correo);
                } else {
                    $correo->estado = 'E';
                    $correo->error = $mail->error;
                    $correo->fecha_envio = 'null';
                    $correoDAO->actualizarCorreo($correo);
                }
            }
        }
    }

    public function getCorreoPorEstado() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $correoDAO = new CorreoDAO();
        $correoDAO->setConexion($this->conexion);
        $data = $correoDAO->getColaCorreosConsulta($_POST["estado"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function enviarCorreoInmediato($correo, $correoDAO) {
        if (is_null($correoDAO)) {
            $correoDAO = new CorreoDAO();
            $this->conectar();
            $correoDAO->setConexion($this->conexion);
        }
        $mail = new Correo();

        if (!General::tieneValor($correo, "archivo")) {
            if ($mail->enviarCorreo($correo->destinatario, $correo->texto_correo, $correo->asunto, $correo->fromName)) {
                $correo->estado = 'C';
                $correo->error = null;
                $correo->fecha_envio = 'now()';
                $correoDAO->insertarCorreo($correo);
            } else {
                $correo->estado = 'E';
                $correo->error = $mail->error;
                $correo->fecha_envio = 'null';
                $correoDAO->insertarCorreo($correo);
                return false;
            }
        } else {
            $archivo = new stdClass();
            $archivo->url = ArchivosBO::getURLArchivo() . $correo->archivo;
            $archivo->nombre = $correo->nombre_archivo;
            if ($mail->enviarCorreoArchivo($correo->destinatario, $correo->texto_correo, $correo->asunto, $archivo, $correo->fromName)) {
                $correo->estado = 'C';
                $correo->error = null;
                $correo->fecha_envio = 'now()';
                $correoDAO->insertarCorreo($correo);
            } else {
                $correo->estado = 'E';
                $correo->error = $mail->error;
                $correo->fecha_envio = 'null';
                $correoDAO->insertarCorreo($correo);
                return false;
            }
        }
        return true;;
    }

}
