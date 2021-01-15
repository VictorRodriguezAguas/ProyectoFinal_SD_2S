<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/UsuarioDAO.php';
require_once '../util/General.php';
require_once '../bo/ArchivosBO.php';
require_once '../dao/ConsultasDAO.php';

class UsuarioBO extends BO {

    //put your code here
    public function getUsuarioxUsuario() {
        $respuesta = General::validarParametros($_POST, ["usuario"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $respuesta = new stdClass();
        try {
            $user = $_POST["usuario"];
            $usuarioDAO = new UsuarioDAO();
            $this->conectar();
            $usuarioDAO->setConexion($this->conexion);

            $data = $usuarioDAO->getUsuarioLogin($user, "");

            if (is_null($data)) {
                $data = $usuarioDAO->getUsuarioXCorreo($user);
                if (is_null($data)) {
                    return General::setRespuesta("2", "Usuario no existe", null);
                }
            }
            $data->password = "";
            $data->password2 = "";
            return General::setRespuesta("1", "Usuario existe", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function grabarFotoPerfil() {
        try {
            $usuarioDAO = new UsuarioDAO();
            $consultaDAO = new ConsultasDAO();
            $this->conectar();
            $usuarioDAO->setConexion($this->conexion);
            $consultaDAO->setConexion($this->conexion);
            $data = new stdClass();
            if (isset($_FILES['fotoPerfil'])) {
                $data->mensaje1="Entro por file";
                $file = $_FILES['fotoPerfil'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "foto_perfil_" . $this->user->id . "." . $ext;
                    $mensaje = ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    if ($mensaje != 'OK')
                        return General::setRespuesta("0", $mensaje, $this->user);
                    $this->user->foto = $nameImage;
                }
            }else{
                $data->mensaje2="Entro base";
                if(isset($_POST["ImagenBase"])){
                    $data->mensaje3="Si tiene base";
                    $baseImagen = $_POST["ImagenBase"];
                    if(!is_null($baseImagen)){
                        $nameImage = "foto_perfil_" . $this->user->id . ".png";
                        if (!ArchivosBO::guardarArchivoBase64($baseImagen, $nameImage)) {
                            return General::setRespuesta("0", "La imagen no es valida", null);
                        }
                        $this->user->foto = $nameImage;
                    }
                }
            }
            $url = $consultaDAO->getParamSystem("RUTA_ARCHIVOS_URL");
            $usuarioDAO->actualizarFotoPerfil($this->user);
            $this->user->foto = str_replace("'", "", $this->user->foto);
            $this->user->url_foto = $url . $this->user->foto;

            return General::setRespuesta("1", "Se grabó con éxito", $this->user);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function saveMeetingFile() {
        try {
            $urlFile = "";
            $usuarioDAO = new UsuarioDAO();
            $consultaDAO = new ConsultasDAO();
            $this->conectar();
            $usuarioDAO->setConexion($this->conexion);
            $consultaDAO->setConexion($this->conexion);
            $url = $consultaDAO->getParamSystem("RUTA_ARCHIVOS_URL");
            if (isset($_FILES['archivoReunion'])) {
                $file = $_FILES['archivoReunion'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "archivo_reunion_" . $this->user->id . "." . $ext;
                    $mensaje = ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $urlFile = $nameImage;
                    if($mensaje!='OK')
                        return General::setRespuesta("0", $mensaje, $this->user);
                    $this->user->foto = $nameImage;
                }
            }

            return General::setRespuesta("1", "Se grabó con éxito", $urlFile);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getUsuariosEpico() {
        try {
            $usuarioDAO = new UsuarioDAO();
            $this->conectar();
            $usuarioDAO->setConexion($this->conexion);
            $data = $usuarioDAO->getUsuariosEpico();
            return General::setRespuesta("1", "Consulta éxitosa", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getUsuario() {
        $respuesta = General::validarParametros($_POST, ["id_usuario"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $usuarioDAO = new UsuarioDAO();
            $this->conectar();
            $usuarioDAO->setConexion($this->conexion);

            $data = $usuarioDAO->getUsuario($_POST["id_usuario"]);

            if (is_null($data)) {
                return General::setRespuesta("2", "Usuario no existe", null);
            }
            $data->password = "";
            $data->password2 = "";
            return General::setRespuesta("1", "Usuario existe", $data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function insertarUsuario() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $usuario = json_decode($_POST["datos"]);
        $usuario = $this->grabarUsuario($usuario);
        return General::setRespuesta("1", "Se grabó con éxito", $usuario);
    }


    public function grabarUsuario($usuario) {
        $usuarioDAO = new UsuarioDAO();
        $this->conectar();
        try {
            $usuarioDAO->setConexion($this->conexion);
            if(!General::tieneValor($usuario, "id") && !General::tieneValor($usuario, "id_usuario")){
                $usu = $usuarioDAO->getUsuarioXCorreo($usuario->correo);
                if(!is_null($usu)){
                    return General::setRespuesta("0", "Ya existe un usuario creado con este correo");
                }
                $usu = $usuarioDAO->getUsuarioLogin($usuario->usuario, "");
                if(!is_null($usu)){
                    return General::setRespuesta("0", "Usuario ya existe");
                }
                $usuario->id = $usuarioDAO->insertarUsuario($usuario);
                $usuario->id_usuario = $usuario->id;
            }else{
                $usuarioDAO->actualizarUsuario($usuario);
            }
            return $usuario;
        } 
        finally {
            $this->cerrarConexion();
        }
    }

}
