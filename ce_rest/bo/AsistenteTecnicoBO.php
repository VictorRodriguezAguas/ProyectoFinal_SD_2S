<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AsistenteTecnico
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../util/General.php';
require_once '../dao/CorreoDAO.php';
require_once '../dao/AsistenteTecnicoDAO.php';
require_once '../dao/PersonaDAO.php';
require_once '../dao/UsuarioDAO.php';

class AsistenteTecnicoBO extends BO {

    //put your code here
    public function consultaAsistenteTecnico() {
        $this->conectar();
        try {
            $asistenteTecnicoDAO = new AsistenteTecnicoDAO();
            $asistenteTecnicoDAO->setConexion($this->conexion);
            $data = $asistenteTecnicoDAO->consultaAsistentesTecnicos();
        } finally {
            $this->cerrarConexion();
        }
        return General::setRespuestaOK($data);
    }

    public function consultaAsistenteTecnicoHorario($id_asistencia_tecnica) {
        $this->conectar();
        try {
            $asistenteTecnicoDAO = new AsistenteTecnicoDAO();
            $asistenteTecnicoDAO->setConexion($this->conexion);
            $data = $asistenteTecnicoDAO->consultaAsistenteTecnicoHorario($id_asistencia_tecnica);
        } finally {
            $this->cerrarConexion();
        }
        return General::setRespuestaOK($data);
    }

    public function grabarAsistenteTecnico($asistente = null) {
        if (is_null($asistente)) {
            $respuesta = General::validarParametros($_POST, ["datos"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
            $asistente = json_decode($_POST["datos"]);
        }
        try {
            $this->conectar();
            $asistenteTecnicoDAO = new AsistenteTecnicoDAO();
            $personaDAO = new PersonaDAO();
            $asistenteTecnicoDAO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);
            $persona = $personaDAO->getPersonaXIdent($asistente->identificacion);
            if (is_null($persona)) {
                return General::setRespuesta('0', "La identificación de la persona no existe");
            }
            $asis = $asistenteTecnicoDAO->consultaAsistenteTecnico($persona->id);
            if (!is_null($asis)) {
                return General::setRespuesta('0', "Hay un periodo activo para este asistente técnico");
            }
            if (!General::tieneValor($asistente, "id") && !General::tieneValor($asistente, "id_asistente_tecnico")) {
                $asistente->id_persona = $persona->id;
                $asistente->id = $asistenteTecnicoDAO->insertarAsistenteTecnico($asistente);
                $asistente->id_asistente_tecnico = $asistente->id;
            }
            $usuarioDAO = new UsuarioDAO();
            $usuarioDAO->setConexion($this->conexion);
            if (General::tieneValor($persona, "id_usuario")) {
                $perfil = $usuarioDAO->getUsuarioXidPerfil($persona->id_usuario, "11");
                if (is_null($perfil)) {
                    $usuarioDAO->insertarUsuarioPerfil($persona->id_usuario, "11");
                }
            } else {
                $usuario = new stdClass();
                $usuario->usuario = $persona->email;
                $usuario->password = $persona->identificacion;
                $usuario->nombre = $persona->nombre;
                $usuario->apellido = $persona->apellido;
                $usuario->id_institucion = 2;
                $usuario->estado = 'A';
                $usuario->correo = $persona->email;
                $usuario->id_usuario = $usuarioDAO->insertarUsuario($usuario);
                $persona->id_usuario = $usuario->id_usuario;
                $usuarioDAO->insertarUsuarioPerfil($usuario->id_usuario, "11");
                $personaDAO->actualizarPersona($persona);
            }
            $this->cerrarConexion();
            return General::setRespuestaOK($asistente);
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function insertHorario($horarios = null, $id_asistencia_tecnica=null) {
        if (is_null($horarios)) {
            $respuesta = General::validarParametros($_POST, ["horarios", "id_asistencia_tecnica"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
            $horarios = json_decode($_POST["horarios"]);
            $id_asistencia_tecnica = $_POST["id_asistencia_tecnica"];
        }
        try {
            $this->conectar();
            $asistenteTecnicoDAO = new AsistenteTecnicoDAO();
            $asistenteTecnicoDAO->setConexion($this->conexion);
            $asistenteTecnicoDAO->eliminarHorariosAsistenteTecnico($id_asistencia_tecnica);
            foreach ($horarios as $horario) {
                $asistenteTecnicoDAO->insertarHorarioAsistenteTecnico($horario);
            }
            $this->cerrarConexion();
            return General::setRespuestaOK();
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function eliminarAsistenteTecnico($asistente = null) {
        if (is_null($asistente)) {
            $respuesta = General::validarParametros($_POST, ["datos"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
            $asistente = json_decode($_POST["datos"]);
        }
        try {
            $this->conectar();
            $asistenteTecnicoDAO = new AsistenteTecnicoDAO();
            $asistenteTecnicoDAO->setConexion($this->conexion);
            if (!General::tieneValor($asistente, "id") && !General::tieneValor($asistente, "id_asistente_tecnico")) {
                if(!General::tieneValor($asistente, "id_asistente_tecnico")){
                    $asistente->id_asistente_tecnico = $asistente->id;
                }
                $asistente->estado = 'I';
                $asistenteTecnicoDAO->actualizarAsistenteTecnico($asistente);
            }
            
            $this->cerrarConexion();
            return General::setRespuestaOK($asistente);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function consultaAsistenteTecnicoAgenda($id_persona) {
        $this->conectar();
        try {
            $asistenteTecnicoDAO = new AsistenteTecnicoDAO();
            $asistenteTecnicoDAO->setConexion($this->conexion);
            $data = $asistenteTecnicoDAO->consultaAsistenteTecnicoAgenda($id_persona);
        } finally {
            $this->cerrarConexion();
        }
        return General::setRespuestaOK($data);
    }
}
