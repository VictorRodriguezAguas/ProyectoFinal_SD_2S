<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmprendedorDAO
 *
 * @author ernesto.ruales
 */
class EmprendedorDAO {

    //put your code here

    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function insertarEmprendedor($emprendedor) {
        General::setNullSql($emprendedor, "acepta_terminos_registro");
        General::setNullSql($emprendedor, "id_razon_no_emprender");
        General::setNullSql($emprendedor, "id_usuario");
        General::setNullSql($emprendedor, "estado_ce");

        $sql = "INSERT INTO `emprendedor`
        (`estado`, acepta_terminos_registro, 
          id_persona, id_razon_no_emprender, 
          id_usuario, estado_ce)
        VALUES
        ('$emprendedor->estado',$emprendedor->acepta_terminos_registro,
         '$emprendedor->id_persona', $emprendedor->id_razon_no_emprender,
          $emprendedor->id_usuario, $emprendedor->estado_ce);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }

    function getEmprendedorPorId($id) {
        $sql = "Select a.*, per.*, a.id as id_emprendedor
            from emprendedor a 
            left outer join persona per on per.id = a.id_persona 
            where a.id = '$id'";
        return $this->con->getEntidad($sql);
    }

    function getEmprendedorPorIdUsuario($id) {
        $respuesta = new stdClass();
        $sql = "Select a.id as id_emprendedor, a.*
            from emprendedor a 
            where a.id_usuario = $id";
        return $this->con->getEntidad($sql);
    }

    function getEmprendedorPorIdPersona($id) {
        $respuesta = null;
        $sql = "Select a.id as id_emprendedor, a.*
            from emprendedor a 
            where a.id_persona = $id";
        return $this->con->getEntidad($sql);
    }

