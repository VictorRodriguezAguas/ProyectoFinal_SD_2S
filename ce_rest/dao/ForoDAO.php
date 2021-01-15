<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ForoDAO
 *
 * @author ernesto.ruales
 */
class ForoDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function insert($foro) {        
        $campos=array("tema","fecha_inicio", "id_usuario_registro", "id_prioridad", "id_tipo_tematica",
            "estado", "contenido");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $foro->{$campo};
            $tipodatos[] = "s";
        }
        $id = $this->con->Insertar("foro", $campos, $tipodatos, $valores);
        if($this->con->getError() == '1'){
            throw new Exception($this->con->getMensajeError(), $this->con->getCodigoError());
        }
        return $id;
    }
    
    function update($foro) {     
        $campos=array("tema","fecha_inicio", "id_usuario_registro", "id_prioridad", "id_tipo_tematica",
            "estado", "contenido", "fecha_fin");
        $tabla="foro";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$foro->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($foro->id_foro);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() == '1'){
            throw new Exception($this->con->getMensajeError(), $this->con->getCodigoError());
        }
    }
    
    function getForo($id_foro){
        $sql = "select a.*, b.nombre, b.apellido, c.nombre as prioridad, d.nombre as tematica,
                        concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as url_foto
            from foro a
            inner join ".basedatos::$baseSeguridad.".usuario b on b.id = a.id_usuario_registro
            inner join foro_prioridad c on c.id = a.id_prioridad
            inner join foro_tipo_tematica d on d.id = a.id_tipo_tematica
            where a.id = '$id_foro'";
        return $this->con->getEntidad($sql);
    }
    
    function getForos($param2){
        $paramAux=new stdClass();
        $paramAux->id_tipo_tematica = null;
        $paramAux->id_prioridad = null;
        $paramAux->id_usuario_registro = null;
        $paramAux->nombre = null;
        $paramAux->fecha_desde = null;
        $paramAux->fecha_hasta = null;
        $paramAux->estado = 'T';
        $paramAux->tema = null;
        
        $param = (object)array_merge((array)$paramAux, (array)$param2);
  
        $sql = "select a.*, b.nombre, b.apellido, c.nombre as prioridad, d.nombre as tematica,
                    concat(ps.valor,b.foto) as url_foto
            from foro a
            inner join ".basedatos::$baseSeguridad.".usuario b on b.id = a.id_usuario_registro
            inner join foro_prioridad c on c.id = a.id_prioridad
            inner join foro_tipo_tematica d on d.id = a.id_tipo_tematica
            left outer join parametro_sistema ps on ps.nombre = 'RUTA_ARCHIVOS_URL'
            where a.estado = (case '$param->estado' when 'T' then a.estado else '$param->estado' end) ";
        if(!is_null($param->id_tipo_tematica)){
            $sql .= " and a.id_tipo_tematica = '$param->id_tipo_tematica' ";
        }
        if(!is_null($param->id_prioridad)){
            $sql .= " and a.id_prioridad = '$param->id_prioridad' ";
        }
        if(!is_null($param->id_usuario_registro)){
            $sql .= " and a.id_usuario_registro = '$param->id_usuario_registro' ";
        }
        if(!is_null($param->nombre)){
            $sql .= " and concat(b.nombre, ' ', b.apellido) like '%$param->nombre%' ";
        }
        if(!is_null($param->tema)){
            $sql .= " and a.tema like '%$param->tema%' ";
        }
        if(!is_null($param->fecha_desde)){
            $sql .= " and a.fecha_inicio >= '$param->fecha_desde' ";
        }
        if(!is_null($param->fecha_hasta)){
            $sql .= " and a.fecha_inicio <= '$param->fecha_hasta' ";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function getRespuestas($id_foro){
        $sql = "select a.*, a.id as id_respuesta,
                        b.nombre, b.apellido,
                        concat(b.nombre, ' ', b.apellido) as nombre_completo,
                        concat(ps.valor,b.foto) as url_foto
               from foro_respuesta a
              inner join ".basedatos::$baseSeguridad.".usuario b on b.id = a.id_usuario
              left outer join parametro_sistema ps on ps.nombre = 'RUTA_ARCHIVOS_URL'
              where a.id_foro = '$id_foro'
              order by a.id_respuesta_padre";
        return $this->con->getArrayHash($sql, "id_respuesta");
    }
    
    function insertarRespuesta($respuesta) {        
        $campos=array("id_usuario","id_foro", "contenido", "estado", "id_respuesta_padre");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $respuesta->{$campo};
            $tipodatos[] = "s";
        }
        $id = $this->con->Insertar("foro_respuesta", $campos, $tipodatos, $valores);
        if($this->con->getError() == '1'){
            throw new Exception($this->con->getMensajeError(), $this->con->getCodigoError());
        }
        return $id;
    }
    
    function updateRespuesta($respuesta) {
        $paramAux=new stdClass();
        $paramAux->observacion_baja = null;
        $paramAux->id_usuario_baja = null;
        
        $respuesta = (object)array_merge((array)$paramAux, (array)$respuesta);
        
        $campos=array("contenido", "estado", "calificacion", "observacion_baja", "id_usuario_baja");
        $tabla="foro_respuesta";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$respuesta->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($respuesta->id_respuesta);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() == '1'){
            throw new Exception($this->con->getMensajeError(), $this->con->getCodigoError());
        }
    }
}
