<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardEmprendedorBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/DashboardDAO.php';

class DashboardEmprendedorBO extends BO{
    //put your code here
    public function getResumenEmprendedor() {
        $id_subprograma = 1;
        if(General::tieneValor($_POST, "id_sub_programa")){
            $id_subprograma = $_POST["id_sub_programa"];
        }
        $this->conectar();
        $dashboardDAO = new DashboardDAO();
        $dashboardDAO->setConexion($this->conexion);
        $data = new stdClass();
        $data->inscripcion = $dashboardDAO->getEtapaEmprendedor($this->user->id_persona, $id_subprograma);
        if(is_null($data->inscripcion)){
            return General::setRespuestaOK($data);
        }
        $data->resumen = $dashboardDAO->getAvancePrograma($data->inscripcion->id_inscripcion, $data->inscripcion->etapa);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
}
