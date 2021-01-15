<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RubricaDAO
 *
 * @author ernesto.ruales
 */
class RubricaDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getRubricas($estado) {
        $sql = "select * from rubrica_ce where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getRubricaDetalleID($id) {
        $sql = "select  
		a.id as id_rubrica, a.nombre as rubrica, a.tipo as tipo_rubrica, a.calificacion as calificacion_rubrica, a.calificacion_min as calificacion_min_rubrica,
                b.id as id_rubrica_criterio, b.id_criterio, c.nombre as criterio, b.calificacion as calificacion_criterio, b.ponderado as ponderado_criterio, b.descripcion, b.orden as orden_criterio,
                d.id as id_rubrica_criterio_pregunta, d.id_pregunta, d.calificacion as calificacion_rubrica_pregunta, d.ponderado as ponderado_rubrica_pregunta, d.orden as orden_rubrica_pregunta,
                e.id_calificacion, f.nombre as nombre_calificacion, e.calificacion, e.ponderado
          from rubrica_ce a
         inner join rubrica_criterio_ce b on b.id_rubrica = a.id
         inner join criterio_ce c on c.id = b.id_criterio
         inner join rubrica_criterio_pregunta_ce d on d.id_rubrica_criterio = b.id and d.estado = 'A'
         inner join rubrica_criterio_pregunta_calificacion_ce e on e.id_rubrica_criterio_pregunta = a.id 
         inner join calificacion_ce f on f.id = e.id_calificacion
         where a.id = '$id';";
        return $this->con->getArraySQL($sql);
    }
    
    function getRubricaxID($id, $estado=null){
        $sql = "select a.*, a.id as id_rubrica from rubrica_ce a where id = '$id' ";
        if(!is_null($estado)){
            $sql .= " and a.estado = '$estado' ";
        }
        return $this->con->getEntidad($sql);
    }
    
    function getCriteriosxIdRubrica($id, $estado=null){
        $sql = "select a.*, a.id as id_rubrica_criterio, b.nombre as criterio
                  from rubrica_criterio_ce a
                 inner join criterio_ce b on b.id = a.id_criterio
                 where a.id_rubrica = '$id' ";
        if(!is_null($estado)){
            $sql .= " and a.estado = '$estado' ";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function getPreguntaxIdCriterio($id, $estado=null){
        $sql = "select a.*, a.id as id_rubrica_pregunta, b.nombre as pregunta
                  from rubrica_criterio_pregunta_ce a
                 inner join pregunta_ce b on b.id = a.id_pregunta
                 where a.id_rubrica_criterio = '$id'";
        if(!is_null($estado)){
            $sql .= " and a.estado = '$estado' ";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function getCalificacionxIdPregunta($id, $estado=null){
        $sql = "select a.*, b.nombre
                  from rubrica_criterio_pregunta_calificacion_ce a
                 inner join calificacion_ce b on b.id = a.id_calificacion
                 where a.id_rubrica_criterio_pregunta = '$id'";
        if(!is_null($estado)){
            $sql .= " and a.estado = '$estado' ";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function getMensajeIdRubrica($id){
        $sql = "select a.* from rubrica_ce_mensaje a where id_rubrica = '$id'";
        return $this->con->getArraySQL($sql);
    }
    
}
