<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EvaluacionDAO
 *
 * @author ernesto.ruales
 */
class EvaluacionDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function getEvaluacionxID($id){
        $sql = "select a.*, a.id as id_evaluacion from evaluacion_ce a where a.id = '$id'";
        return $this->con->getEntidad($sql);
    }
    
    function getEvaluacionxIds($param, $id_rubrica){
        $sql = "select a.*, a.id as id_evaluacion 
                from evaluacion_ce a 
                where a.id_rubrica = '$id_rubrica'
                 ";
        foreach ($param as $nombre => $valor) {
            $sql .=" and a.$nombre = '$valor' ";
        }
        return $this->con->getEntidad($sql);
    }
    
    function getEvaluacionCriterioxIdEvaluacion($id){
        $sql = "select a.*, a.id as id_evaluacion_criterio from evaluacion_criterio_ce a where a.id_evaluacion = '$id'";
        return $this->con->getArraySQL($sql);
    }
    
    function getEvaluacionDetallexIdCriterio($id){
        $sql = "select a.* from evaluacion_detalle_ce a where a.id_evaluacion_criterio = '$id'";
        return $this->con->getArraySQL($sql);
    }
    
    function insertarEvaluacion($evaluacion) {
        $campos=array("concepto","id_rubrica", "calificacion", "aprobado",
            "id_evaluador", "id_evaluado", "id_usuario_evaluador", "id_reunion", "id_emprendimiento", 
            "id_emprendedor", "id_mentor", "id_asistencia_tecnica");
        
        $sql = General::insertSQL("evaluacion_ce", $evaluacion, $campos);
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
    
    function actualizarEvaluacion($evaluacion) {
        $campos=array("concepto","calificacion", "aprobado", "id_reunion", "id_emprendimiento",
            "id_mentor", "id_asistencia_tecnica");
        $filtro = " WHERE id = '$evaluacion->id_evaluacion' ";
        $sql = General::updateSQL("evaluacion_ce", $evaluacion, $campos, $filtro);
        
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function insertarEvaluacionCriterio($criterio) {
        $campos=array("id_evaluacion","id_rubrica_criterio", "calificacion_conf", "ponderado_conf",
            "calificacion_total", "calificacion_pon");
        
        $sql = General::insertSQL("evaluacion_criterio_ce", $criterio, $campos);
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
    
    function actualizarEvaluacionCriterio($criterio) {
        $campos=array("calificacion_conf","ponderado_conf", "calificacion_total", "calificacion_pon");
        $filtro = " WHERE id = '$criterio->id_evaluacion_criterio' ";
        $sql = General::updateSQL("evaluacion_criterio_ce", $criterio, $campos, $filtro);
        
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function eliminarEvaluacionDetalle($id_evaluacion_criterio) {
        $sql = "delete from evaluacion_detalle_ce where id_evaluacion_criterio = '$id_evaluacion_criterio'";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function insertarEvaluacionDetalle($detalle) {
        $campos=array("id_evaluacion_criterio","id_rubrica_pregunta", "calificacion_conf", "ponderado_conf",
            "calificacion", "ponderado", "id_calificacion", "observacion");
        
        $sql = General::insertSQL("evaluacion_detalle_ce", $detalle, $campos);
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
}
