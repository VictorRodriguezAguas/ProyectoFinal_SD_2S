<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PerfilDAO
 *
 * @author ernesto.ruales
 */
class PerfilDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function cargarPerfil($row){
        $respuesta = new stdClass();
        $respuesta->id = $row["id"];
        $respuesta->nombre = $row["nombre"];
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }
    
    function getPerfilxUsuario($id_usuario, $id_perfil) {
        $sql = "select b.* 
                from ".basedatos::$baseSeguridad.".usuario_perfil a 
               inner join ".basedatos::$baseSeguridad.".perfil b on b.id = a.id_perfil
               where a.id_usuario =  '$id_usuario' ";
        if(!is_null($id_perfil)){
            $sql .=" and a.id_perfil = '$id_perfil'";
            $resultado_consulta = $this->con->getConexion()->query($sql);
            $respuesta = null;
            if ($row = $resultado_consulta->fetch_array()) {
                $respuesta=$this->cargarPerfil($row);
            }
            return $respuesta;
        }else{
            $lista = array();
            $i=0;
            if ($row = $resultado_consulta->fetch_array()) {
                $lista[$i] = $this->cargarPerfil($row);
                $i++;
            }
            return $lista;
        }
    }
    
    function insertarPerfil($perfil){
        $sql = "INSERT INTO ".basedatos::$baseSeguridad.".`perfil`
            (`nombre`,`estado`)
            VALUES('$perfil->nombre','$perfil->estado');
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
    
    function actualizarPerfil($perfil){
        $sql = "UPDATE ".basedatos::$baseSeguridad.".`perfil` SET nombre = '$perfil->nombre',
            estado = '$perfil->estado'
            where id = $perfil->id_perfil
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function consultarPerfil($nombre, $estado) {
         $sql = "select a.*, a.id as id_perfil
                  from ".basedatos::$baseSeguridad.".perfil a
                  where a.estado = (case '$estado' when 'T' then a.estado else '$estado' end)
                    and a.nombre like '%$nombre%'
                  order by a.id";
        return $this->con->getArraySQL($sql);
    }
    
    function getPerfilxNombre($nombre) {
        $respuesta = null;
        $sql = "select * from perfil where nombre = '$nombre'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        if ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->id = $row["id"];
            $respuesta->nombre = $row["nombre"];
            $respuesta->estado = $row["estado"];
        }
        return $respuesta;
    }
    
    function insertarPerfilMenu($menu){
        $sql = "INSERT INTO ".basedatos::$baseSeguridad.".`perfil_menu`
            (`id_menu`,`id_perfil`)
            VALUES('$menu->id','$menu->id_perfil');
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function eliminarMenuPerfil($id_perfil, $id_aplicacion){
        $sql = "delete from ".basedatos::$baseSeguridad.".perfil_menu where id_perfil = $id_perfil 
                and id_menu in (select id from ".basedatos::$baseSeguridad.".menu where id_aplicacion = $id_aplicacion)
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
}
