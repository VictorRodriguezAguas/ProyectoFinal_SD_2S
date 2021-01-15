<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RubricaBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/RubricaDAO.php';
require_once '../util/General.php';
require_once '../bo/ArchivosBO.php';

class RubricaBO extends BO {
    //put your code here
    
    public function getRubricaEvaluacion() {
        $respuesta = General::validarParametros($_POST, ["id_rubrica"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $respuesta = new stdClass();
        try {
            $id_rubrica = $_POST["id_rubrica"];
            $rubricaDAO = new RubricaDAO();

            $this->conectar();
            $rubricaDAO->setConexion($this->conexion);

            $rubrica = $rubricaDAO->getRubricaxID($_POST['id_rubrica'], "A");
            if(is_null($rubrica)){
                return General::setRespuesta("0", "No se encontro datos en la consulta", $rubrica);
            }
            $rubrica->criterios = $rubricaDAO->getCriteriosxIdRubrica($rubrica->id, "A");
            foreach ($rubrica->criterios as &$criterio){
                $criterio->preguntas = $rubricaDAO->getPreguntaxIdCriterio($criterio->id_rubrica_criterio, "A");
                foreach ($criterio->preguntas as &$pregunta){
                    $pregunta->calificaciones = $rubricaDAO->getCalificacionxIdPregunta($pregunta->id, "A");
                }
            }
            
            return General::setRespuesta("1", "Consulta éxitosa", $rubrica);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getMensaje() {
        $respuesta = General::validarParametros($_POST, ["id_rubrica", "calificacion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $mensaje = $this->_getMensaje($_POST["id_rubrica"], $_POST["calificacion"]);
        return General::setRespuesta("1", "Consulta éxitosa", $mensaje);
    }
    
    public function _getMensaje($id_rubrica, $calificacion) {
        try {
            $rubricaDAO = new RubricaDAO();
            $this->conectar();
            $rubricaDAO->setConexion($this->conexion);

            $mensajes = $rubricaDAO->getMensajeIdRubrica($id_rubrica);
            $mensaje = null;
            $value = false;
            foreach($mensajes as &$men){
                $men->comando = str_replace("{{calificacion}}", $calificacion, $men->comando);
                eval("\$value = $men->comando;");
                if($value){
                    $mensaje = $men->texto;
                    break;
                }
            }
            return $mensaje;
        } finally {
            $this->cerrarConexion();
        }
    }
}
