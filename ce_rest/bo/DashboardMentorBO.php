<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardAT
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/DashboardDAO.php';

class DashboardMentorBO extends BO{
    //put your code here
    public function getResumenMentor() {
        $this->conectar();
        $dashboardDAO = new DashboardDAO();
        $dashboardDAO->setConexion($this->conexion);
        $data = new stdClass();
        $data->resumen = $dashboardDAO->getResumenAgendaDia($this->user->id_persona);
        if(is_null($data->resumen)){
            $data->resumen = new stdClass();
            $data->resumen->atendidos = 0;
            $data->resumen->total = 0;
            $data->resumen->pendientes = 0;
            $data->resumen->enproceso = 0;
        }
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
}
