<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Sesion {
    public static $usuarioName = "epicoUserAppCe";
    
    public static function validarSesion($band) {
        $respuesta = new stdClass();
        if(!isset($_SESSION))
            session_start();
        if (!isset($_SESSION[Sesion::$usuarioName]) || $_SESSION[Sesion::$usuarioName] == null) {
            if($band == 1){
                $respuesta->codigo = "-1";
                $respuesta->mensaje = "Su sesión ha caducado por favor inicie sesión nuevamente";
                print json_encode($respuesta);
            }else{
                print "<script>alert('Su sesion ha caducado');window.location='logout.php';</script>";
            }
            return false;
        }
        return true;
    }
    
    public static function getUsuarioSesion(){
        if(!isset($_SESSION))
            session_start();
        $user = unserialize($_SESSION[Sesion::$usuarioName]);
        return $user;
    }
}
