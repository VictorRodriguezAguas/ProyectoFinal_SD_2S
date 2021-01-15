<?php


class ReservaEspacioDAO
{
    private $con;

    function setConexion($con)
    {
        $this->con = $con;
    }

    function getIndex()
    {
        $sql = 'select e.*,et.nombre as espacio_tipo from espacio e join espacio_tipo et on e.id_espacio_tipo = et.id';
        return $this->con->getArraySQL($sql);
    }

    function getPerson($id_espacio)
    {
        /*
         * Segun el espacio se debe aplicar filtro para obtener las personas que pueden reservar el espacio
         */
        if ($id_espacio == 1) {
            $perfil = '(1,2,3,4,11)';
        } elseif ($id_espacio == 2) {
            $perfil = '(4,11)';
        } else {
            $perfil = '("")';
        }

        $sql = "select p.id, p.nombre, p.apellido, p.email, p.identificacion, up.id_perfil  from persona as p join usuario_perfil as up on p.id_usuario = up.id_usuario where p.estado='A' and up.id_perfil IN ${perfil};";
        return $this->con->getArraySQL($sql);
    }

    function getAddress()
    {
        $sql = "SELECT valor from parametro_sistema ps where nombre='UBICACION_EPICO'";
        return $this->con->getArraySQL($sql);
    }

    function getIndexReservation($id_espacio)
    {
        $sql = 'select er.*, e.hora_inicio as espacio_hora_inicio, e.hora_fin as espacio_hora_fin, e.cupo, e.id_espacio_tipo, et.nombre as espacio_tipo
            from espacio_reserva er 
            join espacio e on er.id_espacio = e.id 
            join espacio_tipo et on et.id  = e.id_espacio_tipo
            WHERE id_espacio = ' . $id_espacio;
        return $this->con->getArraySQL($sql);
    }

    function storeReservation($espacio, $id)
    {
        $espacio->id_usuario_registro = $id;
        $campos = array(
            "id_espacio",
            "id_persona",
            "fecha_registro",
            "id_usuario_registro",
            "fecha_inicio",
            "hora_inicio",
            "hora_fin",
            "estado",
            "espacio_reservado",
            "espacio_confirmado"
        );

        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $espacio->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("espacio_reserva", $campos, $tipodatos, $valores);
    }

    function updateReservation($espacio, $id)
    {
        $espacio->id_usuario_registro = $id;
        $campos = array(
            "id_espacio",
            "id_persona",
            "fecha_registro",
            "id_usuario_registro",
            "fecha_inicio",
            "hora_inicio",
            "hora_fin",
            "estado",
            "espacio_reservado",
            "espacio_confirmado"
        );

        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $espacio->{$campo};
            $tipodatos[] = "s";
        }
        $campos_condicion = array("id");
        $campos_condicion_valor = array($espacio->id);
        $tipodatos_condicion = array("i");
        $this->con->Actualizar("espacio_reserva", $campos, $tipodatos, $valores, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion);
    }

    function getDataSetByDate($date, $id_espacio)
    {
        $sql = "SELECT id, id_espacio , fecha_inicio, hora_inicio, hora_fin 
        from espacio_reserva er 
        where fecha_inicio = '${date}' and id_espacio = ${id_espacio}";
        return $this->con->getArraySQL($sql);
    }

    function getIndexFutureReservation($id_espacio)
    {
        $sql = "select fecha_inicio, hora_inicio, hora_fin from espacio_reserva WHERE id_espacio = ${id_espacio} and fecha_inicio >= CURDATE()";
        return $this->con->getArraySQL($sql);
    }

    function getParamMaxDaysAvailable()
    {
        $sql = "select valor from parametro_sistema where nombre='RESERVA-MAX_DIAS_DISPONIBILIDAD'";
        return $this->con->getArraySQL($sql);

    }

    function getMaxAvailableInEachPlace($id_espacio){
        $sql = "select cupo from espacio e where id = ${id_espacio}";
        return $this->con->getArraySQL($sql);
    }
}
