<?php

/**
 * Description of TicketDAO
 *
 * @author Mauricio Guzman
 */

class TicketDAO {

    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function loadCatalogStructure($row){
        $respuesta = new stdClass();
        $respuesta->id = $row["id"];
        $respuesta->nombre = $row["nombre"];
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function loadTicketType($row){
        $respuesta = new stdClass();
        $respuesta->id = $row["id"];
        $respuesta->nombre = $row["nombre"];
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function loadCategory($row){
        $respuesta = new stdClass();
        $respuesta->id = $row["id_categoria"];
        $respuesta->nombre = $row["nombre"];
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function loadSubcategory($row){
        $respuesta = new stdClass();
        $respuesta->id = $row["id_subcategoria"];
        $respuesta->nombre = $row["nombre"];
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function loadTicketByParams($row){
        $respuesta = new stdClass();
        $respuesta->id_ticket = $row["id_ticket"];
        $respuesta->ticket_estado = $row["ticket_estado"];
        $respuesta->id_usuario_creacion = $row["id_usuario_creacion"];
        $respuesta->nombre_usuario_creacion = $row["usuario_nombre"];
        $respuesta->apellido_usuario_creacion = $row["usuario_apellido"];
        $respuesta->email_usuario_creacion = $row["usuario_email"];
        $respuesta->alias_usuario_creacion = $row["usuario"];
        $respuesta->fecha_creacion = $row["fecha_creacion"];
        $respuesta->fecha_toma = $row["fecha_inicio_atencion"];
        $respuesta->fecha_finalizacion = $row["fecha_fin_atencion"];
        $respuesta->foto_usuario_creacion = $row["uc_foto"];
        $respuesta->nombre_usuario_atencion = $row["usuario_atencion_nombre"];
        $respuesta->apellido_usuario_atencion = $row["usuario_atencion_apellido"];
        $respuesta->foto_usuario_atencion = $row["ua_foto"];        
        $respuesta->categoria = $row["categoria"];
        $respuesta->subcategoria = $row["subcategoria"];
        $respuesta->descripcion = $row["descripcion"];
        $respuesta->observacion = $row["observacion"];
        $respuesta->estado = $row["estado"];
        $respuesta->id_reunion = $row["id_reunion"];
        return $respuesta;
    }


    function loadAttendedTicketByUser($row){
        $respuesta = new stdClass();
        $respuesta->id_ticket = $row["id_ticket"];
        $respuesta->ticket_estado = $row["ticket_estado"];
        $respuesta->id_usuario_creacion = $row["id_usuario_creacion"];
        $respuesta->nombre_usuario_creacion = $row["usuario_nombre"];
        $respuesta->apellido_usuario_creacion = $row["usuario_apellido"];
        $respuesta->alias_usuario_creacion = $row["usuario"];
        $respuesta->fecha_creacion = $row["fecha_creacion"];
        $respuesta->fecha_toma = $row["fecha_inicio_atencion"];
        $respuesta->fecha_finalizacion = $row["fecha_fin_atencion"];
        $respuesta->foto_usuario_creacion = $row["uc_foto"];
        $respuesta->nombre_usuario_atencion = $row["usuario_atencion_nombre"];
        $respuesta->apellido_usuario_atencion = $row["usuario_atencion_apellido"];
        $respuesta->foto_usuario_atencion = $row["ua_foto"];        
        $respuesta->categoria = $row["categoria"];
        $respuesta->subcategoria = $row["subcategoria"];
        $respuesta->descripcion = $row["descripcion"];
        $respuesta->id_reunion = $row["id_reunion"];
        $respuesta->atendido = true;
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function loadNotAttendedTicketByUser($row){
        $respuesta = new stdClass();
        $respuesta->id_ticket = $row["id_ticket"];
        $respuesta->ticket_estado = $row["ticket_estado"];
        $respuesta->id_usuario_creacion = $row["id_usuario_creacion"];
        $respuesta->nombre_usuario_creacion = $row["usuario_nombre"];
        $respuesta->apellido_usuario_creacion = $row["usuario_apellido"];
        $respuesta->alias_usuario_creacion = $row["usuario"];
        $respuesta->fecha_creacion = $row["fecha_creacion"];
        $respuesta->fecha_toma = $row["fecha_inicio_atencion"];
        $respuesta->fecha_finalizacion = $row["fecha_fin_atencion"];
        $respuesta->foto_usuario_creacion = $row["uc_foto"];
        $respuesta->nombre_usuario_atencion = $row["usuario_atencion_nombre"];
        $respuesta->apellido_usuario_atencion = $row["usuario_atencion_apellido"];
        $respuesta->foto_usuario_atencion = $row["ua_foto"];        
        $respuesta->categoria = $row["categoria"];
        $respuesta->subcategoria = $row["subcategoria"];
        $respuesta->descripcion = $row["descripcion"];
        $respuesta->id_reunion = $row["id_reunion"];
        $respuesta->atendido = false;
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function loadAttendedTicketBody($row){
        $respuesta = new stdClass();
        $respuesta->id_ticket = $row["id_ticket"];
        $respuesta->ticket_estado = $row["ticket_estado"];
        $respuesta->id_usuario_creacion = $row["id_usuario_creacion"];
        $respuesta->nombre_usuario_creacion = $row["usuario_nombre"];
        $respuesta->apellido_usuario_creacion = $row["usuario_apellido"];
        $respuesta->alias_usuario_creacion = $row["usuario"];
        $respuesta->fecha_creacion = $row["fecha_creacion"];
        $respuesta->fecha_toma = $row["fecha_inicio_atencion"];
        $respuesta->fecha_finalizacion = $row["fecha_fin_atencion"];
        $respuesta->foto_usuario_creacion = $row["uc_foto"];
        $respuesta->nombre_usuario_atencion = $row["usuario_atencion_nombre"];
        $respuesta->apellido_usuario_atencion = $row["usuario_atencion_apellido"];
        $respuesta->foto_usuario_atencion = $row["ua_foto"];
        $respuesta->categoria = $row["categoria"];
        $respuesta->subcategoria = $row["subcategoria"];
        $respuesta->descripcion = $row["descripcion"];
        $respuesta->id_reunion = $row["id_reunion"];
        $respuesta->atendido = true;
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function loadNotAttendedTicketBody($row){
        $respuesta = new stdClass();
        $respuesta->id_ticket = $row["id_ticket"];
        $respuesta->ticket_estado = $row["ticket_estado"];
        $respuesta->id_usuario_creacion = $row["id_usuario_creacion"];
        $respuesta->nombre_usuario_creacion = $row["usuario_nombre"];
        $respuesta->apellido_usuario_creacion = $row["usuario_apellido"];
        $respuesta->alias_usuario_creacion = $row["usuario"];
        $respuesta->fecha_creacion = $row["fecha_creacion"];
        $respuesta->fecha_toma = $row["fecha_inicio_atencion"];
        $respuesta->fecha_finalizacion = $row["fecha_fin_atencion"];
        $respuesta->foto_usuario_creacion = $row["uc_foto"];
        $respuesta->nombre_usuario_atencion = $row["usuario_atencion_nombre"];
        $respuesta->apellido_usuario_atencion = $row["usuario_atencion_apellido"];
        $respuesta->foto_usuario_atencion = $row["ua_foto"];
        $respuesta->categoria = $row["categoria"];
        $respuesta->subcategoria = $row["subcategoria"];
        $respuesta->descripcion = $row["descripcion"];
        $respuesta->id_reunion = $row["id_reunion"];
        $respuesta->atendido = false;
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }



    function getTicketTypes() {
        $sql = "select * from tipo_ticket where estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadCatalogStructure($row);
            $i++;
        }
        return $lista;
    }

    function getTicketStatus() {
        $sql = "select * from tipo_estado_atencion where estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadCatalogStructure($row);
            $i++;
        }
        return $lista;
    }

    function getTicketCategories($type) {
        $sql = "select * from ticket_categoria where id_tipo = '$type' and estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadCategory($row);
            $i++;
        }
        return $lista;
    }

    function getTicketSubcategories($type) {
        $sql = "select * from ticket_subcategoria where id_categoria = '$type' and estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadSubcategory($row);
            $i++;
        }
        return $lista;
    }

    function getTicketProfile() {
        $sql = "select * from ".basedatos::$baseSeguridad.".perfil where estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadCatalogStructure($row);
            $i++;
        }
        return $lista;
    }


    function getPerfilByUser($usuario) {
        $sql = "select us.*, up.id_perfil from ".basedatos::$baseSeguridad.".usuario_perfil up
                left outer join ".basedatos::$baseSeguridad.".usuario us
                on up.id_usuario = us.id
                where up.id_usuario = '$usuario'";

        $resultado_consulta = $this->con->getConexion()->query($sql);
        if ($row = $resultado_consulta->fetch_array()) {
            return $row["id_perfil"];
        }
        return 0;
    }


    function getTicketsByPerfil($usuario) {

        $perfil = $this->getPerfilByUser($usuario);
        $attendedLst = array();
        $ntAttendedLst = array();
        $allTicketLst = array();
        
        if($perfil == 5){
            $attendedLst = $this->getAttendedTickets($perfil);
            $ntAttendedLst = $this->getNotAttendedTickets();

            $p = 0;
            $q = 0;
            foreach ($attendedLst as $aItem) {
                $allTicketLst[$p] = $aItem;
                $p++;
                $q = $p;
            }

            foreach ($ntAttendedLst as $naItem) {
                $allTicketLst[$q] = $naItem;
                $q++;
            }
            return $allTicketLst;
        } else{
            $attendedLst = $this->getAttendedTicketsByUser($usuario);
            $ntAttendedLst = $this->getNotAttendedTicketsByUser($usuario);

            $p = 0;
            $q = 0;
            foreach ($attendedLst as $aItem) {
                $allTicketLst[$p] = $aItem;
                $p++;
                $q = $p;
            }

            foreach ($ntAttendedLst as $naItem) {
                $allTicketLst[$q] = $naItem;
                $q++;
            }
            return $allTicketLst;
        }
    }


    function getAttendedTickets($usuario) {
        $sql = "select tk.*, ctk.nombre as categoria, trtk.nombre as subcategoria,
                tea.nombre as ticket_estado, a.usuario as usuario, 
                a.nombre as usuario_nombre, a.apellido as usuario_apellido,
                tk.id as id_ticket, b.nombre as usuario_atencion_nombre, b.apellido 
                as usuario_atencion_apellido,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as uc_foto,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as ua_foto 
                from ticket tk
                left outer join tipo_estado_atencion tea
                on tk.id_tipo_atencion = tea.id
                left outer join ".basedatos::$baseSeguridad.".usuario a
                on tk.id_usuario_creacion = a.id
                left outer join ".basedatos::$baseSeguridad.".usuario b
                on tk.id_usuario_atencion = b.id
                left outer join ticket_categoria ctk
                on tk.id_categoria = ctk.id_categoria
                left outer join ticket_subcategoria trtk
                on tk.id_subcategoria = trtk.id_subcategoria  
                where tk.id_usuario_atencion = '$usuario'
                and tk.id_tipo_atencion not in (3, 4) 
                and tk.estado = 'A'";

        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadAttendedTicketBody($row);
            $i++;
        }
        return $lista;
    }


    function getNotAttendedTickets() {
        $sql = "select tk.*, ctk.nombre as categoria, trtk.nombre as subcategoria,
                tea.nombre as ticket_estado, a.usuario as usuario, 
                a.nombre as usuario_nombre, a.apellido as usuario_apellido,
                tk.id as id_ticket, b.nombre as usuario_atencion_nombre, b.apellido 
                as usuario_atencion_apellido,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as uc_foto,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as ua_foto 
                from ticket tk
                left outer join tipo_estado_atencion tea
                on tk.id_tipo_atencion = tea.id
                left outer join ".basedatos::$baseSeguridad.".usuario a
                on tk.id_usuario_creacion = a.id
                left outer join ".basedatos::$baseSeguridad.".usuario b
                on tk.id_usuario_atencion = b.id
                left outer join ticket_categoria ctk
                on tk.id_categoria = ctk.id_categoria
                left outer join ticket_subcategoria trtk
                on tk.id_subcategoria = trtk.id_subcategoria  
                where tk.id_usuario_atencion is null 
                and tk.estado = 'A'";

        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadNotAttendedTicketBody($row);
            $i++;
        }
        return $lista;
    }

