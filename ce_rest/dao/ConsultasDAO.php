<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConsultasDAO
 *
 * @author ernesto.ruales
 */
class ConsultasDAO {

    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getTipoAprobacionXid($id) {
        $sql = "Select * from tipo_aprobacion where id = $id";
        return $this->con->getArraySQL($sql);
    }

    function getParamSystem($name) {
        $sql = "select valor from parametro_sistema where nombre = '$name';";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        if ($row = $resultado_consulta->fetch_array()) {
            return $row["valor"];
        }
        return null;
    }

    function getPersonasActividad($id_actividad) {
        $sql = "select b.nombre, b.apellido, b.email, b.telefono, b.identificacion,
		c.nombre as actividad, d.id as id_actividad_trans,
                ifnull(e.nombre,'SIN ASIGNAR') as estado
          from inscripcion_edicion a
          inner join persona b on b.id = a.id_persona
          inner join actividad_etapa c on c.id = $id_actividad
          left outer join actividad_edicion d on d.id_actividad_etapa = c.id and d.id_inscripcion = a.id
          left outer join estado_actividad e on e.codigo = d.estado
          where a.fase = c.id_etapa";
        return $this->con->getArraySQL($sql);
    }

    function getPersonasActividadAsig($id_actividad) {
        $sql = "select c.nombre, c.apellido, c.email, c.telefono, c.identificacion,
		act.nombre as actividad, a.id as id_actividad_trans,
                d.nombre as estado
          from actividad_edicion a
         inner join actividad_etapa act on act.id = a.id_actividad_etapa
         inner join inscripcion_edicion b on b.id = a.id_inscripcion and b.fase = act.id_etapa
         inner join persona c on c.id = b.id_persona
         inner join estado_actividad d on d.codigo = a.estado
         where a.id_actividad_etapa = $id_actividad";
        return $this->con->getArraySQL($sql);
    }

    function getPersonasActividadNoAsig($id_actividad) {
        $sql = "select b.nombre, b.apellido, b.email, b.telefono, b.identificacion,
		c.nombre as actividad, null as id_actividad_trans,
                'SIN ASIGNAR' as estado
          from inscripcion_edicion a
          inner join persona b on b.id = a.id_persona
          inner join actividad_etapa c on c.id = $id_actividad
          where a.fase = c.id_etapa
          and not exists(select 1 from actividad_edicion act where act.id_inscripcion = a.id and act.id_actividad_etapa = $id_actividad)";
        return $this->con->getArraySQL($sql);
    }

    function getDatosTB($nombre, $estado, $tb) {
        $sql = "Select * from $tb where estado = (case '$estado' when 'T' then estado else '$estado' end)";
        return $this->con->getArraySQL($sql);
    }

    function insertarDatosTB($datos, $tb) {
        $sql = "INSERT INTO `$tb`
            (`nombre`,`estado`)
            VALUES('$datos->nombre','$datos->estado');
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }

    function actualizarDatosTB($datos, $tb) {
        $sql = "UPDATE `$tb` SET nombre = '$datos->nombre',
            estado = '$datos->estado'
            where id = $datos->id
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function getAplicacionExterna($codigo) {
        $sql = "Select * from aplicacion_externa where codigo = '$codigo'";
        return $this->con->getEntidad($sql);
    }

    function getTrama($codigo) {
        $sql = "Select * from trama_configuracion where codigo = '$codigo'";
        return $this->con->getEntidad($sql);
    }

    function getData($sql) {
        return $this->con->getEntidad($sql);
    }

}
