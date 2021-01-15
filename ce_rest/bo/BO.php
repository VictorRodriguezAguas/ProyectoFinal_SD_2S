<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BO
 *
 * @author ernesto.ruales
 */
include_once '../util/basedatos.php';
include_once '../servicio/Sesion.php';
include_once '../bo/ArchivosBO.php';

class BO {

    //put your code here
    protected $conexion = null;
    private $bandHerencia = 0;
    protected $user = null;
    
    function setUser($user){
        $this->user = $user;
    }

    function setConexion($con) {
        $this->conexion = $con;
        $this->bandHerencia = 1;
    }

    function cerrarConexion() {
        if ($this->bandHerencia == 0) {
            if (!is_null($this->conexion)) {
                $this->conexion->cerrarConexion();
                $this->conexion = null;
            }
        }
    }

    function conectar() {
        $args = func_get_args();
        if (is_null($this->conexion)) {
            $this->conexion = new basedatos();
            call_user_func_array(array($this->conexion, 'conectar'), $args);
        }
    }

    function conectarEsquemaSeguridad() {
        $args = func_get_args();
        if (is_null($this->conexion)) {
            $this->conexion = new basedatos();
            $this->conexion->conectar("localhost", basedatos::$baseSeguridad);
        }
    }

}