    function getAttendedTicketsByUser($usuario) {
        $sql = "select tk.*, ctk.nombre as categoria, trtk.nombre as subcategoria,
                tea.nombre as ticket_estado, a.usuario as usuario, 
                a.nombre as usuario_nombre, a.apellido as usuario_apellido,
                tk.id as id_ticket, b.nombre as usuario_atencion_nombre, b.apellido 
                as usuario_atencion_apellido,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as uc_foto,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as ua_foto
                from ticket tk
                left outer join tipo_estado_atencion tea
                on tk.id_tipo_atencion = tea.id
                left outer join ".basedatos::$baseSeguridad.".usuario a
                on tk.id_usuario_creacion = a.id
                left outer join ".basedatos::$baseSeguridad.".usuario b
                on tk.id_usuario_atencion = b.id
                left outer join ticket_categoria ctk
                on tk.id_categoria = ctk.id_categoria
                left outer join ticket_subcategoria trtk
                on tk.id_subcategoria = trtk.id_subcategoria  
                where tk.id_usuario_creacion = '$usuario'
                and tk.id_usuario_atencion is not null
                and tk.estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadAttendedTicketByUser($row);
            $i++;
        }
        return $lista;
    }

    function getNotAttendedTicketsByUser($usuario) {
        $sql = "select tk.*, ctk.nombre as categoria, trtk.nombre as subcategoria,
                tea.nombre as ticket_estado, a.usuario as usuario, 
                a.nombre as usuario_nombre, a.apellido as usuario_apellido,
                tk.id as id_ticket, b.nombre as usuario_atencion_nombre, b.apellido 
                as usuario_atencion_apellido,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as uc_foto,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as ua_foto
                from ticket tk
                left outer join tipo_estado_atencion tea
                on tk.id_tipo_atencion = tea.id
                left outer join ".basedatos::$baseSeguridad.".usuario a
                on tk.id_usuario_creacion = a.id
                left outer join ".basedatos::$baseSeguridad.".usuario b
                on tk.id_usuario_atencion = b.id
                left outer join ticket_categoria ctk
                on tk.id_categoria = ctk.id_categoria
                left outer join ticket_subcategoria trtk
                on tk.id_subcategoria = trtk.id_subcategoria  
                where tk.id_usuario_creacion = '$usuario'
                and tk.id_usuario_atencion is null
                and tk.estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadNotAttendedTicketByUser($row);
            $i++;
        }
        return $lista;
    }

    function getTicketsByParam($usuario_atencion, $tipo_estado_atencion, 
                                $categoria, $subcategoria,
                                $cedula_usuario_creacion, $nombre_usuario_creacion,
                                $email_usuario_creacion, $estado = 'A') {

        $sql = "select tk.*, ctk.nombre as categoria, trtk.nombre as subcategoria,
                tea.nombre as ticket_estado, a.usuario as usuario, 
                a.nombre as usuario_nombre, a.apellido as usuario_apellido, a.correo as usuario_email,
                tk.id as id_ticket, b.nombre as usuario_atencion_nombre, b.apellido 
                as usuario_atencion_apellido, tk.observacion,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as uc_foto,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as ua_foto
                from ticket tk
                left outer join tipo_estado_atencion tea
                on tk.id_tipo_atencion = tea.id
                left outer join ".basedatos::$baseSeguridad.".usuario a
                on tk.id_usuario_creacion = a.id
                left outer join ".basedatos::$baseSeguridad.".usuario b
                on tk.id_usuario_atencion = b.id
                left outer join persona pe
                on pe.id_usuario = tk.id_usuario_creacion
                left outer join ticket_categoria ctk
                on tk.id_categoria = ctk.id_categoria
                left outer join ticket_subcategoria trtk
                on tk.id_subcategoria = trtk.id_subcategoria  
                where tk.estado = '$estado'";

        if (!is_null($tipo_estado_atencion)) {
            if($tipo_estado_atencion == 1)
                $sql .= " and tk.id_tipo_atencion = '$tipo_estado_atencion'";
            else
                $sql .= " and tk.id_tipo_atencion = '$tipo_estado_atencion' and tk.id_usuario_atencion = '$usuario_atencion'";
        }
        if (!is_null($categoria)) {
            $sql .= " and tk.id_categoria = $categoria";
        }
        if (!is_null($subcategoria)) {
            $sql .= " and tk.id_subcategoria = $subcategoria";
        }
        if (!is_null($cedula_usuario_creacion)) {
            $sql .= " and pe.identificacion like '$cedula_usuario_creacion'";
        }
        if (!is_null($nombre_usuario_creacion)) {
            $sql .= " and (pe.nombre like '$nombre_usuario_creacion%' or pe.apellido like '$nombre_usuario_creacion%')";
        }
        if (!is_null($email_usuario_creacion)) {
            $sql .= " and pe.email like '$email_usuario_creacion%'";
        }
        $sql .= " order by id desc";

        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadTicketByParams($row);
            $i++;
        }
        return $lista;
    }


    function getCreatedTicketsByParam($id_usuario, $tipo_estado_atencion, 
                                $categoria, $subcategoria, $estado = 'A') {

        $sql = "select tk.*, ctk.nombre as categoria, trtk.nombre as subcategoria,
                tea.nombre as ticket_estado, a.usuario as usuario, 
                a.nombre as usuario_nombre, a.apellido as usuario_apellido, a.correo as usuario_email,
                tk.id as id_ticket, b.nombre as usuario_atencion_nombre, b.apellido 
                as usuario_atencion_apellido, tk.observacion,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as uc_foto,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as ua_foto
                from ticket tk
                left outer join tipo_estado_atencion tea
                on tk.id_tipo_atencion = tea.id
                left outer join ".basedatos::$baseSeguridad.".usuario a
                on tk.id_usuario_creacion = a.id
                left outer join ".basedatos::$baseSeguridad.".usuario b
                on tk.id_usuario_atencion = b.id
                left outer join persona pe
                on pe.id_usuario = tk.id_usuario_creacion
                left outer join ticket_categoria ctk
                on tk.id_categoria = ctk.id_categoria
                left outer join ticket_subcategoria trtk
                on tk.id_subcategoria = trtk.id_subcategoria  
                where tk.estado = '$estado'";

        if (!is_null($id_usuario)) {
            $sql .= " and tk.id_usuario_creacion = $id_usuario";
        }
        if (!is_null($tipo_estado_atencion)) {
            $sql .= " and tk.id_tipo_atencion = $tipo_estado_atencion";
        }
        if (!is_null($categoria)) {
            $sql .= " and tk.id_categoria = $categoria";
        }
        if (!is_null($subcategoria)) {
            $sql .= " and tk.id_subcategoria = $subcategoria";
        }
        $sql .= " order by id desc";

        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadTicketByParams($row);
            $i++;
        }
        return $lista;
    }


    function getTicketsByParamHistorical($tipo_estado_atencion, 
                                $categoria, $subcategoria,
                                $id_perfil,
                                $nombre_usuario_creacion,
                                $fecha_inicio_atencion, $fecha_fin_atencion,
                                $estado = 'A') {

        $sql = "select tk.*, ctk.nombre as categoria, trtk.nombre as subcategoria,
                tea.nombre as ticket_estado, a.usuario as usuario, 
                a.nombre as usuario_nombre, a.apellido as usuario_apellido, a.correo as usuario_email,
                tk.id as id_ticket, b.nombre as usuario_atencion_nombre, b.apellido 
                as usuario_atencion_apellido, tk.observacion,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),a.foto) as uc_foto,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),b.foto) as ua_foto
                from ticket tk
                left outer join tipo_estado_atencion tea
                on tk.id_tipo_atencion = tea.id
                left outer join ".basedatos::$baseSeguridad.".usuario a
                on tk.id_usuario_creacion = a.id
                left outer join ".basedatos::$baseSeguridad.".usuario b
                on tk.id_usuario_atencion = b.id
                left outer join persona pe
                on pe.id_usuario = tk.id_usuario_creacion
                left outer join ".basedatos::$baseSeguridad.".usuario_perfil usp
                on a.id = usp.id_usuario
                left outer join ".basedatos::$baseSeguridad.".perfil per
                on per.id = usp.id_perfil
                left outer join ticket_categoria ctk
                on tk.id_categoria = ctk.id_categoria
                left outer join ticket_subcategoria trtk
                on tk.id_subcategoria = trtk.id_subcategoria  
                where tk.estado = '$estado'";

        if (!is_null($tipo_estado_atencion)) {
            $sql .= " and tk.id_tipo_atencion = '$tipo_estado_atencion'";
        }
        if (!is_null($categoria)) {
            $sql .= " and tk.id_categoria = $categoria";
        }
        if (!is_null($subcategoria)) {
            $sql .= " and tk.id_subcategoria = $subcategoria";
        }
        if (!is_null($id_perfil)) {
            $sql .= " and per.id = $id_perfil";
        }
        if (!is_null($nombre_usuario_creacion)) {
            $sql .= " and (pe.nombre like '$nombre_usuario_creacion%' or pe.apellido like '$nombre_usuario_creacion%')";
        }
        if (!is_null($fecha_inicio_atencion)) {
            $sql .= " and tk.fecha_inicio_atencion >= '$fecha_inicio_atencion'";
        }
        if (!is_null($fecha_fin_atencion)) {
            $sql .= " and tk.fecha_fin_atencion <= '$fecha_fin_atencion'";
        }
        $sql .= " order by id desc";

        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadTicketByParams($row);
            $i++;
        }
        return $lista;
    }    


    function insertarTicket($ticket) {
        General::setNullSql($ticket, "id_subcategoria");
        General::setNullSql($ticket, "id_reunion");
        $sql = "INSERT INTO `ticket` (`id_tipo`, `id_usuario_creacion`, `fecha_creacion`, `id_categoria`, 
                `id_subcategoria`, `id_tipo_atencion`, `descripcion`, `id_persona`, `estado`, id_reunion)
                VALUES('$ticket->id_tipo','$ticket->id_usuario_creacion','$ticket->fecha_creacion', 
                '$ticket->id_categoria', $ticket->id_subcategoria, 1, '$ticket->descripcion', '$ticket->id_persona', 'A',
                $ticket->id_reunion)";

        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }


    function updateTicketByAttended($ticket) {
        $sql = "UPDATE `ticket` SET `id_usuario_atencion` = '$ticket->id_usuario_atencion',
                                    `id_tipo_atencion` = 2,
                                    `fecha_inicio_atencion` = '$ticket->fecha_toma' 
               WHERE `id` = '$ticket->id_ticket'";
               
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }


    function doneTicketByAttended($ticket) {
        $sql = "UPDATE `ticket` SET `id_usuario_atencion` = '$ticket->id_usuario_atencion',
                                    `id_tipo_atencion` = 3,
                                    `observacion` = '$ticket->observacion',
                                    `fecha_fin_atencion` = '$ticket->fecha_finalizacion' 
               WHERE `id` = '$ticket->id_ticket'";
               
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }


    function deleteTicketByAttended($ticket) {
        $sql = "UPDATE `ticket` SET `id_usuario_atencion` = '$ticket->id_usuario_atencion',
                                    `id_tipo_atencion` = 4,
                                    `observacion` = '$ticket->observacion',
                                    `fecha_fin_atencion` = '$ticket->fecha_finalizacion' 
               WHERE `id` = '$ticket->id_ticket'";
               
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

}
