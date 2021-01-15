<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AsistenciaTecnicaDAO
 *
 * @author ernesto.ruales
 */
class AsistenciaTecnicaDAO {

    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getHorarioAsistenciaTecnica($dia = null, $fecha = null, $id_asistente_tecnico = null) {
        $sql = "SELECT a.*
                FROM asistencia_tecnica_horario a 
               inner join asistencia_tecnica b on b.id = a.id_asistencia_tecnica
               where b.estado = 'A'";
        if (is_null($fecha)) {
            $sql .= " and b.fecha_inicio <= now() and b.fecha_fin >= now() ";
        } else {
            $sql .= " and b.fecha_inicio <= '$fecha' and b.fecha_fin >= '$fecha' ";
        }
        if (!is_null($dia)) {
            $sql .= " and a.dia = '$dia' ";
        }
        if (!is_null($id_asistente_tecnico)) {
            $sql .= " and b.id = '$id_asistente_tecnico' ";
        }
        return $this->con->getArraySQL($sql);
    }

    function getAsistenciaAgendadaXdiaXhora($dia, $hora_inicio = null, $hora_fin = null, $tipo = 'ASISTENCIA TECNICA', $id_asistente_tecnico = null) {
        $sql = "SELECT a.fecha, a.hora_inicio, a.hora_fin, count(1) as cant
                    FROM agenda a 
                   where a.fecha = '$dia' ";
        if (!is_null($hora_inicio)) {
            $sql .= " and a.hora_inicio = '$hora_inicio'";
        }
        if (!is_null($hora_fin)) {
            $sql .= " and a.hora_fin = '$hora_fin'";
        }
        if (!is_null($hora_fin)) {
            $sql .= " and a.hora_fin = '$hora_fin'";
        }
        if (!is_null($id_asistente_tecnico)) {
            $sql .= " and exists (select 1 from asistencia_tecnica at where at.id_persona = a.id_persona2 and at.id = '$id_asistente_tecnico' ) ";
        }
        $sql .="    and a.tipo = '$tipo'
                   group by a.fecha, a.hora_inicio, a.hora_fin;";
        return $this->con->getArraySQL($sql);
    }

    function getAsistenteTecnicoCantAgendada($dia, $fecha, $hora_inicio, $hora_fin, $tipo = 'ASISTENCIA TECNICA', $id_asistente_tecnico = null) {
        $sql = "SELECT a.id, a.id_persona, count(c.id) as cant
                FROM asistencia_tecnica a
                inner join asistencia_tecnica_horario b on b.id_asistencia_tecnica = a.id 
                left outer join agenda c on c.id_persona2 = a.id_persona and c.fecha = '$fecha' and tipo = '$tipo'
                where b.dia = '$dia'
                  and (case length(b.hora_inicio) when 5 then concat(b.hora_inicio, ':00') else b.hora_inicio end) <= '$hora_inicio'
                  and (case length(b.hora_fin) when 5 then concat(b.hora_fin, ':00') else b.hora_fin end) >= '$hora_fin'
                  and a.fecha_inicio <= '$fecha' and a.fecha_fin >= '$fecha'
                  and a.estado = 'A' ";
        if (!is_null($id_asistente_tecnico)) {
            $sql .= " and a.id = $id_asistente_tecnico ";
        }
        $sql .= "        group by a.id_persona
                order by cant asc
                -- limit 1
                ;";
        return $this->con->getEntidad($sql);
    }

    function getAsistenciaAgendadaXfechas($fecha_inicio, $fecha_fin, $id_asistente_tecnico = null) {
        $sql = "SELECT a.fecha, a.hora_inicio, a.hora_fin
                    FROM agenda a 
                   where a.fecha >= '$fecha_inicio'
                     and a.fecha <= '$fecha_fin'
                     and a.tipo = 'ASISTENCIA TECNICA'";
        if (!is_null($id_asistente_tecnico)) {
            $sql .= " and exists (select 1 from asistencia_tecnica at where at.id_persona = a.id_persona2 and at.id = '$id_asistente_tecnico' ) ";
        }
        //General::printD($fecha_inicio, "2020-12-09", $sql);
        return $this->con->getArraySQL($sql);
    }

    function getAsistentePorIdPersona($id) {
        $sql = "Select a.id as id_asistente_tecnico, a.*
            from asistencia_tecnica a 
            where a.id_persona = $id";
        //print $sql;
        $respuesta = $this->con->getEntidad($sql);
        return $respuesta;
    }

}
