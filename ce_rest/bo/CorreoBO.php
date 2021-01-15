<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CorreoBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/CorreoDAO.php';
require_once '../util/General.php';
require_once '../util/Mail.php';
require_once '../bo/ArchivosBO.php';

class CorreoBO extends BO {

    //put your code here
    public function enviarCorreoEnCola() {
        $respuesta = General::validarParametros($_POST, ["max_registros"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $correoDAO = new CorreoDAO();
        $correoDAO->setConexion($this->conexion);
        $this->enviarCorreo($correoDAO->getColaCorreos('E', "3", $_POST["max_registros"]), $correoDAO);
        $this->enviarCorreo($correoDAO->getColaCorreos('P', null, $_POST["max_registros"]), $correoDAO);
        $this->cerrarConexion();
        return General::setRespuesta("1", "Proceso culminado con éxito", "");
    }

    private function enviarCorreo($correos, $correoDAO) {
        $mail = new Mail();
        foreach ($correos as &$correo) {
            if (is_null($correo->archivo)) {
                if ($mail->enviarCorreo($correo->destinatario, $correo->texto_correo, $correo->asunto)) {
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
                if ($mail->enviarCorreoArchivo($correo->destinatario, $correo->texto_correo, $correo->asunto, $archivo)) {
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
        return;
        if (is_null($correoDAO)) {
            $correoDAO = new CorreoDAO();
            $this->conectar();
            $correoDAO->setConexion($this->conexion);
        }
        $mail = new Mail();

        if (!General::tieneValor($correo, "archivo")) {
            if ($mail->enviarCorreo($correo->destinatario, $correo->texto_correo, $correo->asunto)) {
                $correo->estado = 'C';
                $correo->error = null;
                $correo->fecha_envio = 'now()';
                $correoDAO->insertarCorreo($correo);
            } else {
                $correo->estado = 'E';
                $correo->error = $mail->error;
                $correo->fecha_envio = 'null';
                $correoDAO->insertarCorreo($correo);
            }
        } else {
            $archivo = new stdClass();
            $archivo->url = ArchivosBO::getURLArchivo() . $correo->archivo;
            $archivo->nombre = $correo->nombre_archivo;
            if ($mail->enviarCorreoArchivo($correo->destinatario, $correo->texto_correo, $correo->asunto, $archivo)) {
                $correo->estado = 'C';
                $correo->error = null;
                $correo->fecha_envio = 'now()';
                $correoDAO->insertarCorreo($correo);
            } else {
                $correo->estado = 'E';
                $correo->error = $mail->error;
                $correo->fecha_envio = 'null';
                $correoDAO->insertarCorreo($correo);
            }
        }
    }

}
