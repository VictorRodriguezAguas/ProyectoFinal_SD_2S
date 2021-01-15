<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/DashboardDAO.php';
class DashboardBO extends BO{
    //put your code here
    
    public function getDashboardAdmin() {
        $this->conectar();
        $dashboardDAO = new DashboardDAO();
        $dashboardDAO->setConexion($this->conexion);
        $data = new stdClass();
        $data->listaInscripcionesFases = $dashboardDAO->getInscripcionesxFases();
        $data->listaEmprendedoresFases = $dashboardDAO->getEmprendedoresFase();
        $data->listaInscripcionesFecha = $dashboardDAO->getInscripcionesFecha();
        $data->listaInscripcionesMes = $dashboardDAO->getInscripcionesMes();
        $data->listaSituacionLaboral = $dashboardDAO->getIndicadoresSitucionLaboral();
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function getIndicadoresXEtapa() {
        $respuesta = General::validarParametros($_POST, ["id_etapa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $dashboardDAO = new DashboardDAO();
        $dashboardDAO->setConexion($this->conexion);
        $data = $dashboardDAO->getActividadesPorEtapa($_POST["id_etapa"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function getDatosPivot() {
        $this->conectar();
        $dashboardDAO = new DashboardDAO();
        $dashboardDAO->setConexion($this->conexion);
        $data = $dashboardDAO->getDataPivot();
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function getIndicadoresSitucionLaboral() {
        $this->conectar();
        $dashboardDAO = new DashboardDAO();
        $dashboardDAO->setConexion($this->conexion);
        $data = $dashboardDAO->getIndicadoresSitucionLaboral();
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
}
