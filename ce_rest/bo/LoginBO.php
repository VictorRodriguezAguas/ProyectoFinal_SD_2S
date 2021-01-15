<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginBO
 *
 * @author ernesto.ruales
 */
$newUrl = URL::getUrlLibreria();

require_once '../dao/UsuarioDAO.php';
require_once '../util/General.php';
require_once '../bo/BO.php';
//require_once '../servicio/Sesion.php';
require_once '../bo/PerfilBO.php';
require_once '../bo/auth.php';

class LoginBO extends BO {
    
    //put your code here
    public function validarLogin() {
        $respuesta = General::validarParametros($_POST, ["usuario", "password"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        return $this->validarSesion($_POST["usuario"], $_POST["password"]);
    }
   
    public function validarSesion($user, $password) {
        $this->conectar();
        try {
            $usuarioDAO = new UsuarioDAO();
            $usuarioDAO->setConexion($this->conexion);
            $usuario = $usuarioDAO->getUsuarioLogin($user, $password);
            $acceso = new stdClass();
            $acceso->usuario = $user;
            $acceso->ip = General::getRealIP();
            if (is_null($usuario)) {
                $acceso->estado = "UE";
                $usuarioDAO->insertarAcceso($acceso);
                return General::setRespuesta("2", "Usuario no existe", null);
            }
            if ($usuario->estado == 'I') {
                $acceso->estado = "UI";
                $usuarioDAO->insertarAcceso($acceso);
                return General::setRespuesta("3", "Usuario se encuentra inactivo", null);
            }
            if ($usuario->password != $usuario->password2) {
                $acceso->estado = "PE";
                $usuarioDAO->insertarAcceso($acceso);
                return General::setRespuesta("4", "Usuario/Password incorrectos", null);
            }
            $acceso->estado = "IE";
            $usuarioDAO->insertarAcceso($acceso);
            
            $perfilBO = new PerfilBO();
            $perfilBO->setConexion($this->conexion);
            $usuario->emprendedor = 0;
            $usuario->mesa_servicio = 0;
            $usuario->mentor = 0;
            $usuario->asistencia_tecnica = 0;          
            $usuario->administrador = 0;
            
            //session_start();
            $usuario->password = "";
            $usuario->password2 = "";
            
            $perfil_emp = $perfilBO->getPerfilXUsuario($usuario->id, "3");
            if(!is_null($perfil_emp->data))
                $usuario->emprendedor = 1;
            $perfil_emp = $perfilBO->getPerfilXUsuario($usuario->id, "5");
            if(!is_null($perfil_emp->data))
                $usuario->mesa_servicio = 1;
            $perfil_emp = $perfilBO->getPerfilXUsuario($usuario->id, "10");
            if(!is_null($perfil_emp->data))
                $usuario->mentor = 1;
            $perfil_emp = $perfilBO->getPerfilXUsuario($usuario->id, "11");
            if(!is_null($perfil_emp->data))
                $usuario->asistencia_tecnica = 1;
            $perfil_emp = $perfilBO->getPerfilXUsuario($usuario->id, "1");
            if(!is_null($perfil_emp->data))
                $usuario->administrador = 1;
            //$_SESSION[Sesion::$usuarioName] = serialize($usuario);
            //$token = Auth::SignIn($usuario);
            return General::setRespuesta("1", "Ingreso éxitoso", $usuario);
        } catch (Exception $ex){
            return General::setRespuesta("0", $ex->getMessage(), null);
        }
        finally {
            $this->cerrarConexion();
        } 
    }

}
