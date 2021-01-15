<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SolicitudServicioDAO
 *
 * @author ernesto.ruales
 */
class SolicitudServicioDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function insertarSolicitud($solicitud){
        General::setNullSql($solicitud, "observacion");
        $sql = "INSERT INTO `emprendedor_solicitud_servicio`
                (`id_servicio`,`id_emprendedor`,`id_emprendimiento`,`estado`)
                VALUES
                ($solicitud->id_servicio,$solicitud->id_emprendedor,$solicitud->id_emprendimiento,'$solicitud->estado');
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
    
    function getSolicitudServicio($id) {
        $respuesta = new stdClass();
        $sql = "Select * from emprendedor_solicitud_servicio where id = $id";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        if ($row = $resultado_consulta->fetch_array()) {
            $respuesta->id = $row["id"];
            $respuesta->id_servicio = $row["id_servicio"];
            $respuesta->id_emprendedor = $row["id_emprendedor"];
            $respuesta->id_emprendimiento = $row["id_emprendimiento"];
            $respuesta->estado = $row["estado"];
            $respuesta->observacion = $row["observacion"];
            $respuesta->fecha_registro = $row["fecha_registro"];
            $respuesta->fecha_aprobacion_rechazo = $row["fecha_aprobacion_rechazo"];
            $respuesta->id_usuario_revision = $row["id_usuario_revision"];
        }
        return $respuesta;
    }
    
    function getListaSolicitudServicioXidEmprendimiento($id, $estado, $idServicio) {
        $lista = array();
        $sql = "Select * from emprendedor_solicitud_servicio where id_emprendimiento = $id ";
        if(!is_null($estado)){
            $sql .= " and estado = '$estado'";
        }
        if(!is_null($idServicio)){
            $sql .= " and id_servicio = '$idServicio'";
        }
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $i=0;
        while ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->id = $row["id"];
            $respuesta->id_servicio = $row["id_servicio"];
            $respuesta->id_emprendedor = $row["id_emprendedor"];
            $respuesta->id_emprendimiento = $row["id_emprendimiento"];
            $respuesta->estado = $row["estado"];
            $respuesta->observacion = $row["observacion"];
            $respuesta->fecha_registro = $row["fecha_registro"];
            $respuesta->fecha_aprobacion_rechazo = $row["fecha_aprobacion_rechazo"];
            $respuesta->id_usuario_revision = $row["id_usuario_revision"];
            $i++;
            $lista[$i] = $respuesta;
        }
        return $lista;
    }
    
    function getSolicitudServicioXidEmprendimiento($id, $estado, $idServicio) {
        $respuesta = null;
        $sql = "Select * from emprendedor_solicitud_servicio where id_emprendimiento = $id ";
        if(!is_null($estado)){
            $sql .= " and estado = '$estado'";
        }
        if(!is_null($idServicio)){
            $sql .= " and id_servicio = '$idServicio'";
        }
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $i=0;
        if ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->id = $row["id"];
            $respuesta->id_servicio = $row["id_servicio"];
            $respuesta->id_emprendedor = $row["id_emprendedor"];
            $respuesta->id_emprendimiento = $row["id_emprendimiento"];
            $respuesta->estado = $row["estado"];
            $respuesta->observacion = $row["observacion"];
            $respuesta->fecha_registro = $row["fecha_registro"];
            $respuesta->fecha_aprobacion_rechazo = $row["fecha_aprobacion_rechazo"];
            $respuesta->id_usuario_revision = $row["id_usuario_revision"];
        }
        return $respuesta;
    }
    
    function tieneSolicitudServicioVigentes($id, $idServicio) {
        $sql = "Select * from emprendedor_solicitud_servicio where id_emprendimiento = $id 
                and id_servicio = '$idServicio'
                and estado not in ('E', 'R') ";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        if ($row = $resultado_consulta->fetch_array()) {
            return true;
        }
        return false;
    }
    
    function actualizarEstadoSolicitud($solicitud){
        General::asignarNullEntidad($solicitud, "id_usuario_revision");
        General::asignarNullEntidad($solicitud, "fecha_aprobacion_rechazo");
        General::asignarNullEntidad($solicitud, "fecha_aprobacion_rechazo_aut");
        General::asignarNullEntidad($solicitud, "id_usuario_autorizacion");
        General::asignarNullEntidad($solicitud, "observacion");
        $sql = "UPDATE `emprendedor_solicitud_servicio` 
                SET estado = '$solicitud->estado' ";
        if(!is_null($solicitud->id_usuario_revision)){
            $sql .= " ,id_usuario_revision = $solicitud->id_usuario_revision";
        }
        if(!is_null($solicitud->fecha_aprobacion_rechazo)){
            $sql .= " ,fecha_aprobacion_rechazo = $solicitud->fecha_aprobacion_rechazo";
        }
        if(!is_null($solicitud->fecha_aprobacion_rechazo_aut)){
            $sql .= " ,fecha_aprobacion_rechazo_aut = $solicitud->fecha_aprobacion_rechazo_aut";
        }
        if(!is_null($solicitud->id_usuario_autorizacion)){
            $sql .= " ,id_usuario_autorizacion = $solicitud->id_usuario_autorizacion";
        }
        if(!is_null($solicitud->observacion)){
            $sql .= " ,observacion = '$solicitud->observacion'";
        }
        $sql .="  where id = '$solicitud->id' ";
        //print $sql;
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
}
