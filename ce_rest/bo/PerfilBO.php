<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PerfilBO
 *
 * @author ernesto.ruales
 */
$newUrl = URL::getUrlLibreria();

require_once '../dao/PerfilDAO.php';
require_once '../util/General.php';
require_once '../bo/BO.php';

class PerfilBO extends BO {
    //put your code here
    public function getPerfilXUsuario($id_user, $id_perfil) {
        //$user = Sesion::getUsuarioSesion();
        $this->conectar();
        $perfilDAO = new PerfilDAO();
        $perfilDAO->setConexion($this->conexion);
        $data = $perfilDAO->getPerfilxUsuario($id_user, $id_perfil);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function consultarPerfiles() {
        $respuesta = General::validarParametros($_POST, ["estado", "nombre"]);
        if(!is_null($respuesta)){
            return $respuesta;
        }
        $this->conectar();
        $perfilDAO = new PerfilDAO();
        $perfilDAO->setConexion($this->conexion);
        $data = $perfilDAO->consultarPerfil($_POST["nombre"], $_POST["estado"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function grabarPerfil() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $perfil = json_decode($_POST["datos"]);
        if (General::tieneValor($perfil, "id_perfil")) {
            return $this->actualizarPerfil($perfil);
        } else {
            return $this->crearPerfil($perfil);
        }
    }
    
    public function crearPerfil($perfil) {
        try {
            $this->conectar();
            $perfilDAO = new PerfilDAO();
            $perfilDAO->setConexion($this->conexion);
            
            $perfil->id = $perfilDAO->insertarPerfil($perfil);
            $perfil->id_perfil = $perfil->id;
            $this->cerrarConexion();
            return General::setRespuesta("1", "Perfil grabado con éxito", $perfil);
        } catch (Exception $e) {
            return General::setRespuestaError($e);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function actualizarPerfil($perfil) {
        try {
            $this->conectar();
            $perfilDAO = new PerfilDAO();
            $perfilDAO->setConexion($this->conexion);
            $perfilDAO->actualizarPerfil($perfil);
            $this->cerrarConexion();
            return General::setRespuesta("1", "Perfil actualizado con éxito", $perfil);
        } catch (Exception $e) {
            return General::setRespuestaError($e);
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function asignarMenuPerfil() {
        $respuesta = General::validarParametros($_POST, ["datos", "id_perfil", "id_aplicacion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $menus = json_decode($_POST["datos"]);
        $id_perfil = $_POST['id_perfil'];
        $id_aplicacion = $_POST['id_aplicacion'];
        try {
            $this->conectar();
            $perfilDAO = new PerfilDAO();
            $perfilDAO->setConexion($this->conexion);
            
            $perfilDAO->eliminarMenuPerfil($id_perfil, $id_aplicacion);
            foreach ($menus as &$menu){
                $menu->id_perfil = $id_perfil;
                if($menu->selected)
                    $perfilDAO->insertarPerfilMenu($menu);
            }
            $this->cerrarConexion();
            return General::setRespuesta("1", "Asignación éxitosa", null);
        } catch (Exception $e) {
            return General::setRespuestaError($e);
        } finally {
            $this->cerrarConexion();
        }
    }
    
}
