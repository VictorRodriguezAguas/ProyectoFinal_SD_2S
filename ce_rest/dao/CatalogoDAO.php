<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CatalogoDAO
 *
 * @author ernesto.ruales
 */
class CatalogoDAO {

    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getActividades($estado) {
        $sql = "select * from actividad where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }

    function getNivelEstudio($estado) {
        $sql = "select * from nivel_estudio where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getEtiquetas($estado,$tipo) {
        $sql = "select a.*, a.etiqueta as nombre, a.etiqueta as text, a.id as value, a.etiqueta as label
                from etiqueta a where estado = '$estado' and tipo = '$tipo'";
        return $this->con->getArraySQL($sql);
    }

    function getExperiencia($estado) {
        $sql = "select * from experiencia where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getCategorias($estado) {
        $sql = "select * from categoria where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getProgramas_mercado593($idTipo) {
        $sql = "select a.id, b.nombre, a.estado
        from programa_edicion a
        inner join programa b on b.id = a.id_programa and b.estado = 'A'
        where a.fecha_inicio <= now()
          and a.fecha_fin >= now()
	  and a.estado = 'A'
          and a.id_tipo_programa = $idTipo ";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function getProgramasxTipoxAnio($id_tipo, $anio) {
        $sql = "select b.id, a.nombre, b.estado
                from programa a
               inner join programa_edicion b on b.id_programa = a.id
               where b.id_tipo_programa = $id_tipo
                 and b.anio = $anio";
        return $this->con->getArraySQL($sql);
    }
    
    function getGeneros($estado) {
        $sql = "SELECT * FROM genero where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getPaises($estado) {
        $sql = "SELECT * FROM pais where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getCiudades($pais) {
        $sql = "SELECT * FROM ciudad where pais = '$pais' and estado = 'A' order by nombre";
        return $this->con->getArraySQL($sql);
    }
    function getSituacionLaboral($estado) {
        $sql = "SELECT * FROM situacion_laboral where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getTiposEmprendimiento($estado) {
        $sql = "SELECT * FROM tipo_emprendimiento where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getEtapasEmprendimientos($estado) {
        $sql = "SELECT * FROM etapa_emprendimiento where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getFactorInicioEmprendimiento($estado) {
        $sql = "SELECT * FROM factor_inciar_emprendimiento where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getPromedioVentas($estado) {
        $sql = "SELECT * FROM promedio_ventas where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getRangoPersonas($estado) {
        $sql = "SELECT * FROM rango_persona where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getNivelAcademico($estado) {
        $sql = "SELECT * FROM nivel_academico where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getRangoEdad($estado) {
        $sql = "SELECT * FROM rango_edad where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getRedSocial($estado) {
        $sql = "SELECT a.*, '' as valor FROM red_social a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getLugarComercializacion($estado) {
        $sql = "SELECT a.* FROM lugar_comercializacion a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getCanalVentas($estado) {
        $sql = "SELECT a.* FROM canal_venta a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getEmpresaDelivery($estado) {
        $sql = "SELECT a.*, false as selected FROM empresa_delivery a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getFormaCapital($estado) {
        $sql = "SELECT a.*FROM forma_capital a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getActividadesComplementarias($estado) {
        $sql = "SELECT a.*FROM actividad_complementaria a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getSectorCrediticio($estado) {
        $sql = "SELECT a.*FROM sector_crediticio a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getPersonaSocietaria($estado) {
        $sql = "SELECT a.*FROM persona_societaria a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getTipoRechazo($estado) {
        $sql = "SELECT a.*FROM tipo_rechazo a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getTipoAprobacion($estado) {
        $sql = "SELECT a.*FROM tipo_aprobacion a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    function getRubrica($estado) {
        $sql = "SELECT a.*FROM rubrica_ce a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getRazonesNoEmpreder($estado) {
        $sql = "SELECT a.*FROM razon_no_emprender a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getInteresCentroEmprendimiento($estado) {
        $sql = "SELECT a.*FROM interes_centro_emprendimiento a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getEjesMentoria($estado) {
        $sql = "SELECT a.*FROM eje_mentoria a where estado = '$estado'";
        return $this->con->getArraySQL($sql);
    }
    
    function getEtapasXSubPrograma($id_sub_programa, $estado) {
        $sql = "SELECT a.id, a.nombre, a.estado FROM etapa a where estado = '$estado' and id_sub_programa = '$id_sub_programa'";
        return $this->con->getArraySQL($sql);
    }
    
    function getProgramas($estado='A') {
        $sql = "select * from programa where estado = (case '$estado' when 'T' then '$estado' else '$estado' end );";
        return $this->con->getArraySQL($sql);
    }
    
    function getSubProgramaxIdPrograma($id_programa, $estado) {
        $sql = "select * from sub_programa where id_programa = $id_programa 
                and estado = (case '$estado' when 'T' then '$estado' else '$estado' end );";
        return $this->con->getArraySQL($sql);
    }
    
    function getEventosEpico($fecha_desde=null) {
        $sql = "SELECT a.id, b.post_title as title, concat('https://epico.gob.ec/events/',b.post_name) as url1, c.tstart, c.tend, 
                    c.dstart as start, c.dend as end, true as stick
                FROM epicoez2_epibase.epc_mec_events a
                inner join epicoez2_epibase.epc_posts b on b.ID = a.post_id
                inner join epicoez2_epibase.epc_mec_dates c on c.post_id = a.post_id
                where b.post_status = 'publish'";
        if(!is_null($fecha_desde)){
            $sql .= " and a.start >= '$fecha_desde'";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function getEstadosEmprendedor($parametros) {
        General::asignarNullEntidad($parametros, "email");
        General::asignarNullEntidad($parametros, "identificacion");
        General::asignarNullEntidad($parametros, "ruc_rise");
        $respuesta = new stdClass();
        $respuesta->emprendedor= null;
        $respuesta->emprendimiento = null;
        $respuesta->solicitud = null;
        
        $sql = "select a.id, per.nombre, per.apellido, a.estado, per.email, a.fecha_registro, per.id_usuario
            from emprendedor a
            left outer join persona per on per.id = a.id_persona ";        
        if(!is_null($parametros->ruc_rise) && $parametros->ruc_rise!= ''){
            $sql .=" inner join emprendedor_emprendimiento b on b.id_emprendedor = a.id
            inner join emprendimiento c on c.id = b.id_emprendimiento and ruc_rise = '$parametros->ruc_rise'";
        }        
        $sql .="    where a.estado <> 'E' ";        
        if(!is_null($parametros->email) && $parametros->email!= ''){
            $sql .= " and a.email = '$parametros->email' ";
        }        
        if(!is_null($parametros->identificacion) && $parametros->identificacion!= ''){
            $sql .= " and per.identificacion = '$parametros->identificacion' ";
        }
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $datos = null;
        if ($row = $resultado_consulta->fetch_array()) {
            $datos = new stdClass();
            $datos->id = $row["id"];
            $datos->nombre = $row["nombre"];
            $datos->apellido = $row["apellido"];
            $datos->email = $row["email"];
            $datos->estado = $row["estado"];
            $datos->id_usuario = $row["id_usuario"];
            $datos->fecha_registro = $row["fecha_registro"];
        }
        $respuesta->emprendedor = $datos;        
        if(is_null($respuesta->emprendedor)){
            return $respuesta;
        }
        
        
        
        $id = $respuesta->emprendedor->id;
        $sql = "select a.id, a.nombre, a.estado, a.persona from emprendimiento a 
                inner join person
                inner join emprendedor_emprendimiento b on b.id_emprendimiento = a.id and b.id_emprendedor = $id;";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $datos = null;
        if ($row = $resultado_consulta->fetch_array()) {
            $datos = new stdClass();
            $datos->id = $row["id"];
            $datos->nombre = $row["nombre"];
            $datos->estado = $row["estado"];
            $datos->persona = $row["persona"];
        }
        $respuesta->emprendimiento = $datos;
        if(is_null($respuesta->emprendimiento)){
            return $respuesta;
        }
        
        
        $id = $respuesta->emprendimiento->id;
        $sql = "select a.id, a.id_servicio, b.nombre, a.id_emprendimiento, a.estado, a.fecha_registro, a.fecha_aprobacion_rechazo, a.fecha_aprobacion_rechazo_aut 
                from emprendedor_solicitud_servicio a 
                inner join servicio b on b.id = a.id_servicio
            where a.id_emprendimiento = $id;";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $datos = null;
        if ($row = $resultado_consulta->fetch_array()) {
            $datos = new stdClass();
            $datos->id = $row["id"];
            $datos->id_servicio = $row["id_servicio"];
            $datos->nombre = $row["nombre"];
            $datos->estado = $row["estado"];
            $datos->fecha_registro = $row["fecha_registro"];
            $datos->fecha_aprobacion_rechazo = $row["fecha_aprobacion_rechazo"];
            $datos->fecha_aprobacion_rechazo_aut = $row["fecha_aprobacion_rechazo_aut"];
        }
        $respuesta->solicitud = $datos;
        
        return $respuesta;
    }
    
    function getAplicaciones($estado) {
        $sql = "SELECT * FROM ".basedatos::$baseSeguridad.".aplicacion where estado = '$estado';";
        return $this->con->getArraySQL($sql);
    }
    
    function getTipoActividad($estado) {
        $sql = "SELECT * FROM tipo_actividad where estado = '$estado';";
        return $this->con->getArraySQL($sql);
    }
    
    function getTipoEjecucion($estado) {
        $sql = "SELECT * FROM tipo_ejecucion where estado = '$estado';";
        return $this->con->getArraySQL($sql);
    }
    
    function getListaNemonicoFle() {
        $sql = "SELECT * FROM nemonico_file";
        return $this->con->getArraySQL($sql);
    }
    
    function getNemonicoFile($nemonico) {
        $sql = "SELECT * FROM nemonico_file where nemonico = '$nemonico'";
        return $this->con->getEntidad($sql);
    }
    
    function getActividadesxSubPrograma($id_sub_programa) {
        $sql = "select 	c.nombre as sub_programa,
                b.id as id_etapa,
                c.id as id_sub_programa,
                d.id as id_tipo_actividad,
                a.id as id_actividad_etapa,
		b.nombre as etapa,
                d.nombre as tipo_actividad,
                a.id, a.nombre, a.estado,
                concat(b.nombre, '/',a.nombre ) as actividad,
                a.id_actividad_padre
        from actividad_etapa a
        inner join etapa b on b.id = a.id_etapa
        inner join sub_programa c on c.id = b.id_sub_programa
        inner join tipo_actividad d on d.id = a.id_tipo_actividad
        where b.id_sub_programa = $id_sub_programa and a.estado = 'A'";
        return $this->con->getArraySQL($sql);
    }
    
    function getAplicacionExterna($estado) {
        $sql = "SELECT * FROM aplicacion_externa where estado = '$estado';";
        return $this->con->getArraySQL($sql);
    }
    
    function getTipoEvento($estado='A') {
        $sql = "select * from tipo_evento where estado = (case '$estado' when 'T' then '$estado' else '$estado' end );";
        return $this->con->getArraySQL($sql);
    }
    
    function getEstadoActividad($estado='A') {
        $sql = "select * from estado_actividad where estado = (case '$estado' when 'T' then '$estado' else '$estado' end );";
        return $this->con->getArraySQL($sql);
    }
    
    function getMotivoCancelar($estado='A') {
        $sql = "select * from motivo_cancelar_agenda where estado = (case '$estado' when 'T' then '$estado' else '$estado' end );";
        return $this->con->getArraySQL($sql);
    }
    
    function getTipoAsistencia($estado='A') {
        $sql = "select * from tipo_asistencia where estado = (case '$estado' when 'T' then '$estado' else '$estado' end );";
        return $this->con->getArraySQL($sql);
    }

    function getInstitucion() {
        $sql = "SELECT * FROM ".basedatos::$baseSeguridad.".institucion ";
        return $this->con->getArraySQL($sql);
    }
    
    function getFeriados() {
        $sql = "select * from feriado_nacional where anio = year(now())";
        return $this->con->getArraySQL($sql);
    }
    
    function getUbicaciones($codigo, $estado){
        $sql = "SELECT a.* FROM ubicacion a where estado = '$estado'";
        if(!is_null($codigo)){
            $sql .= " and id_ubicacion_padre = '$codigo'";
        }else{
            $sql .= " and id_ubicacion_padre is null";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function getEdiciones($id_sub_programa) {
        $sql = "select * from edicion where id_sub_programa = $id_sub_programa";
        return $this->con->getArraySQL($sql);
    }
}
