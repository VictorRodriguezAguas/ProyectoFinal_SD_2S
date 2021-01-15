<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AgendaDAO
 *
 * @author ernesto.ruales
 */
class AgendaDAO {

    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function insertarAgenda($agenda) {
        $paramAux=new stdClass();
        $paramAux->id_persona = null;
        $paramAux->id_persona2 = null;
        $paramAux->tipo = null;
        $paramAux->fecha = null;
        $paramAux->hora_inicio = null;
        $paramAux->tema = null;
        $paramAux->id_evento = null;
        $paramAux->hora_fin = null;
        $paramAux->id_tipo_asistencia = null;
        $paramAux->id_actividad = null;
        $paramAux->color = null;
        
        $agenda = (object)array_merge((array)$paramAux, (array)$agenda);
        
        $campos = array("id_persona", "id_persona2", "tipo", "fecha", "hora_inicio",
            "estado", "tema", "id_evento", "hora_fin", "id_tipo_asistencia", "id_actividad", "color");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $agenda->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("agenda", $campos, $tipodatos, $valores);
    }

    function actualizarAgenda($agenda) {
        $paramAux=new stdClass();
        $paramAux->estado = null;
        $paramAux->observacion = null;
        $paramAux->id_motivo_cancelar = null;
        $paramAux->id_usuario_can = null;
        $campos = array("estado", "observacion", "id_motivo_cancelar", "id_usuario_can");
        $agenda = (object)array_merge((array)$paramAux, (array)$agenda);
        
        $tabla = "agenda";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $agenda->{$campo};
            $tipodatos[] = "s";
        }
        $campos_condicion = array("id");
        $campos_condicion_valor = array($agenda->id_agenda);
        $tipodatos_condicion = array("i");
        $this->con->Actualizar($tabla, $campos, $tipodatos, $valores, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion);
    }

    function getAgendaxId($id) {
        $sql = "select a.*, a.id as id_agenda, 
                concat(p1.nombre, ' ', p1.apellido) as nombre1, 
                concat(p2.nombre, ' ', p2.apellido) as nombre2,
                p1.email as email1,
                p2.email as email2,
                r.id as id_reunion,
                d.id as id_actividad_inscripcion,
                a.observacion,
                a.id_motivo_cancelar,
                e.nombre as motivo_cancelar,
                f.nombre as tipo_asistencia,
                DATE_FORMAT(a.fecha, '%d de %M del %Y') as fechaTexto,
                (select valor from parametro_sistema WHERE nombre = 'UBICACION_EPICO') as lugar
                from agenda a
                inner join persona p1 on p1.id = a.id_persona
                left outer join persona p2 on p2.id = a.id_persona2
                left outer join reunion r on r.id_agenda = a.id
                left outer join actividad_edicion d on d.id_agenda = a.id
                left outer join motivo_cancelar_agenda e on e.id = a.id_motivo_cancelar
                left outer join tipo_asistencia f on f.id = a.id_tipo_asistencia
                where a.id = '$id'
                ";
        return $this->con->getEntidad($sql);
    }

    function getAgendaxIdPersona($id_persona) {
        $sql = "select a.*, a.id as id_agenda, 
                concat(p1.nombre, ' ', p1.apellido) as nombre1, 
                concat(p2.nombre, ' ', p2.apellido) as nombre2,
                p1.email as email1,
                p2.email as email2,
                concat(a.fecha, 'T', a.hora_inicio) as start,
                concat(a.fecha, 'T', a.hora_fin) as end,
                tipo as title,
                (case a.estado when 'CA' then 'red' when 'AS' then '#9CCC65' else ifnull(a.color, ifnull(b.color, ifnull(c.color, '#a389d4'))) end) as backgroundColor,
                (case a.estado when 'CA' then 'red' when 'AS' then '#9CCC65' else ifnull(a.color, ifnull(b.color, ifnull(c.color, '#a389d4'))) end) as borderColor,
                '#fff' as textColor,
                d.id as id_actividad_inscripcion,
                a.observacion,
                a.id_motivo_cancelar,
                e.nombre as motivo_cancelar,
                f.nombre as tipo_asistencia
                from agenda a
                inner join persona p1 on p1.id = a.id_persona
                left outer join persona p2 on p2.id = a.id_persona2
                left outer join evento b on b.id = a.id_evento
                left outer join tipo_evento c on c.id = b.id_tipo_evento
                left outer join actividad_edicion d on d.id_agenda = a.id
                left outer join motivo_cancelar_agenda e on e.id = a.id_motivo_cancelar
                left outer join tipo_asistencia f on f.id = a.id_tipo_asistencia
                where a.id_persona = '$id_persona' and a.estado <> 'CA'
                ";
        return $this->con->getArraySQL($sql);
    }

    function getEvento($id_evento, $codigo, $tipo, $estado = 'A') {
        $sql = "select a.*, b.nombre as tipo,
                (select count(1) from agenda ag where ag.id_evento = a.id and estado <> 'CA') as registrados
            from evento a
            inner join tipo_evento b on b.id = a.id_tipo_evento  
            where a.estado = '$estado'";
        if (!is_null($codigo)) {
            $sql .= " and a.codigo = '$codigo'";
        }
        if (!is_null($tipo)) {
            $sql .= " and a.id_tipo_evento = $tipo";
        }
        if (!is_null($id_evento)) {
            $sql .= " and a.id = $id_evento";
        }
        $sql .= " order by id desc limit 1";
        return $this->con->getEntidad($sql);
    }

    function getAgendaxParam($param) {
        $respuesta = General::validarParametrosOR($param, ["id_persona", "id_persona2", "tipo", "fecha_inicio", "fecha_fin"]);
        if (!is_null($respuesta)) {
            throw new Exception("Faltan paramentros en la consulta");
        }
        $sql = "SELECT a.fecha, a.hora_inicio, a.hora_fin
                    FROM agenda a 
                   where a.estado != 'E' ";
        if (General::tieneValor($param, "fecha_inicio")) {
            $sql .= " and a.fecha >= '$param->fecha_inicio'";
            $param->fecha_inicio = null;
        }
        if (General::tieneValor($param, "fecha_fin")) {
            $sql .= " and a.fecha <= '$param->fecha_fin'";
            $param->fecha_fin = null;
        }
        foreach ($param as $nombre => $valor) {
            if(General::tieneValor($param, "$nombre")){
                $sql .= " and a.$nombre = '$valor' ";
            }
        }
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function getAgendasXIdPersona2($id_persona, $tipo) {
        $sql = "
                select a.id as 'id_agenda', 
                    a.id_persona, concat(p.nombre,' ',p.apellido) as 'persona1', p.telefono,
                    a.id_persona2, concat(p2.nombre,' ',p2.apellido) as 'persona2',
                a.tipo as 'tipo_agenda',a.fecha as 'fecha_agenda',a.hora_inicio as 'hora_inicio_agenda',a.hora_fin as 'hora_fin_agenda',a.estado as 'estado_agenda',a.tema as 'tema_agenda',a.id_evento,
                b.id as id_reunion, b.hora_inicio as hora_inicio_reunion, b.hora_fin as hora_fin_reunion,
                b.estado as estado_reunion, a.*, ifnull(c.nombre, 'Sin modalidad') as tipo_asistencia
                from agenda a
                inner join persona p on p.id = a.id_persona
                inner join persona p2 on p2.id = a.id_persona2
                left outer join reunion b on b.id_agenda = a.id
                left outer join tipo_asistencia c on c.id = a.id_tipo_asistencia
                where id_persona2 = '$id_persona'
                  and a.tipo = '$tipo'
                  and a.estado <> 'CA';";
        return $this->con->getArraySQL($sql);
    }

}
