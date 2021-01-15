<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EvaluacionBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/EvaluacionDAO.php';
require_once '../util/General.php';
require_once '../bo/ArchivosBO.php';
require_once '../bo/RubricaBO.php';

class EvaluacionBO extends BO {

    //put your code here
    public function getEvaluacion() {
        $respuesta = General::validarParametros($_POST, ["id_evaluacion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $respuesta = new stdClass();
        try {
            $evaluacionDAO = new EvaluacionDAO();

            $this->conectar();
            $evaluacionDAO->setConexion($this->conexion);

            $evaluacion = $evaluacionDAO->getEvaluacionxID($_POST['id_evaluacion']);
            if (!is_null($evaluacion)) {
                $evaluacion->criterios = $evaluacionDAO->getEvaluacionCriterioxIdEvaluacion($evaluacion->id_evaluacion);
                foreach ($evaluacion->criterios as &$criterio) {
                    $criterio->detalles = $evaluacionDAO->getEvaluacionDetallexIdCriterio($criterio->id);
                }
            }

            return General::setRespuesta("1", "Consulta éxitosa", $evaluacion);
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function grabarEvaluacion() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $evaluacion = json_decode($_POST["datos"]);
        try{
            $this->conectar();
            $evaluacionDAO = new EvaluacionDAO();
            $evaluacionDAO->setConexion($this->conexion);
            if(General::tieneValor($evaluacion, "id_evaluacion")){
                $evaluacionDAO->actualizarEvaluacion($evaluacion);
            }else{
                $evaluacion->id_evaluacion = $evaluacionDAO->insertarEvaluacion($evaluacion);
            }
            
            foreach ($evaluacion->criterios as &$criterio){
                if(General::tieneValor($criterio, "id_evaluacion_criterio")){
                    $evaluacionDAO->actualizarEvaluacionCriterio($criterio);
                }else{
                    $criterio->id_evaluacion = $evaluacion->id_evaluacion;
                    $criterio->id_evaluacion_criterio = $evaluacionDAO->insertarEvaluacionCriterio($criterio);
                }
                $evaluacionDAO->eliminarEvaluacionDetalle($criterio->id_evaluacion_criterio);
                foreach ($criterio->detalles as &$detalle){
                    if(General::tieneValor($detalle, "id_calificacion")){
                        $detalle->id_evaluacion_criterio = $criterio->id_evaluacion_criterio;
                        $evaluacionDAO->insertarEvaluacionDetalle($detalle);
                    }
                }
            }
            
            $rubricaBO = new RubricaBO();
            $rubricaBO->setConexion($this->conexion);
            if(General::tieneValor($evaluacion, "calificacion")){
                $evaluacion->mensaje = $rubricaBO->_getMensaje($evaluacion->id_rubrica, $evaluacion->calificacion);
            }
            
            return General::setRespuesta("1", "Se grabó con éxito", $evaluacion);
        }
        catch(Exception $e){
            return General::setRespuestaError($e);
        }
    }
    
    public function getEvaluacionXIds() {
        $respuesta = General::validarParametros($_POST, ["param", "id_rubrica", "conDetalles"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $param = json_decode($_POST["param"]);
        try {
            $evaluacionDAO = new EvaluacionDAO();

            $this->conectar();
            $evaluacionDAO->setConexion($this->conexion);

            $evaluacion = $evaluacionDAO->getEvaluacionxIds($param, $_POST['id_rubrica']);
            if (!is_null($evaluacion) && $_POST['conDetalles']=="SI") {
                $evaluacion->criterios = $evaluacionDAO->getEvaluacionCriterioxIdEvaluacion($evaluacion->id_evaluacion);
                foreach ($evaluacion->criterios as &$criterio) {
                    $criterio->detalles = $evaluacionDAO->getEvaluacionDetallexIdCriterio($criterio->id);
                }
            }

            return General::setRespuesta("1", "Consulta éxitosa", $evaluacion);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

}
