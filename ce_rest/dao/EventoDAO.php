<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventoDAO
 *
 * @author ernesto.ruales
 */
class EventoDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function getEventos($param) {
        $sql = "select y.*, (y.cupo - y.registrados) as disponibles
                  from (select a.*, b.nombre as tipo_evento, a.id as id_evento,
                        (select count(1) from agenda ag where ag.id_evento = a.id and estado <> 'CA') as registrados,
                        ta.nombre as tipo_asistencia
                from evento a
                left outer join tipo_asistencia ta on ta.id = a.id_tipo_asistencia
                inner join tipo_evento b on b.id = a.id_tipo_evento ";
        
        if(General::tieneValor($param, "id_etapa")){
            $sql .= " inner join actividad_etapa ae on ae.cod_referencia = a.codigo 
                    and ae.id_etapa = '$param->id_etapa' 
                    and ae.estado = 'A' ";
        }
        
        if(General::tieneValor($param, "id_persona")){
            $sql .= " inner join actividad_etapa act on act.cod_referencia = a.codigo and b.estado = 'A'
                      inner join inscripcion_edicion ins on ins.fase = act.id_etapa and ins.id_persona = '$param->id_persona' ";
        }
        
        $sql .= " where a.estado = (case '$param->estado' when 'T' then a.estado else '$param->estado' end) ";
        
        if(General::tieneValor($param, "id_tipo_evento")){
            $sql .= " and a.id_tipo_evento = '$param->id_tipo_evento'";
        }
        if(General::tieneValor($param, "codigo")){
            $sql .= " and a.codigo = '$param->codigo'";
        }
        if(General::tieneValor($param, "fecha_desde")){
            $sql .= " and a.fecha >= '$param->fecha_desde'";
        }
        $sql .= " ) y order by y.nombre asc, y.fecha desc";
        
        return $this->con->getArraySQL($sql);
    }
    
    function getEventosU($param) {
        $sql = "select a.*, b.nombre as tipo_evento, a.id as id_evento
                from evento a
                inner join tipo_evento b on b.id = a.id_tipo_evento
                where a.estado = (case '$param->estado' when 'T' then a.estado else '$param->estado' end)
                ";
        if(General::tieneValor($param, "id_tipo_evento")){
            $sql .= " and a.id_tipo_evento = '$param->id_tipo_evento'";
        }
        if(General::tieneValor($param, "codigo")){
            $sql .= " and a.codigo = '$param->codigo'";
        }
        if(General::tieneValor($param, "fecha_desde")){
            $sql .= " and a.fecha >= '$param->fecha_desde'";
        }
        $sql .= " group by a.nombre, a.id_tipo_evento, a.codigo, b.nombre";
        return $this->con->getArraySQL($sql);
    }
    
    function insertar($evento){        
        $campos=array("nombre","estado", "fecha", "hora_inicio",
            "hora_fin", "url", "id_tipo_evento", "codigo", "id_evento_mec", "color",
            "cupo", "id_tipo_asistencia");
        
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$evento->{$campo};
            $tipodatos[]="s";
        }
        return $this->con->Insertar("evento",$campos,$tipodatos,$valores);
    }
    
    function actualizar($evento){
        $campos=array("nombre","estado", "nombre", "fecha", "hora_inicio",
            "hora_fin", "url", "id_tipo_evento", "codigo", "id_evento_mec", "color",
            "cupo", "id_tipo_asistencia");
        $tabla="evento";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$evento->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($evento->id_evento);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
    }
}