    function getEmprendedorConsulta($estado, $id_genero, $nombre, $id_usuario, $identificacion, $nombre_emprendimiento, $email) {
        $sql = "SELECT emp.*, emp.id as id_emprendedor, per.*, j.nombre as tipo_aprobacion, b.nombre as genero, c.nombre as etapa_emprendimiento,
				-- f.nombre as rango_edad, 
                                g.nombre as situacion_laboral,
				h.nombre as tipo_emprendimiento, i.nombre as ciudad,
				TIMESTAMPDIFF(YEAR,per.fecha_nacimiento,CURDATE()) as edad,
                                a.id as id_emprendimiento, a.nombre as nombre_emprendimiento, a.id_etapa_emprendimiento,
                                a.cant_socios, a.emprendimiento_formalizado, a.ruc_rise, a.razon_social,a.id_tipo_emprendimiento,
                                a.venta_mensual, a.direccion, a.latitud, a.longitud, a.persona, a.opera_ruc_rise,
                                a.id_tipo_persona_societaria, a.otra_persona_societaria, a.producto, a.descripcion_emprendimiento,
                                a.foto_producto1, a.foto_producto2,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto1) as url_foto_producto1, 
                                concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto2) as url_foto_producto2, 
                                a.dispuesto_obtener_ruc_rise,
                                DATE_FORMAT(emp.fecha_registro, '%d/%m/%Y') as fecha
                FROM emprendedor emp 
                inner join persona per on per.id = emp.id_persona
                inner join genero b on b.id = per.id_genero
                inner join situacion_laboral g on g.id = per.id_situacion_laboral
                inner join ubicacion i on i.id = per.id_ciudad
                ";
        if ($id_usuario !== '1') {
            $sql .=" inner join usuario_asignacion_emprendedor usu on usu.id_usuario = $id_usuario and usu.id_emprendedor = emp.id";
        }
        $sql .=" 
                left outer join emprendedor_emprendimiento ee on ee.id_emprendedor = emp.id
                left outer join emprendimiento a on a.id = ee.id_emprendimiento
                left outer join etapa_emprendimiento c on c.id = a.id_etapa_emprendimiento
                left outer join tipo_emprendimiento h on h.id = a.id_tipo_emprendimiento
                left outer join tipo_aprobacion j on j.id = emp.id_tipo_aprobacion 
               where emp.estado = (case '$estado' when 'T' then emp.estado else '$estado' end) 
                  and (per.nombre like '%$nombre%' or per.apellido like '%$nombre%'  )
                  and emp.estado <> 'E' 
                ";

        if (!is_null($id_genero)) {
            $sql .=" and per.id_genero = $id_genero";
        }
        if ($identificacion != '') {
            $sql .=" and per.identificacion = '$identificacion'";
        }
        if ($nombre_emprendimiento != '') {
            $sql .=" and a.nombre like '%$nombre_emprendimiento%'";
        }
        if ($email != '') {
            $sql .=" and per.email = '$email'";
        }
        $sql .=" order by emp.fecha_registro";

        return $this->con->getArraySQL($sql);
    }

    function actualizarEmprendedor($emprendedor) {
        General::setNullSql($emprendedor, "id_tipo_rechazo");
        General::setNullSql($emprendedor, "id_tipo_aprobacion");
        General::setNullSql($emprendedor, "descripcion_rechazo");
        General::setNullSql($emprendedor, "id_razon_no_emprender");
        General::setNullSql($emprendedor, "estado_ce");
        $sql = "UPDATE `emprendedor`
                    SET
                    `estado` = '$emprendedor->estado',
                    `estado_ce` = ifnull($emprendedor->estado_ce, estado_ce),
                    `id_tipo_rechazo` = $emprendedor->id_tipo_rechazo,
                    descripcion_rechazo = $emprendedor->descripcion_rechazo,
                    id_razon_no_emprender = $emprendedor->id_razon_no_emprender,
                    id_tipo_aprobacion = $emprendedor->id_tipo_aprobacion ";
        if (General::tieneValor($emprendedor, "id_usuario")) {
            $sql .= "    ,`id_usuario` = '$emprendedor->id_usuario' ";
        }
        if (General::tieneValor($emprendedor, "id_usuario_aprobacion")) {
            $sql .= "    ,`id_usuario_aprobacion` = '$emprendedor->id_usuario_aprobacion' 
                         , fecha_aprobacion = now()";
        }
        $sql .= "    WHERE `id` = '$emprendedor->id';
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function getEmprendedorNoAsignado() {
        $lista = array();
        //$sql = "select * from menu order by id_menu_padre, id";
        $sql = "select a.id as id_emprendedor
                    from emprendedor a 
                    where not exists (select 1 from usuario_asignacion_emprendedor b where b.id_emprendedor = a.id) order by a.id";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $i = 0;
        while ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->id_emprendedor = $row["id_emprendedor"];
            $lista[$i] = $respuesta;
            $i++;
        }
        return $lista;
    }

    function asignarEmprededoraUsuario($id_emprendedor, $id_usuario) {
        $sql = "INSERT INTO `usuario_asignacion_emprendedor`
                (`id_emprendedor`,`id_usuario`)
                VALUES
                ($id_emprendedor,$id_usuario);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function getEmprendedoresParaCorreo() {
        $lista = array();
        $sql = "select a.id, per.nombre, per.apellido, per.email, per.id_tipo_aprobacion, a.estado, per.identificacion, b.usuario
                    from emprendedor a
                left outer join persona per on per.id = a.id_persona
                    left outer join " . basedatos::$baseSeguridad . ".usuario b on b.id = a.id_usuario
                    where not exists (select 1 from cola_correos where destinatario = a.email)
                    and a.estado in ('A','R') ";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $i = 0;
        while ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->id = $row["id"];
            $respuesta->nombre = $row["nombre"];
            $respuesta->apellido = $row["apellido"];
            $respuesta->email = $row["email"];
            $respuesta->id_tipo_aprobacion = $row["id_tipo_aprobacion"];
            $respuesta->estado = $row["estado"];
            $respuesta->usuario = $row["usuario"];
            $respuesta->identificacion = $row["identificacion"];
            $lista[$i] = $respuesta;
            $i++;
        }
        return $lista;
    }

    function getEmprendedoresCE($nombre, $inicial) {
        $sql = "
            select a.id, a.id as id_persona, a.apellido, a.nombre, a.perfil, a.frase_perfil, usu.foto,
            concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),usu.foto) as url_foto
            from persona a
            inner join ".basedatos::$baseSeguridad.".usuario usu on usu.id = a.id_usuario
            left outer join emprendedor b on b.id_persona = a.id
            where a.id_interes_centro_emprendimiento is not null 
                ";

        if ($nombre != '') {
            $sql .=" and concat(a.nombre, ' ' ,a.apellido) like '%$nombre%'";
        }
        if ($inicial != '') {
            $sql .=" and a.apellido like '$inicial%'";
        }
        $sql .=" order by a.apellido asc, a.nombre asc";
        return $this->con->getArraySQL($sql);
    }
    
    function getEmprendedores($param){
        $sql="
            SELECT e.id as 'id_emprendedor',e.estado as 'estado_emprendedor',e.fecha_registro as 'fecha_registro_emprendedor',
            e.id_usuario,e.acepta_terminos_registro,p.id as id_persona,e.estado_ce,p.nombre,p.apellido,p.fecha_nacimiento,
            p.id_genero,(select g.nombre from genero g where g.id=p.id_genero) as 'genero',
            p.id_ciudad,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad',p.email,p.telefono,p.id_situacion_laboral,
            (select sl.nombre from situacion_laboral sl where sl.id=p.id_situacion_laboral) as 'situacion_laboral',p.tipo_identificacion,
            p.identificacion,p.id_nivel_academico,(select nl.nombre from nivel_academico nl where nl.id=p.id_nivel_academico) as 'nivel_academico',
            p.direccion,p.id_ciudad_domicilio,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad_domicilio',p.telefono_fijo,p.cv,
            ee.id_emprendimiento,
            emp.nombre as 'nombre_emprendimiento',emp.id_etapa_emprendimiento,
            (select ete.nombre from etapa_emprendimiento ete where ete.id = emp.id_etapa_emprendimiento) as 'etapa_emprendimiento',
            emp.cant_socios,emp.emprendimiento_formalizado,emp.ruc_rise,emp.razon_social,emp.nombre_comercial,
            emp.id_tipo_emprendimiento, (select te.nombre from tipo_emprendimiento te where te.id=emp.id_tipo_emprendimiento) as 'tipo_emprendimiento',
            emp.venta_mensual,emp.ganancia_anual,emp.id_lugar_comercializacion,
            (select lc.nombre from lugar_comercializacion lc where lc.id = emp.id_lugar_comercializacion) as 'lugar_comercializacion',
            emp.numero_labora,emp.utiliza_plataforma_electronica,emp.estado as 'estado_emprendimiento',emp.direccion as 'direccion_emprendimiento',emp.logo,
            emp.opera_ruc_rise,emp.telefono_whatsapp,
            ie.id as id_inscripcion,
            ie.fase, (select etp.nombre from etapa etp where etp.id=ie.fase) as 'fase_nombre',
            concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),usu.foto) as url_foto ";
        if(General::tieneValor($param, "id_actividad") || General::tieneValor($param, "estado_actividad") ||
                    General::tieneValor($param, "id_evento")){
            $sql .= " , act.id as id_actividad_inscripcion, ae.nombre as actividad";
        }
        $sql .= "    FROM persona p
            INNER JOIN ".basedatos::$baseSeguridad.".usuario usu on usu.id = p.id_usuario 
            LEFT OUTER JOIN emprendedor e ON e.id_persona=p.id
            LEFT OUTER JOIN emprendedor_emprendimiento ee ON e.id=ee.id_emprendedor
            LEFT OUTER JOIN emprendimiento emp ON ee.id_emprendimiento=emp.id ";
        if(General::tieneValor($param, "id_etapa") || General::tieneValor($param, "id_actividad") || 
                General::tieneValor($param, "estado_actividad") || !$param->todos ||
                General::tieneValor($param, "id_evento")){
            $sql .="    INNER JOIN inscripcion_edicion ie ON ie.id_persona=p.id ";
            if(General::tieneValor($param, "id_actividad") || General::tieneValor($param, "estado_actividad") ||
                    General::tieneValor($param, "id_evento")){
                $sql .="    INNER JOIN actividad_edicion act ON act.id_inscripcion=ie.id 
                            INNER JOIN actividad_etapa ae ON ae.id = act.id_actividad_etapa 
                        ";
                if(General::tieneValor($param, "id_actividad")){
                    $sql .= " and act.id_actividad_etapa = '$param->id_actividad'";
                }
                if(General::tieneValor($param, "estado_actividad")){
                    $sql .= " and act.estado = '$param->estado_actividad'";
                }
                if(General::tieneValor($param, "id_evento")){
                    $sql .= " INNER JOIN agenda ag on ag.id = act.id_agenda and ag.id_evento = '$param->id_evento' ";
                }
            }
        }else{
            $sql .="    LEFT JOIN inscripcion_edicion ie ON ie.id_persona=p.id ";
        }
        
        $sql .="    where p.estado='A'";
        if(General::tieneValor($param, "nombre")){
            $sql .= "    and (concat(p.nombre , ' ', p.apellido) like '%$param->nombre%'";
            $sql .= "    or concat(p.apellido, ' ', p.nombre) like '%$param->nombre%' )";
        }
        if(General::tieneValor($param, "email")){
            $sql .= "    and p.email = '$param->email'";
        }
        if(General::tieneValor($param, "nombre_emprendimiento")){
            $sql .= "    and emp.nombre like '%$param->nombre_emprendimiento%'";
        }
        if(General::tieneValor($param, "id_etapa")){
            $sql .= "    and ie.fase = '$param->id_etapa' ";
        }
        if(General::tieneValor($param, "id_persona")){
            $sql .= "    and p.id = '$param->id_persona' ";
        }
        $sql .= "    limit 0,1000";
        //print "$sql";
        return $this->con->getArraySQL($sql);
    }

    function getEmprendedorAT($id_persona){
        $sql = "
            SELECT e.id as 'id_emprendedor',e.estado as 'estado_emprendedor',e.fecha_registro as 'fecha_registro_emprendedor',
            e.id_usuario,e.acepta_terminos_registro,e.id_persona,e.estado_ce,p.nombre,p.apellido,p.fecha_nacimiento,
            p.id_genero,(select g.nombre from genero g where g.id=p.id_genero) as 'genero',
            p.id_ciudad,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad',p.email,p.telefono,p.id_situacion_laboral,
            (select sl.nombre from situacion_laboral sl where sl.id=p.id_situacion_laboral) as 'situacion_laboral',p.tipo_identificacion,
            p.identificacion,p.id_nivel_academico,(select nl.nombre from nivel_academico nl where nl.id=p.id_nivel_academico) as 'nivel_academico',
            p.direccion,p.id_ciudad_domicilio,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad_domicilio',p.telefono_fijo,p.cv,
            ee.id_emprendimiento,
            emp.nombre as 'nombre_emprendimiento',emp.id_etapa_emprendimiento,
            (select ete.nombre from etapa_emprendimiento ete where ete.id = emp.id_etapa_emprendimiento) as 'etapa_emprendimiento',
            emp.cant_socios,emp.emprendimiento_formalizado,emp.ruc_rise,emp.razon_social,emp.nombre_comercial,
            emp.id_tipo_emprendimiento, (select te.nombre from tipo_emprendimiento te where te.id=emp.id_tipo_emprendimiento) as 'tipo_emprendimiento',
            emp.venta_mensual,emp.ganancia_anual,emp.id_lugar_comercializacion,
            (select lc.nombre from lugar_comercializacion lc where lc.id = emp.id_lugar_comercializacion) as 'lugar_comercializacion',
            emp.numero_labora,emp.utiliza_plataforma_electronica,emp.estado as 'estado_emprendimiento',emp.direccion as 'direccion_emprendimiento',emp.logo,
            emp.opera_ruc_rise,emp.telefono_whatsapp,
            ie.fase, (select etp.nombre from etapa etp where etp.id=ie.fase) as 'fase_nombre',(case emp.persona when 'N' then 'Persona Natural' when 'J' then 'Persona Jurídica' else emp.persona end) as 'tipo_persona',
            concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),us.foto) as url_foto
            FROM persona p
            INNER JOIN inscripcion_edicion ie
            ON ie.id_persona=p.id
            LEFT OUTER JOIN emprendedor e
            ON e.id_persona=p.id
            LEFT OUTER JOIN ".basedatos::$baseSeguridad.".usuario us
            ON us.id=p.id_usuario
            LEFT OUTER JOIN emprendedor_emprendimiento ee
            ON e.id=ee.id_emprendedor
            LEFT OUTER JOIN emprendimiento emp
            ON ee.id_emprendimiento=emp.id and emp.id = ie.id_emprendimiento

            where p.id = $id_persona";
        //print "$sql";
        return $this->con->getEntidad($sql);
    }
}
