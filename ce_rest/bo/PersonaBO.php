<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonaBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/PersonaDAO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../dao/UsuarioDAO.php';
require_once '../dao/EmprendedorDAO.php';
require_once '../dao/EmprendimientoDAO.php';
require_once '../util/General.php';
require_once '../bo/LoginBO.php';
require_once '../bo/ArchivosBO.php';
require_once '../dao/MentorDAO.php';
require_once '../dao/AsistenciaTecnicaDAO.php';

class PersonaBO extends BO {
    
    public function insertarPersona() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $persona = json_decode($_POST["datos"]);
        $persona = $this->grabarPersona($persona);
        if($persona == null){
            return General::setRespuesta("0", "Persona ya existe", $persona);
        }
        return General::setRespuesta("1", "Se grabó con éxito", $persona);
    }

    //put your code here
    public function grabarPersona($persona) {

        $personaDAO = new PersonaDAO();
        $usuarioDAO = new UsuarioDAO();

        $this->conectar();
        try {
            $personaDAO->setConexion($this->conexion);
            $usuarioDAO->setConexion($this->conexion);

            if (General::tieneValor($persona, "id_persona") || General::tieneValor($persona, "id")) {
                if(General::tieneValor($persona, "estado")){
                    if($persona->estado == 'I'){
                        $personaDAO->actualizarPersona($persona);
                        $usuarioDAO->actualizarUsuarioEstado($persona->id_usuario);
                    } else{
                        $personaDAO->actualizarPersona($persona);
                    }
                } else{
                    $personaDAO->actualizarPersona($persona);
                }
            } else {
                $perid = $personaDAO->getPersonaXIdent($persona->identificacion);
                $pmail = $personaDAO->getPersonaXCorreo($persona->email);
                if (!is_null($perid)) {
                    return null;
                } elseif (!is_null($pmail)) {
                    return null;
                } else {
                    $persona->id = $personaDAO->insertarPersona($persona);
                    $persona->id_persona = $persona->id;
                }
            }
            
            if (General::tieneValor($persona, "id_usuario")) {
                $usuario = $usuarioDAO->getUsuario($persona->id_usuario);
                $usuario->nombre = $persona->nombre;
                $usuario->apellido = $persona->apellido;
                $usuarioDAO->actualizarUsuario($usuario);
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

            if (isset($persona->redes_sociales)) {
                if (!is_null($persona->redes_sociales)) {
                    $personaDAO->eliminarRedesSocialesPersona($persona->id_persona);
                    foreach ($persona->redes_sociales as &$valor) {
                        if (isset($valor->red)) {
                            if ($valor->red !== "") {
                                $personaDAO->insertarRedesSocialesPersona($persona->id_persona, $valor->id, $valor->red);
                            }
                        }
                    }
                }
            }

            if (isset($persona->intereses)) {
                if (!is_null($persona->intereses)) {
                    $personaDAO->eliminarInteresPersona($persona->id_persona);
                    foreach ($persona->intereses as &$valor) {
                        $personaDAO->insertarInteresPersona($persona->id_persona, $valor->id);
                    }
                }
            }

            return $persona;
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function actualizarPersona() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        //$mail = new Mail();
        $persona = json_decode($_POST["datos"]);
        $personaDAO = new PersonaDAO();
        $usuarioDAO = new UsuarioDAO();
        $this->conectar();
        try {
            $personaDAO->setConexion($this->conexion);
            $usuarioDAO->setConexion($this->conexion);

            //$correoBO = new CorreoBO();
            //$correoBO->setConexion($this->conexion);
            if (!General::tieneValor($persona, "id_usuario")) {
                $usuario = new stdClass();
                $usuario->nombre = $persona->nombre;
                $usuario->apellido = $persona->apellido;
                $usuario->usuario = $persona->email;
                $usuario->correo = $persona->email;
                $usuario->password = $persona->identificacion;
                $usuario->estado = 'A';
                $usuario->id_institucion = '2';
                $persona->id_usuario = $usuarioDAO->insertarUsuario($usuario);
                $usuarioDAO->insertarUsuarioPerfil($persona->id_usuario, "3");
                $persona->direccion = str_replace("'", "", $persona->direccion);

                $loginBO = new LoginBO();
                $loginBO->setConexion($this->conexion);
                $loginBO->validarSesion($usuario->usuario, $usuario->password);

                //$correo = $mail->getCorreoCreacionUsuario($usuario->nombre, $usuario->apellido, $usuario->usuario, $usuario->password);
                //$correo->destinatario = $usuario->correo;
                //$correoBO->enviarCorreoInmediato($correo, null);

                //$this->enviarCorreoPaso1($persona);
            }

            if (General::tieneValor($persona, "id_persona")) {
                $personaDAO->actualizarPersona($persona);
            }

            $personaDAO->eliminarRedesSocialesPersona($persona->id_persona);
            foreach ($persona->redes_sociales as &$valor) {
                if (isset($valor->red)) {
                    if ($valor->red !== "") {
                        $personaDAO->insertarRedesSocialesPersona($persona->id_persona, $valor->id, $valor->red);
                    }
                }
            }

            return General::setRespuesta("1", "Se actualizo con éxito", $persona);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getPersonaXIdent() {
        $respuesta = General::validarParametros($_POST, ["cedula"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $respuesta = new stdClass();
        try {
            $cedula = $_POST["cedula"];
            $personaDAO = new PersonaDAO();
            $emprendedorDAO = new EmprendedorDAO();
            $emprendimientoDAO = new EmprendimientoDAO();
            $mentorDAO = new MentorDAO();

            $this->conectar();
            $personaDAO->setConexion($this->conexion);
            $emprendedorDAO->setConexion($this->conexion);
            $emprendimientoDAO->setConexion($this->conexion);
            $mentorDAO->setConexion($this->conexion);

            $data = new stdClass();
            $data->persona = $personaDAO->getPersonaXIdent($cedula);

            if (is_null($data->persona)) {
                return General::setRespuesta("2", "Persona no registrada", null);
            }

            $data->emprendedor = $emprendedorDAO->getEmprendedorPorIdPersona($data->persona->id);
            $data->mentor = $mentorDAO->getMentorPorIdPersona($data->persona->id);
            if (!is_null($data->emprendedor))
                $data->emprendimiento = $emprendimientoDAO->getUltimoEmprendimiento($data->emprendedor->id);
            else {
                $data->listaEmprendimientos = array();
                $data->emprendedor = new stdClass();
            }
            if (is_null($data->mentor)) {
                $data->mentor = new stdClass();
            }

            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getPersona() {
        $respuesta = General::validarParametros($_POST, ["id_persona"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $personaDAO = new PersonaDAO();
            
            $this->conectar();
            $personaDAO->setConexion($this->conexion);
            
            $data = $personaDAO->getPersonaXId($_POST["id_persona"]);

            if (is_null($data)) {
                return General::setRespuesta("2", "Persona no registrada", null);
            }
            
            $data->redes_sociales = $personaDAO->getRedesSocialesPersona($_POST["id_persona"]);
            $data->intereses = $personaDAO->getInteresPersona($_POST["id_persona"]);

            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function getPersonas() {
        try {
            $this->conectar();
            $personaDAO = new PersonaDAO();
            $personaDAO->setConexion($this->conexion);
            $data = $personaDAO->getPersonas();
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }    

    public function getPerfil() {
        try {
            $personaDAO = new PersonaDAO();
            $emprendedorDAO = new EmprendedorDAO();
            $emprendimientoDAO = new EmprendimientoDAO();
            $mentorDAO = new MentorDAO();
            $asistenciaTecnicaDAO = new AsistenciaTecnicaDAO();

            $this->conectar();
            $personaDAO->setConexion($this->conexion);
            $emprendedorDAO->setConexion($this->conexion);
            $emprendimientoDAO->setConexion($this->conexion);
            $mentorDAO->setConexion($this->conexion);
            $asistenciaTecnicaDAO->setConexion($this->conexion);

            $data = new stdClass();
            $data->persona = $personaDAO->getPersonaXIdUsuario($this->user->id);
            $id_persona = null;
            if(!is_null($data->persona)){
                $id_persona = $data->persona->id_persona;
            }
            $data->avance = $this->getAvancePerfil($id_persona);
            if (!is_null($data->persona)) {
                $data->persona->redes_sociales = $personaDAO->getRedesSocialesPersona($data->persona->id);
                foreach ($data->persona->redes_sociales as &$red){
                    $data->avance->total++;
                    if(!is_null($red->red)){
                        $data->avance->completado++;
                    }
                }
                $data->avance->avance=round($data->avance->completado/$data->avance->total * 100, 2);
                
                $data->persona->intereses = $personaDAO->getInteresPersona($data->persona->id);

                if (is_null($data->persona)) {
                    return General::setRespuesta("2", "Persona no registrada", null);
                }

                $data->emprendedor = $emprendedorDAO->getEmprendedorPorIdPersona($data->persona->id);
                $data->mentor = $mentorDAO->getMentorPorIdPersona($data->persona->id);
                $data->asistente_tecnico = $asistenciaTecnicaDAO->getAsistentePorIdPersona($data->persona->id);

                if (!is_null($data->emprendedor))
                    $data->listaEmprendimientos = $emprendimientoDAO->getEmprendimientoConsulta($data->emprendedor->id_emprendedor, 'T');
                else {
                    $data->listaEmprendimientos = array();
                    $data->emprendedor = new stdClass();
                }
            } 
            else {
                $data->persona = new stdClass();
                $data->emprendedor = new stdClass();
                $data->mentor = new stdClass();
                $data->listaEmprendimientos = array();
            }
            return General::setRespuesta("1", "Consulta éxitosa", $data);
        }
        catch (Exception $ex){
            return General::setRespuestaError($ex);
        }
        finally {
            $this->cerrarConexion();
        }
    }

    public function getPeronasXActividad() {
        $respuesta = General::validarParametros($_POST, ["id_actividad", "tipo"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $consultasDAO = new ConsultasDAO();
            $this->conectar();
            $consultasDAO->setConexion($this->conexion);
            switch ($_POST["tipo"]){
                case "ASIGNADOS": 
                    $data = $consultasDAO->getPersonasActividadAsig($_POST["id_actividad"]);
                    break;
                case "NOASIGNADOS": 
                    $data = $consultasDAO->getPersonasActividadNoAsig($_POST["id_actividad"]);
                    break;
                case "TODOS": 
                    $data = $consultasDAO->getPersonasActividad($_POST["id_actividad"]);
                    break;
                default :
                    $data = null;
                    break;
            }
            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    private function getAvancePerfil($id_persona){
        $personaDAO = new PersonaDAO();
        $this->conectar();
        $personaDAO->setConexion($this->conexion);
        $persona = $personaDAO->getPersona($id_persona);
        $avance = new stdClass();
        $avance->completado=0;
        $avance->total=0;
        $avance->avance=0;
        foreach ($persona as $nombre => $valor) {
            $avance->total++;
            if(!is_null($valor)){
                $avance->completado++;
            }
        }
        $avance->avance=round($avance->completado/$avance->total * 100, 2);
        return $avance;
    }
    
    public function getRedesSocialesPersona() {
        $respuesta = General::validarParametros($_POST, ["id_persona"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $personaDAO = new PersonaDAO();
            
            $this->conectar();
            $personaDAO->setConexion($this->conexion);
            
            $data = $personaDAO->getRedesSocialesPersona($_POST["id_persona"]);

            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
}
