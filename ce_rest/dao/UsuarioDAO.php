<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioDAO
 *
 * @author ernesto.ruales
 */
class UsuarioDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function getUsuarioLogin($usuario,$pass) {
        $sql = "select a.*, password('$pass') as password2, b.id as id_emprendedor, p.id as id_persona,
                       a.id as id_usuario, 
                       concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as url_foto,
                       c.id as id_asistente_tecnico,
                       d.id as id_mentor
                from ".basedatos::$baseSeguridad.".usuario a
                    left outer join persona p on p.id_usuario = a.id
                    left outer join emprendedor b on b.id_usuario = a.id
                    left outer join asistencia_tecnica c on c.id_persona = p.id
                    left outer join mentor d on d.id_persona = p.id
                where a.usuario = '$usuario'";
        return $this->con->getEntidad($sql);
    }
    
    function getUsuario($id_usuario) {
        $sql = "select a.*, b.id as id_emprendedor, p.id as id_persona,
                       a.id as id_usuario, 
                       concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as url_foto
                from ".basedatos::$baseSeguridad.".usuario a
                    left outer join persona p on p.id_usuario = a.id
                    left outer join emprendedor b on b.id_usuario = a.id
                where a.id = '$id_usuario'";
        return $this->con->getEntidad($sql);
    }
    
    function getUsuarioXCorreo($correo) {
        $sql = "select a.*, b.id as id_emprendedor
                from ".basedatos::$baseSeguridad.".usuario a
                    left outer join emprendedor b on b.id_usuario = a.id
                where a.correo = '$correo'";
        return $this->con->getEntidad($sql);
    }
    
    function insertarAcceso($acceso) {
        General::setNullSql($acceso, "ip");
        General::setNullSql($acceso, "mac_address");
        $sql = "INSERT INTO ".basedatos::$baseSeguridad.".`acceso_sistema`
                (`usuario`,`ip`,`mac_address`,`estado`)
                VALUES
                ('$acceso->usuario',$acceso->ip,$acceso->mac_address,'$acceso->estado');";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function actualizarFotoPerfil($usuario) {
        General::setNullSql($usuario, "foto");
        $sql = "UPDATE ".basedatos::$baseSeguridad.".`usuario` SET 
                foto = $usuario->foto
                WHERE id = '$usuario->id'
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    /*Estos metodos deben modificarse*/
    function insertarUsuario($usuario) {
        General::setNullSql($usuario, "foto");
        $sql = "INSERT INTO ".basedatos::$baseSeguridad.".`usuario`
                (
                `usuario`,`nombre`,`apellido`,`correo`,
                `id_institucion`,`estado`,`foto`,`password`)
                VALUES
                ('$usuario->usuario','$usuario->nombre','$usuario->apellido','$usuario->correo',
                 '$usuario->id_institucion','$usuario->estado',$usuario->foto,password('$usuario->password'));";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
    
    function actualizarUsuario($usuario) {
        $campos = array("usuario", "correo", "nombre", "apellido", "id_institucion",
            "estado", "foto");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$usuario->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($usuario->id_usuario);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar(basedatos::$baseSeguridad.".`usuario`",$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
        /*$sql = "UPDATE ".basedatos::$baseSeguridad.".`usuario` SET 
                usuario = '$usuario->usuario',
                estado = '$usuario->estado',
                foto = $usuario->foto,
                nombre = $usuario->nombre,
                correo = $usuario->correo,
                apellido = $usuario->apellido,
                apellido = $usuario->apellido
                WHERE id = '$usuario->id'
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));*/
    }

    function actualizarUsuarioEstado($id_usuario) {
        $sql = "UPDATE ".basedatos::$baseSeguridad.".`usuario` SET 
                estado = 'I'
                WHERE id = '$id_usuario'";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function insertarUsuarioPerfil($idUsuario, $idPerfil){
        $sql = "INSERT INTO ".basedatos::$baseSeguridad.".usuario_perfil VALUES ($idPerfil, $idUsuario)";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function getUsuarioXidPerfil($idUsuario, $idPerfil) {
        $sql = "
                select * from ".basedatos::$baseSeguridad.".usuario a
                inner join ".basedatos::$baseSeguridad.".usuario_perfil b on b.id_usuario = a.id
                where b.id_perfil = $idPerfil and b.id_usuario = $idUsuario
                ";
        return $this->con->getEntidad($sql);
    }
    
    function getUsuariosEpico() {
        $sql = "select  b.*,
		ifnull(b.nombre, a.nombre) as nombre,
                ifnull(b.apellido, a.apellido) as apellido,
                a.id as id_usuario,
                b.id as id_persona,
                ifnull(b.email, a.correo) as email,
                a.foto, a.usuario,
                concat('" . ArchivosBO::getURLArchivo() . "',a.foto) as url_foto
        from ".basedatos::$baseSeguridad.".usuario a
        left outer join persona b on b.id_usuario = a.id
        where a.id_institucion = 1 and a.id not in ('1', '444') and a.estado = 'A'";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
}
