<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MentorBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/PersonaDAO.php';
require_once '../util/General.php';
require_once '../bo/ArchivosBO.php';
require_once '../dao/MentorDAO.php';
require_once '../dao/EvaluacionDAO.php';
require_once '../bo/UsuarioBO.php';
require_once '../bo/MailBO.php';

class MentorBO extends BO {

    public function getMentores() {
        /* $respuesta = General::validarParametros($_POST, ["id_persona"]);
          if (!is_null($respuesta)) {
          return $respuesta;
          } */
        try {
            $mentorDAO = new MentorDAO();

            $this->conectar();
            $mentorDAO->setConexion($this->conexion);

            $data = $mentorDAO->getMentores();
            foreach ($data as &$mentor) {
                $mentor->listaEjeMentoria = $mentorDAO->getEjeMentoriaxIdMentor($mentor->id_mentor);
            }
            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getMentoresAllInfo() {
        /* $respuesta = General::validarParametros($_POST, ["id_persona"]);
          if (!is_null($respuesta)) {
          return $respuesta;
          } */
        try {
            $mentorDAO = new MentorDAO();

            $this->conectar();
            $mentorDAO->setConexion($this->conexion);

            $data = $mentorDAO->getMentoresAllInfo();
            foreach ($data as &$mentor) {
                $mentor->listaEjeMentoria = $mentorDAO->getEjeMentoriaxIdMentorText($mentor->id_mentor);
            }
            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getMentoresXestado() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $mentorDAO = new MentorDAO();

            $this->conectar();
            $mentorDAO->setConexion($this->conexion);

            $data = $mentorDAO->getMentoresXestado($_POST["estado"]);
            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getMentorPersona() {
        $respuesta = General::validarParametros($_POST, ["id_persona"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $mentorDAO = new MentorDAO();
            $personaDAO = new PersonaDAO();
            $this->conectar();
            $mentorDAO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);

            $data = $mentorDAO->getMentorPorIdPersona($_POST["id_persona"]);
            if (!is_null($data)) {
                //$data->listaEjeMentoria = $mentorDAO->getEjeMentoriaxIdMentor($data->id_mentor);
                $data->listaEjeMentoria = $mentorDAO->getEjeMentoria($data->id_mentor);
                $data->periodos = $mentorDAO->getPeriodos($data->id_mentor);
                $data->horarios = $mentorDAO->getHorarioMentor($data->id_mentor);
                $data->redes_sociales = $personaDAO->getRedesSocialesPersona($_POST["id_persona"]);
                $data->intereses = $personaDAO->getInteresPersona($_POST["id_persona"]);
            }

            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getMentor() {
        $respuesta = General::validarParametros($_POST, ["id_mentor"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $mentorDAO = new MentorDAO();
            $personaDAO = new PersonaDAO();
            $evaluacionDAO = new EvaluacionDAO();
            $this->conectar();
            $mentorDAO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);
            $evaluacionDAO->setConexion($this->conexion);

            $data = $mentorDAO->getMentor($_POST["id_mentor"]);
            if (!is_null($data)) {
                //$data->listaEjeMentoria = $mentorDAO->getEjeMentoriaxIdMentor($data->id_mentor);
                $data->listaEjeMentoria = $mentorDAO->getEjeMentoria($data->id_mentor);
                $data->periodos = $mentorDAO->getPeriodos($data->id_mentor);
                $data->horarios = $mentorDAO->getHorarioMentor($data->id_mentor);
                $data->redes_sociales = $personaDAO->getRedesSocialesPersona($data->id_persona);
                $data->intereses = $personaDAO->getInteresPersona($data->id_persona);
                $param = new stdClass();
                $param->id_mentor = $data->id_mentor;
                $param->id_evaluado = $data->id_persona;
                $data->evaluacion = $evaluacionDAO->getEvaluacionxIds($param, 2);
            }

            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function insertHorarios($horarios = null, $id_mentor = null) {
        if (is_null($horarios)) {
            $respuesta = General::validarParametros($_POST, ["horarios", "id_mentor"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
            $horarios = json_decode($_POST["horarios"]);
            $id_mentor = $_POST["id_mentor"];
        }
        try {
            $this->conectar();
            $mentorDAO = new MentorDAO();
            $mentorDAO->setConexion($this->conexion);
            $this->_insertHorarios($mentorDAO, $horarios, $id_mentor);
            $this->cerrarConexion();
            return General::setRespuestaOK();
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    private function _insertHorarios($mentorDAO, $horarios, $id_mentor) {
        $mentorDAO->eliminarHorariosMentor($id_mentor);
        foreach ($horarios as $horario) {
            $mentorDAO->insertarHorarioMentor($horario);
        }
    }

    public function guardarMentor() {
        $respuesta = General::validarParametros($_POST, ["datos", "crearUsuario"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $mentor = json_decode($_POST["datos"]);
        $crearUsuario = json_decode($_POST["crearUsuario"]);
        $resp = $this->grabarMentor($mentor, $crearUsuario);
        if (General::tieneValor($resp, "codigo")) {
            return $resp;
        }
        return General::setRespuesta("1", "Mentor grabado con éxito", $resp);
        /* if($persona == null){
          return General::setRespuesta("0", "Persona ya existe", $persona);
          } */
    }

    public function grabarMentor($mentor, $crearUsuario = false) {

        $mentorDAO = new MentorDAO();
        $personaDAO = new PersonaDAO();
        $usuarioDAO = new UsuarioDAO();
        
        $this->conectar();
        try {
            $mentorDAO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);
            $usuarioDAO->setConexion($this->conexion);

            if (!General::tieneValor($mentor, "id_persona")) {
                return General::setRespuesta("0", "No tiene asignado una persona");
            }

            if (!General::tieneValor($mentor, "id_mentor")) {
                $mentorAux = $mentorDAO->getMentorPorIdPersona($mentor->id_persona);
                if (!is_null($mentorAux)) {
                    return General::setRespuesta("0", "La persona ya esta registrada como mentor");
                } 
                else {
                    $mentor->id_mentor = $mentorDAO->insertar($mentor);
                }
            } 
            else {
                if ($mentor->estado == 'V')
                    $mentor->estado = 'A';
                $mentorDAO->actualizar($mentor);
            }

            if (count($mentor->listaEjeMentoria) > 0) {
                $mentorDAO->eliminarEjeMentoria($mentor->id_mentor);
                foreach ($mentor->listaEjeMentoria as &$valor) {
                    General::setNullSql($valor, "valor");
                    if ($valor->selected) {
                        $mentorDAO->insertarEjeMentoria($mentor->id_mentor, $valor->id, $valor->valor);
                    }
                }
            }

            $this->_insertHorarios($mentorDAO, $mentor->horarios, $mentor->id_mentor);

            $persona = $personaDAO->getPersona($mentor->id_persona);
            
            if (!General::tieneValor($persona, "id_usuario") && $crearUsuario) {
                $usuarioBO = new UsuarioBO();
                $usuarioBO->setConexion($this->conexion);
                $usuario = new stdClass();
                $usuario->usuario = $persona->email;
                $usuario->nombre = $persona->nombre;
                $usuario->apellido = $persona->apellido;
                $usuario->correo = $persona->email;
                $usuario->id_institucion = 2;
                $usuario->estado = 'A';
                $usuario->foto = null;
                $usuario->password = $persona->identificacion;
                $resp = $usuarioBO->grabarUsuario($usuario);
                if (General::tieneValor($resp, "codigo")) {
                    return $resp;
                }
                $mentor->id_usuario = $usuario->id_usuario;
                $persona->id_usuario = $usuario->id_usuario;
                $personaDAO->actualizarPersona($persona);
                
                $datosTrama = new stdClass();
                $datosTrama->id_persona = $mentor->id_persona;
                $mailBO = new MailBO();
                $mailBO->setConexion($this->conexion);
                //$mailBO->enviarCorreoTrama("MXUSU001", $datosTrama, null, null, "CREACION USUARIO MENTOR");
            }
            
            $usuPer = $usuarioDAO->getUsuarioXidPerfil($persona->id_usuario, 10);
            if(is_null($usuPer) && $crearUsuario){
                $usuarioDAO->insertarUsuarioPerfil($persona->id_usuario, "10");
            }

            if (isset($_FILES['file_cv'])) {
                $file = $_FILES['file_cv'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "persona_" . $persona->id_persona . "_cv." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $persona->cv = $nameImage;
                    $personaDAO->actualizarCV($persona);
                }
            }

            return $mentor;
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function grabarPeriodoMentor() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $periodo = json_decode($_POST["datos"]);
        try {
            $this->conectar();
            $mentorDAO = new MentorDAO();
            $mentorDAO->setConexion($this->conexion);
            if (!General::tieneValor($periodo, "id_periodo")) {
                $mentorDAO->insertarPeriodoMentor($periodo);
            } else {
                $mentorDAO->actualizarPeriodoMentor($periodo);
            }
            $periodos = $mentorDAO->getPeriodos($periodo->id_mentor);
            $this->cerrarConexion();
            return General::setRespuestaOK($periodos);
        } catch (Exception $ex) {
            return General::setRespuestaError($ex);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getMentoresXEjeMentoria() {
        $respuesta = General::validarParametros($_POST, ["id_eje_mentoria"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $mentorDAO = new MentorDAO();
            $mentorDAO->setConexion($this->conexion);
            $data = $mentorDAO->getMentorPorEjeMentoria($_POST["id_eje_mentoria"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

}
