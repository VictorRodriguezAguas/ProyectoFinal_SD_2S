<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProgramaDAO
 *
 * @author ernesto.ruales
 */
class ProgramaDAO {

    //put your code here

    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getProgramas($estado = 'A') {
        $sql = "select * from programa where estado = (case '$estado' when 'T' then '$estado' else '$estado' end );";
        return $this->con->getArraySQL($sql);
    }

    function getSubProgramaxIdPrograma($id_programa) {
        $sql = "select * from sub_programa where id_programa = $id_programa;";
        return $this->con->getArraySQL($sql);
    }

    function getEtapaxIdSubPrograma($id_sub_programa) {
        $sql = "select a.*, a.id as id_etapa, a.nombre as etapa,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as url_logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as url_banner,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as url_icono
                from etapa a where id_sub_programa = $id_sub_programa;";
        return $this->con->getArraySQL($sql);
    }

    function getActividadesxIdEtapa($id_etapa, $estado = 'T') {
        $sql = "select 	c.nombre as sub_programa,
		b.nombre as etapa,
                d.nombre as tipo_actividad,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as url_logo,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as url_banner,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as url_icono,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.archivo) as url_archivo,
                a.id as id_actividad_etapa,
                a.*
        from actividad_etapa a
        inner join etapa b on b.id = a.id_etapa
        inner join sub_programa c on c.id = b.id_sub_programa
        inner join tipo_actividad d on d.id = a.id_tipo_actividad
        where a.id_etapa = $id_etapa 
            and a.estado = (case '$estado' when 'T' then a.estado else '$estado' end) 
                order by id_actividad_padre, orden "
                ;
        return $this->con->getArraySQL($sql);
    }

    function getSubProgramaxIdInscripcion($id_inscripcion) {
        $sql = "select a.id as id_inscripcion, c.id as id_sub_programa, 
		c.nombre as sub_programa,
                c.estado, c.version, 
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.icono) as icono,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.logo) as logo,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.banner) as banner,
                a.id_persona, a.id_emprendedor, a.id_emprendimiento, a.fase
            from inscripcion_edicion a
            inner join edicion b on b.id = a.id_edicion
            inner join sub_programa c on c.id = b.id_sub_programa
           where a.id = $id_inscripcion;";
        return $this->con->getEntidad($sql);
    }

    function getProgramasInscritosxIdPersona($id_persona) {
        $sql = "select a.id as id_inscripcion, a.*,
		c.nombre as sub_programa
                from inscripcion_edicion a
               inner join edicion b on b.id = a.id_edicion
               inner join sub_programa c on c.id = b.id_sub_programa
               where b.estado = 'A'
                 and a.id_persona = '$id_persona'";
        return $this->con->getArraySQL($sql);
    }

    function getSubProgramaxIds($id_persona, $id_emprendimiento, $id_emprendedor, $id_sub_programa) {
        $sql = "select a.id as id_inscripcion, c.id as id_sub_programa, 
		c.nombre as sub_programa,
                c.estado, c.version, c.icono, c.logo, c.banner,
                a.id_persona, a.id_emprendedor, a.id_emprendimiento, a.fase
            from inscripcion_edicion a
            inner join edicion b on b.id = a.id_edicion
            inner join sub_programa c on c.id = b.id_sub_programa
                where c.id = $id_sub_programa
                and a.id_persona = $id_persona";
        if (!is_null($id_emprendedor)) {
            $sql .= "  and a.id_emprendedor = $id_emprendedor ";
        }
        if (!is_null($id_emprendimiento)) {
            $sql .= "  and a.id_emprendimiento = $id_emprendimiento";
        }
        return $this->con->getEntidad($sql);
    }

    function getActividadxEtapaxInscripcion($id_inscripcion, $id_etapa, $id_actividad_padre = null, $todos=true) {
        $sql = "
                select 
                    a.id as id_etapa, 
                    a.nombre as etapa, 
                    a.id_sub_programa, 
                    a.estado, 
                    a.inicio, 
                    a.fin, 
                    a.orden, 
                    a.antecesor, 
                    a.predecesor, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as icono,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as banner,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.plan_trabajo) as plan_trabajo,
                    
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, 
                    b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.boton_guardar,
                    b.componente,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    b.codigo_act,
                    
                    e.*,

                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, 
                    c.duracion, 
                    -- (case c.is_agregada_manual when 'SI' then c.orden when 'NO' then b.orden else b.orden end) as orden_actividad_inscripcion,
                    -- ifnull(c.orden, b.orden) as orden_actividad_inscripcion,
                    b.orden as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, 
                    c.fecha_registro, 
                    c.fecha_aprobacion, 
                    c.id_usuario_aprobacion,
                    c.tiempo_actividad, 
                    c.estado as estado_actividad_inscripcion,
                    c.id_mentor, 
                    c.id_asistente_tecnico, 
                    c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    ifnull(ifnull(f.texto_mensaje, d.nombre), b.mensaje_estado_ina) as estado_actividad,
                    c.id_agenda, 
                    c.archivo, 
                    ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, 
                    c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.archivo) as url_archivo,
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    ifnull(c.id_inscripcion_etapa, ie.id) as id_inscripcion_etapa,
                    c.id_eje_mentoria,
                    c.id_reunion_asig
              from etapa a
             inner join actividad_etapa b on b.id_etapa = a.id
             left outer join inscripcion_etapa ie on ie.id_etapa = a.id 
               and ie.id_inscripcion = $id_inscripcion 
               and ie.estado not in ('CF', 'AN')
              left outer join actividad_edicion c 
                on c.id_actividad_etapa = b.id 
               and c.id_inscripcion = $id_inscripcion
              left outer join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where a.id = $id_etapa
               and a.estado = 'A'
               and b.estado = 'A'
                 "
        ;
        if(!$todos){
            if(!is_null($id_actividad_padre)){
                $sql .= " and b.id_actividad_padre = $id_actividad_padre and (is_agregada_manual = 'NO' || is_agregada_manual is null) ";
            }
            else{
                $sql .= " and (b.id_actividad_padre is null or (c.id_actividad_padre is null and c.id is not null) ) ";
            }
        }
        $sql .= " order by b.id_actividad_padre, orden_actividad_inscripcion asc, b.orden asc";
        return $this->con->getArraySQL($sql);
    }
    
    function getActividadxEtapaInscripcion($id_inscripcion, $id_inscripcion_etapa, $id_actividad_padre = null, $todos=true) {
        $sql = "
                select 
                    a.id as id_etapa, 
                    a.nombre as etapa, 
                    a.id_sub_programa, 
                    a.estado, 
                    a.inicio, 
                    a.fin, 
                    a.orden, 
                    a.antecesor, 
                    a.predecesor, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as icono,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as banner,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.plan_trabajo) as plan_trabajo,
                    
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, 
                    b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.boton_guardar,
                    b.componente,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    b.codigo_act,
                    
                    e.*,

                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, 
                    c.duracion, 
                    -- (case c.is_agregada_manual when 'SI' then c.orden when 'NO' then b.orden else b.orden end) as orden_actividad_inscripcion,
                    c.orden as orden_actividad_inscripcion,
                    -- b.orden as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, 
                    c.fecha_registro, 
                    c.fecha_aprobacion, 
                    c.id_usuario_aprobacion,
                    c.tiempo_actividad, 
                    c.estado as estado_actividad_inscripcion,
                    c.id_mentor, 
                    c.id_asistente_tecnico, 
                    c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    ifnull(ifnull(f.texto_mensaje, d.nombre), b.mensaje_estado_ina) as estado_actividad,
                    c.id_agenda, 
                    c.archivo, 
                    ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, 
                    c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.archivo) as url_archivo,
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    ifnull(c.id_inscripcion_etapa, ie.id) as id_inscripcion_etapa,
                    c.id_eje_mentoria,
                    c.id_reunion_asig
              from etapa a
             inner join inscripcion_etapa ie on ie.id_etapa = a.id 
               and ie.id_inscripcion = $id_inscripcion 
               and ie.id = $id_inscripcion_etapa
             inner join actividad_edicion c 
                on c.id_inscripcion_etapa = ie.id
               and c.id_inscripcion = ie.id_inscripcion
             inner join actividad_etapa b 
                on b.id = c.id_actividad_etapa
              left outer join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where a.estado = 'A'
               and b.estado = 'A'
                 "
        ;
        if(!$todos){
            if(!is_null($id_actividad_padre)){
                $sql .= " and c.id_actividad_padre = $id_actividad_padre ";
            }
            else{
                $sql .= " and c.id_actividad_padre is null ";
            }
        }
        $sql .= " order by orden_actividad_inscripcion asc";
        
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    function getActividadInscripcionxId($id_actividad, $id_actividad_etapa=null, $id_inscripcion=null, $id_inscripcion_etapa=null) {
        $sql = "
                select a.id as id_etapa, a.nombre as etapa, 
                    a.id_sub_programa, 
                    a.estado, 
                    a.inicio, 
                    a.fin, 
                    a.orden, 
                    a.antecesor, 
                    a.predecesor, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as icono,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as banner,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.plan_trabajo) as plan_trabajo,
                    
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.componente,
                    b.boton_guardar,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    b.codigo_act,
                    
                    e.*,
                    
                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, c.duracion, 
                    -- ifnull(c.orden, b.orden) as orden_actividad_inscripcion,
                    (case c.is_agregada_manual when 'SI' then c.orden when 'NO' then b.orden else b.orden end) as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, c.fecha_registro, c.fecha_aprobacion, c.id_usuario_aprobacion,
                    c.tiempo_actividad, c.estado as estado_actividad_inscripcion,
                    c.id_mentor, c.id_asistente_tecnico, c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    ifnull(ifnull(f.texto_mensaje, d.nombre), b.mensaje_estado_ina) as estado_actividad,
                    c.id_agenda, c.archivo, ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.archivo) as url_archivo,
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    ifnull(c.id_inscripcion_etapa, ie.id) as id_inscripcion_etapa,
                    c.id_eje_mentoria,
                    c.id_reunion_asig
              
              ";
        if(!is_null($id_actividad)){
        $sql .=   "  
              from actividad_edicion c 
             inner join actividad_etapa b on b.id = c.id_actividad_etapa
             inner join inscripcion_etapa ie 
                on ie.id_inscripcion = c.id_inscripcion
               and ie.id = c.id_inscripcion_etapa
               and ie.estado <> 'CF'
             inner join etapa a
                on a.id = ie.id_etapa
             inner join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where c.id = '$id_actividad'
               and a.estado = 'A'
               and b.estado = 'A'
               order by b.orden asc
                 ";
        }
        else{
            $sql .= "
              from etapa a
             inner join actividad_etapa b on b.id_etapa = a.id
              left outer join actividad_edicion c 
                on c.id_actividad_etapa = b.id 
               and c.id_inscripcion = $id_inscripcion
              left outer join inscripcion_etapa ie on ie.id_etapa = a.id 
               and ie.id_inscripcion = $id_inscripcion 
               and ie.id = $id_inscripcion_etapa
               and ie.estado <> 'CF'
              left outer join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where b.id = $id_actividad_etapa
               and a.estado = 'A'
               and b.estado = 'A'";
        }
        //print $sql;
        return $this->con->getEntidad($sql);
    }
    
    function getActividadesInscripcionxIdTipoActividad($id_tipo_actividad, $id_inscripcion, $id_inscripcion_etapa) {
        $sql = "
            select a.id as id_etapa, a.nombre as etapa, 
                    a.id_sub_programa, 
                    a.estado, 
                    a.inicio, 
                    a.fin, 
                    a.orden, 
                    a.antecesor, 
                    a.predecesor, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as icono,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as banner,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.plan_trabajo) as plan_trabajo,
                    
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.componente,
                    b.boton_guardar,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    b.codigo_act,
                    
                    e.*,
                    
                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, c.duracion, 
                    (case c.is_agregada_manual when 'SI' then c.orden when 'NO' then b.orden else b.orden end) as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, c.fecha_registro, c.fecha_aprobacion, c.id_usuario_aprobacion,
                    c.tiempo_actividad, c.estado as estado_actividad_inscripcion,
                    c.id_mentor, c.id_asistente_tecnico, c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    ifnull(ifnull(f.texto_mensaje, d.nombre), b.mensaje_estado_ina) as estado_actividad,
                    c.id_agenda, c.archivo, ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.archivo) as url_archivo,
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    ifnull(c.id_inscripcion_etapa, ie.id) as id_inscripcion_etapa,
                    c.id_eje_mentoria,
                    c.id_reunion_asig
              from etapa a
             inner join actividad_etapa b on b.id_etapa = a.id 
             inner join inscripcion_etapa ie on ie.id_etapa = a.id 
               and ie.id_inscripcion = $id_inscripcion
               and ie.id = $id_inscripcion_etapa
              left outer join actividad_edicion c 
                on c.id_actividad_etapa = b.id 
               and c.id_inscripcion = $id_inscripcion
               and c.id_inscripcion_etapa = ie.id
              left outer join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where b.id_tipo_actividad in ($id_tipo_actividad)
               and a.estado = 'A'
               and b.estado = 'A'
               order by c.orden
                ";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function getActividadesInscripcionXTipoActividad($id_tipo_actividad, $id_inscripcion, $id_inscripcion_etapa) {
        $sql = "
                select c.*, c.id as id_actividad_inscripcion
              from actividad_edicion c 
             inner join actividad_etapa b on b.id = c.id_actividad_etapa
             where b.id_tipo_actividad in ($id_tipo_actividad)
               and c.id_inscripcion = $id_inscripcion
               and c.id_inscripcion_etapa = $id_inscripcion_etapa
               order by c.orden "
                ;
        return $this->con->getArraySQL($sql);
    }
    
    function getMentoriasAsignadas($id_inscripcion, $id_inscripcion_etapa) {
        $sql = "
                select c.id_eje_mentoria, count(1) as cant
              from actividad_edicion c 
             inner join actividad_etapa b on b.id = c.id_actividad_etapa
             where b.id_tipo_actividad = 15
               and c.id_inscripcion = $id_inscripcion
               and c.id_inscripcion_etapa = $id_inscripcion_etapa
                group by c.id_eje_mentoria ";
        return $this->con->getArrayHash($sql, "id_eje_mentoria");
    }
    
    function getActividadesMentoriaOrdenada($id_inscripcion, $id_inscripcion_etapa) {
        $sql = "
                select a.*, a.id as id_actividad_inscripcion
                from actividad_edicion a
               inner join actividad_etapa b on b.id = a.id_actividad_etapa and b.id_tipo_actividad in (15,16)
               where id_inscripcion = $id_inscripcion 
                 and id_inscripcion_etapa = $id_inscripcion_etapa
                 and (a.estado <> 'CA' or a.estado is null)
                 order by id_eje_mentoria, id ";
        return $this->con->getArraySQL($sql);
        //return $this->con->getArrayHash($sql, "id_eje_mentoria");
    }
    
    function getCountActividadXEstado($id_inscripcion, $id_inscripcion_etapa, $id_tipo_actividad, $id_eje_mentoria) {
        $sql = "
                select c.estado, count(1) as cant
              from actividad_edicion c 
             inner join actividad_etapa b on b.id = c.id_actividad_etapa
             where b.id_tipo_actividad = $id_tipo_actividad
               and c.id_inscripcion = $id_inscripcion
               and c.id_inscripcion_etapa = $id_inscripcion_etapa
               and c.id_eje_mentoria = $id_eje_mentoria
                group by c.estado";
        return $this->con->getArraySQL($sql);
    }
    
    function getActividadxEtapaxInscripcionAdicionales($id_inscripcion, $id_etapa) {
        $sql = "
                select 
                    a.id as id_etapa, 
                    a.nombre as etapa, 
                    a.id_sub_programa, 
                    a.estado, 
                    a.inicio, 
                    a.fin, 
                    a.orden, 
                    a.antecesor, 
                    a.predecesor, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as icono,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as banner,
                    
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, 
                    b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.boton_guardar,
                    b.componente,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    b.codigo_act,
                    
                    e.*,

                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, 
                    c.duracion, 
                    -- ifnull(c.orden, b.orden) as orden_actividad_inscripcion,
                    (case c.is_agregada_manual when 'SI' then c.orden when 'NO' then b.orden else b.orden end) as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, 
                    c.fecha_registro, 
                    c.fecha_aprobacion, 
                    c.id_usuario_aprobacion,
                    c.tiempo_actividad, 
                    c.estado as estado_actividad_inscripcion,
                    c.id_mentor, 
                    c.id_asistente_tecnico, 
                    c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    ifnull(ifnull(f.texto_mensaje, d.nombre), b.mensaje_estado_ina) as estado_actividad,
                    c.id_agenda, 
                    c.archivo, 
                    ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, 
                    c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.archivo) as url_archivo,
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    c.id_eje_mentoria,
                    c.id_reunion_asig
              from etapa a
             inner join actividad_etapa b on b.id_etapa = a.id
             inner join actividad_edicion c 
                on c.id_actividad_etapa = b.id 
               and c.id_inscripcion = $id_inscripcion
               and c.id_etapa = a.id
              left outer join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where a.id = $id_etapa
               and a.estado = 'A'
               and b.estado = 'A'
               and c.is_agregada_manual = 'SI'
                 "
        ;
        $sql .= " order by orden_actividad_inscripcion asc";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function getActividadesEtapaAprobada($id_inscripcion, $id_etapa_inscripcion) {
        $sql = "
                select 
                    a.id as id_etapa, 
                    a.nombre as etapa, 
                    a.id_sub_programa, 
                    a.estado, 
                    a.inicio, 
                    a.fin, 
                    a.orden, 
                    a.antecesor, 
                    a.predecesor, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as icono,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as banner,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.plan_trabajo) as plan_trabajo,
                    
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, 
                    b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.boton_guardar,
                    b.componente,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    b.codigo_act,

                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, 
                    c.duracion, 
                    -- b.orden as orden_actividad_inscripcion,
                    ifnull(c.orden, b.orden) as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, 
                    c.fecha_registro, 
                    c.fecha_aprobacion, 
                    c.id_usuario_aprobacion,
                    c.tiempo_actividad, 
                    c.estado as estado_actividad_inscripcion,
                    c.id_mentor, 
                    c.id_asistente_tecnico, 
                    c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    ifnull(ifnull(f.texto_mensaje, d.nombre), b.mensaje_estado_ina) as estado_actividad,
                    c.id_agenda, 
                    c.archivo, 
                    ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, 
                    c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.archivo) as url_archivo,
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    ifnull(c.id_inscripcion_etapa, ie.id) as id_inscripcion_etapa,
                    c.id_eje_mentoria,
                    c.id_reunion_asig,
                    
                    e.*
              from inscripcion_etapa ie
             inner join etapa a on a.id = ie.id_etapa and a.version = ie.version
             inner join actividad_etapa b on b.id_etapa = a.id and b.estado = 'A'       
              left outer join actividad_edicion c 
                on c.id_actividad_etapa = b.id 
               and c.id_inscripcion = ie.id_inscripcion
               and c.id_inscripcion_etapa = ie.id
               and c.estado <> 'EL'
               and c.is_agregada_manual = 'NO'
              left outer join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where a.estado = 'A'
               and ie.id_inscripcion = $id_inscripcion
               and ie.id =$id_etapa_inscripcion
               
		union all
        
        select 
                    a.id as id_etapa, 
                    a.nombre as etapa, 
                    a.id_sub_programa, 
                    a.estado, 
                    a.inicio, 
                    a.fin, 
                    a.orden, 
                    a.antecesor, 
                    a.predecesor, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.icono) as icono,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.logo) as logo,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.banner) as banner,
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.plan_trabajo) as plan_trabajo,
                    
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, 
                    b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.boton_guardar,
                    b.componente,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    b.codigo_act,

                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, 
                    c.duracion, 
                    -- b.orden as orden_actividad_inscripcion,
                    ifnull(c.orden, b.orden) as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, 
                    c.fecha_registro, 
                    c.fecha_aprobacion, 
                    c.id_usuario_aprobacion,
                    c.tiempo_actividad, 
                    c.estado as estado_actividad_inscripcion,
                    c.id_mentor, 
                    c.id_asistente_tecnico, 
                    c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    ifnull(ifnull(f.texto_mensaje, d.nombre), b.mensaje_estado_ina) as estado_actividad,
                    c.id_agenda, 
                    c.archivo, 
                    ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, 
                    c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), c.archivo) as url_archivo,
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    ifnull(c.id_inscripcion_etapa, ie.id) as id_inscripcion_etapa,
                    c.id_eje_mentoria,
                    c.id_reunion_asig,
                    
                    e.*
              from inscripcion_etapa ie
             inner join etapa a on a.id = ie.id_etapa and a.version = ie.version
			 inner join actividad_edicion c 
                on c.id_inscripcion = ie.id_inscripcion
               and c.id_inscripcion_etapa = ie.id
               and c.estado <> 'EL'
               and c.is_agregada_manual = 'SI'
			 inner join actividad_etapa b on b.id = c.id_actividad_etapa
              left outer join estado_actividad d
                on d.codigo = c.estado
              left outer join nemonico_file e
                on e.nemonico = b.nemonico_file
              left outer join actividad_estado_mensaje f
                on f.id_actividad_etapa = b.id
               and f.estado = d.codigo
             where a.estado = 'A'
               and ie.id_inscripcion = $id_inscripcion
               and ie.id =$id_etapa_inscripcion
               
             order by 50 
                 "
        ;
        return $this->con->getArraySQL($sql);
    }

    function getActividadInscripcionExiste($id_inscripcion, $id_etapa_inscripcion, $id_actividad_etapa) {
        $sql = "
                select b.*
              from inscripcion_etapa ie
             inner join etapa a on a.id = ie.id_etapa and a.version = ie.version
             inner join actividad_etapa b on b.id_etapa = a.id and b.estado = 'A'       
             inner outer join actividad_edicion c 
                on c.id_actividad_etapa = b.id 
               and c.id_inscripcion = $id_inscripcion
               and c.id_inscripcion_etapa = ie.id
               and c.estado <> 'EL'
             where a.estado = 'A'
               and ie.id_inscripcion = $id_inscripcion 
               and ie.id = $id_etapa_inscripcion
               and b.id = $id_actividad_etapa
                 "
        ;
        return $this->con->getEntidad($sql);
    }
    
    function getActividadInscripcionIdActividadEtapa($id_inscripcion, $id_actividad_etapa) {
        $sql = "select a.estado as estado_actividad_inscripcion, a.id as id_actividad_inscripcion,
                        a.id_etapa as id_etapa_actividad_inscripcion,
                            b.*
              from actividad_edicion a 
             inner join actividad_etapa b on b.id = a.id_actividad_etapa
             where a.id_inscripcion= '$id_inscripcion'
               and a.id_actividad_etapa = '$id_actividad_etapa'";
        return $this->con->getEntidad($sql);
    }

    function insertarActividadEtapaEdicion($actividad) {
        $campos = array("id_inscripcion", "id_actividad_etapa", "codigo_referencia", "fecha_inicio", "fecha_fin",
            "fecha_max_inicio", "tipo_duracion", "duracion", "orden", "antecesor", "predecesor", "is_obligatorio",
            "fecha_aprobacion", "id_usuario_aprobacion", "tiempo_actividad", "estado", "id_mentor", "id_asistente_tecnico",
            "id_actividad_igual", "url", "id_agenda", "archivo", "id_rubrica", "id_evaluacion", "id_actividad_padre",
            "id_etapa", "is_agregada_manual", "id_inscripcion_etapa", "id_eje_mentoria", "nombre", "id_reunion_asig");

        $sql = General::insertSQL("actividad_edicion", $actividad, $campos);
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
    
    function actualizarActividadPadreActividadEdicion($actividad) {
        $campos = array("id_actividad_padre");
        $tabla="actividad_edicion";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$actividad->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($actividad->id_actividad_inscripcion);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
    }

    function actualizarActividadEtapaEdicion($actividad) {
        $campos = array("id_actividad_etapa", "codigo_referencia", "fecha_inicio", "fecha_fin", "fecha_max_inicio",
            "tipo_duracion", "duracion", "orden", "antecesor", "predecesor", "is_obligatorio",
            "fecha_aprobacion", "id_usuario_aprobacion", "tiempo_actividad", "estado", "id_mentor",
            "id_asistente_tecnico", "id_actividad_igual", "id_agenda", "archivo", "id_rubrica", "id_evaluacion",
            "id_usuario_mod", "nombre");
        $tabla="actividad_edicion";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$actividad->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($actividad->id_actividad_inscripcion);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
    }

    function actualizarFaseInscripcion($inscripcion) {

        $campos = array("fase", "id_emprendedor", "id_emprendimiento");
        $filtro = "    WHERE `id` = '$inscripcion->id_inscripcion' ";
        $sql = General::updateSQL("inscripcion_edicion", $inscripcion, $campos, $filtro);

        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function crearArchivoInscripcion($archivo) {
        $campos = array("nemonico", "archivo", "id_inscripcion_edicion");
        $sql = General::insertSQL("incripcion_edicion_archivos", $archivo, $campos);
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function actualizarArchivoInscripcion($archivo) {
        $campos = array("archivo");
        $filtro = " WHERE `id_inscripcion_edicion` = '$archivo->id_inscripcion_edicion' and nemonico = '$archivo->nemonico' ";
        $sql = General::updateSQL("incripcion_edicion_archivos", $archivo, $campos, $filtro);
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function existeArchivoInscripcion($archivo) {
        $sql = "select a.* from incripcion_edicion_archivos a 
                 WHERE `id_inscripcion_edicion` = '$archivo->id_inscripcion_edicion' 
                   and nemonico = '$archivo->nemonico' 
                ";
        return $this->con->getEntidad($sql);
    }

    function getInscripcionXidPersonaxIdSubPrograma($id_persona, $id_sub_programa) {
        $sql = "select a.id as id_inscripcion, c.id as id_sub_programa, 
		c.nombre as sub_programa,
                c.estado, c.version, c.icono, c.logo, c.banner,
                a.id_persona, a.id_emprendedor, a.id_emprendimiento, a.fase
            from inscripcion_edicion a
            inner join edicion b on b.id = a.id_edicion
            inner join sub_programa c on c.id = b.id_sub_programa
           where a.id_persona = $id_persona
             and b.id_sub_programa = $id_sub_programa
             and b.estado = 'A';";
        return $this->con->getEntidad($sql);
    }

    function getActividadEtapa($id_actividad_etapa) {
        $sql = "select a.* from actividad_etapa a
             where a.id = '$id_actividad_etapa'";
        return $this->con->getEntidad($sql);
    }
    
    function getActividadEtapaTranversal($id_inscripcion, $id_etapa, $codigo_act, $datosActivos=true){
        if($datosActivos)
            return $this->getActividadEtapaTranversalConDatosActivos($id_inscripcion, $id_etapa, $codigo_act);
        else
            return $this->getActividadEtapaTranversalSinDatosActivos ($id_inscripcion, $codigo_act);
    }
    
    function getActividadEtapaTranversalConDatosActivos($id_inscripcion, $id_etapa, $codigo_act) {
        $sql = "select              
                    b.id as id_actividad_etapa, 
                    ifnull(c.nombre, b.nombre) as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.componente,
                    b.boton_guardar,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    
                    c.id as id_actividad_inscripcion,
                    c.id_inscripcion,
                    c.codigo_referencia as cod_referencia_actividad_inscripcion,
                    c.fecha_inicio as fecha_inicio_actividad_inscripcion, 
                    c.fecha_fin as fecha_fin_actividad_inscripcion,
                    c.fecha_max_inicio as fecha_max_inicio_actividad_inscripcion,
                    c.tipo_duracion, c.duracion, 
                    -- c.orden as orden_actividad_inscripcion,
                    (case c.is_agregada_manual when 'SI' then c.orden when 'NO' then b.orden else b.orden end) as orden_actividad_inscripcion,
                    c.antecesor as antecesor_actividad_inscripcion,
                    c.predecesor as predecesor_actividad_inscripcion,
                    c.is_obligatorio, c.fecha_registro, c.fecha_aprobacion, c.id_usuario_aprobacion,
                    c.tiempo_actividad, c.estado as estado_actividad_inscripcion,
                    c.id_mentor, c.id_asistente_tecnico, c.id_actividad_igual as id_actividad_igual_inscripcion,
                    ifnull(c.url, b.url) as url_actividad_inscripcion, 
                    c.id_agenda, c.archivo, ifnull(c.id_rubrica, b.id_rubrica) as id_rubrica, c.id_evaluacion,
                    c.archivo, 
                    c.id_usuario_mod, 
                    c.id_actividad_padre as id_actividad_inscripcion_padre,
                    c.id_etapa as id_etapa_actividad_inscripcion,
                    c.is_agregada_manual,
                    c.id_eje_mentoria,
                    c.id_reunion_asig
              from actividad_etapa b
             left outer join actividad_edicion c 
                on c.id_actividad_etapa = b.id 
               and c.id_inscripcion = $id_inscripcion
               and c.id_etapa = $id_etapa
             where b.codigo_act = '$codigo_act'
               and b.estado = 'A'";
        return $this->con->getEntidad($sql);
    }
    
    function getActividadEtapaTranversalSinDatosActivos($id_inscripcion, $codigo_act) {
        $sql = "select              
                    b.id as id_actividad_etapa, 
                    b.nombre as actividad, 
                    b.estado, 
                    b.id_tipo_actividad, 
                    b.orden as orden_actividad, 
                    b.antecesor as antecesor_actividad,
                    b.predecesor as predecesor_actividad,
                    b.hora_max, b.hora_min,
                    b.cod_referencia, 
                    b.icono as icono_actividad, 
                    b.logo as logo_actividad, 
                    b.banner as banner_actividad,
                    b.actividad_igual, 
                    b.url, 
                    b.aprueba_etapa,  
                    b.id_tipo_ejecucion,
                    b.archivo as archivo_actividad, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.archivo) as url_archivo_actividad,
                    b.boton_finalizar,
                    b.componente,
                    b.boton_guardar,
                    b.id_actividad_padre,
                    b.cod_aplicacion_externa,
                    b.cod_trama,
                    b.metodo_api,
                    b.actividad_paralelo,
                    
                    null as id_actividad_inscripcion,
                    $id_inscripcion as id_inscripcion,
                    null as cod_referencia_actividad_inscripcion,
                    now() as fecha_inicio_actividad_inscripcion, 
                    null as fecha_fin_actividad_inscripcion,
                    null as fecha_max_inicio_actividad_inscripcion,
                    null as tipo_duracion, 
                    null as duracion, 
                    null as orden_actividad_inscripcion,
                    null as antecesor_actividad_inscripcion,
                    null as predecesor_actividad_inscripcion,
                    null as is_obligatorio, 
                    null as fecha_registro, 
                    null as fecha_aprobacion, 
                    null as id_usuario_aprobacion,
                    null as tiempo_actividad, 
                    null as estado_actividad_inscripcion,
                    null as id_mentor, 
                    null as id_asistente_tecnico, 
                    null as id_actividad_igual_inscripcion,
                    null as url_actividad_inscripcion, 
                    null as id_agenda, 
                    null as archivo, 
                    null as id_rubrica, 
                    null as id_evaluacion,
                    null as archivo, 
                    null as id_usuario_mod, 
                    null as id_actividad_inscripcion_padre,
                    null as id_etapa_actividad_inscripcion,
                    null as is_agregada_manual,
                    null as id_eje_mentoria,
                    null as id_reunion_asig
              from actividad_etapa b
             where b.codigo_act = '$codigo_act'
               and b.estado = 'A' ";
        $data =  $this->con->getEntidad($sql);
        if( $this->con->getError() == 1){
            throw new Exception($this->con->getMensajeError(), '0');
        }
        return $data;
    }

    function getUltimaActividad($id_inscripcion, $id_etapa, $id_actividad_padre){
        $sql = "select max(a.orden) as orden, max(a.id) as antecesor
                    from actividad_edicion a 
                   where a.id_inscripcion = $id_inscripcion 
                   and a.id_etapa = $id_etapa ";
        if(is_null($id_actividad_padre))
            $sql .= "   and a.id_actividad_padre is null";
        else
            $sql .= "   and a.id_actividad_padre = $id_actividad_padre";
        return $this->con->getEntidad($sql);
    }
    
    function getActividadInscripcionAntecesor($id_actividad_inscripcion){
        $sql = "select a.antecesor from actividad_edicion a where a.id = $id_actividad_inscripcion";
        $data = $this->con->getEntidad($sql);
        if(!is_null($data)){
            $data = $this->getActividadInscripcionxId($data->antecesor);
        }
        return $data;
    }
    
    function insertarInscripcionEtapa($etapa) {
        $campos = array("id_inscripcion", "id_etapa", "ver_mensaje", "estado", "id_etapa_anterior", "version");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $etapa->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("inscripcion_etapa", $campos, $tipodatos, $valores);
    }
    
    function actualizarInscripcionEtapa($etapa) {
        $campos = array("id_inscripcion", "fecha_aprobacion", "id_usuario_aprobacion", "observacion", "estado",
            "ver_mensaje");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $etapa->{$campo};
            $tipodatos[] = "s";
        }
        $campos_condicion = array("id");
        $campos_condicion_valor = array($etapa->id);
        $tipodatos_condicion = array("i");
        $this->con->Actualizar("inscripcion_etapa", $campos, $tipodatos, $valores, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion);
    }
    
    function getInscripcionEtapa($id_inscripcion, $id_etapa, $estado, $id=null) {
        $sql = "select a.*, a.id as id_inscripcion_etapa from inscripcion_etapa a ";
        if(!is_null($estado)){
            $sql .= " where estado = '$estado'";
        }
        else{
            $sql .= " where estado not in ('CF', 'AN')";
        }
        if(!is_null($id)){
            $sql .= " and id = '$id'";
        }
        if(!is_null($id_inscripcion)){
            $sql .= " and id_inscripcion = '$id_inscripcion'";
        }
        if(!is_null($id_etapa)){
            $sql .= " and id_etapa = '$id_etapa'";
        }
        //print $sql;
        return $this->con->getEntidad($sql);
    }
    
    function getInscripcionEtapaXId($id_inscripcion_etapa) {
        $sql = "select  
                b.nombre as etapa, 
                b.*,
		b.estado as estado_etapa,
                a.estado as estado_inscripcion_etapa,
		a.id as id_inscripcion_etapa,
                a.*,
                c.id_persona
          from inscripcion_etapa a
         inner join etapa b on b.id = a.id_etapa
         inner join inscripcion_edicion c on c.id = a.id_inscripcion
         where a.id = $id_inscripcion_etapa";
        return $this->con->getEntidad($sql);
    }
    
    function getActividadesEtapa($id_etapa, $id_padre=null, $estado='T') {
        $sql = "select a.* from actividad_etapa a
             where a.id_etapa = '$id_etapa'
                and a.estado = (case '$estado' when 'T' then a.estado else '$estado' end )";
        if(is_null($id_padre)){
            $sql .= " and a.id_actividad_padre is null ";
        }else{
            $sql .= " and a.id_actividad_padre = $id_padre ";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function actualizarNewOrdenActividades($id_etapa) {
        $sql = "
            
            update actividad_edicion set orden = 
			(select orden from actividad_etapa where id = actividad_edicion.id_actividad_etapa) 
	 where id_actividad_etapa in (select id from actividad_etapa where id_etapa = $id_etapa)
	   and exists (select 1 from inscripcion_etapa a where a.id_inscripcion = actividad_edicion.id_inscripcion and a.id_etapa = $id_etapa
		and a.estado in ('IN','EP')
		)
                
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function getActividadModuloAtrevete($id_persona, $curso) {
        $sql = "select c.id as id_actividad_inscripcion, c.estado, c.id_inscripcion
            from inscripcion_edicion a
            inner join persona b on b.id = a.id_persona
            inner join actividad_edicion c on c.id_inscripcion = a.id and c.codigo_referencia = '$curso'
            where b.id = '$id_persona'";
        return $this->con->getArraySQL($sql);
    }
    
    function actualizarActividadModuloAtrevete($id_actividad, $estado) {
        $sql = "UPDATE `actividad_edicion` set estado = '$estado', fecha_fin = now() WHERE id = '$id_actividad'";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function getInscripcionEtapaXIdActividad($id_actividad_inscripcion) {
        $sql = "
         select  
                b.nombre as etapa, b.*,
		b.estado as estado_etapa,
                a.estado as estado_inscripcion_etapa,
		a.id as id_inscripcion_etapa,
                a.*,
                c.id_persona
          from inscripcion_etapa a
         inner join etapa b on b.id = a.id_etapa
         inner join inscripcion_edicion c on c.id = a.id_inscripcion
         inner join actividad_edicion d on d.id_inscripcion_etapa = a.id
         where d.id = $id_actividad_inscripcion";
        return $this->con->getArraySQL($sql);
    }
    
    function getAsistenteTecnicoAsignado($id_inscripcion){
        $sql = "select a.id_asistente_tecnico, c.nombre, c.apellido
                from actividad_edicion a
               inner join asistencia_tecnica b on b.id = a.id_asistente_tecnico and b.fecha_inicio <= now() and b.fecha_fin >= now()
               inner join persona c on c.id = b.id_persona
               where id_asistente_tecnico is not null
                 and id_inscripcion = '$id_inscripcion'
                 order by a.fecha_registro desc";
        return $this->con->getEntidad($sql);
    }
    
    function getActividadInscripcionxIdAgenda($id_agenda) {
        $sql = "select a.*, a.id as id_actividad_inscripcion,
                c.id_sub_programa
                from actividad_edicion a 
                inner join inscripcion_edicion b on b.id = a.id_inscripcion
                inner join edicion c on c.id = b.id_edicion
                where id_agenda = '$id_agenda'";
        return $this->con->getEntidad($sql);
    }
    
    function getActividadesAsignadasxReunion($id_reunion) {
        $sql = "select a.id as id_actividad_inscripcion, 
		ifnull(a.nombre, b.nombre) as actividad, 
		ifnull(c.nombre,'Pendiente') as estadoN,
                ifnull(c.estado, 'IN') as estado
            from actividad_edicion a
            inner join actividad_etapa b on b.id = a.id_actividad_etapa
            left outer join estado_actividad c on c.codigo = a.estado
            where a.id_reunion_asig = '$id_reunion'";
        return $this->con->getArraySQL($sql);
    }
    
    function getMentoriasAsignadasxReunion($id_reunion) {
        $sql = "
                select c.id_eje_mentoria, d.nombre as tematica, count(1) as sesiones
              from actividad_edicion c 
             inner join actividad_etapa b on b.id = c.id_actividad_etapa
             inner join eje_mentoria d on d.id = c.id_eje_mentoria
             where b.id_tipo_actividad = 15
               and c.id_reunion_asig = '$id_reunion'
             group by c.id_eje_mentoria, d.nombre
                 ";
        return $this->con->getArraySQL($sql);
    }
    
    function getMentoriasPendientesEtapa($id_inscripcion, $id_inscripcion_etapa) {
        $sql = "
                select  a.id as id_actividad_inscripcion,
		b.nombre as tema_mentoria,
                concat(e.nombre, ' ', e.apellido) as nombre_mentor,
                d.descripcion_perfil,
                d.id_persona,
                a.*
           from actividad_edicion a 
          inner join eje_mentoria b on b.id = a.id_eje_mentoria
          inner join actividad_etapa c on c.id = a.id_actividad_etapa
          inner join mentor d on d.id = a.id_mentor
          inner join persona e on e.id = d.id_persona
          where a.id_inscripcion = $id_inscripcion
            and a.id_inscripcion_etapa = $id_inscripcion_etapa
            and a.id_eje_mentoria is not null
            and (a.estado not in ('AP', 'CA') || a.estado is null)
            and c.id_tipo_actividad = 15
            order by a.id_eje_mentoria

                 ";
        return $this->con->getArraySQL($sql);
    }
    
    function actualizarMentor($id_mentor, $id_actividad_inscripcion) {
        $campos = array("id_mentor");
        $valores = array();
        $tipodatos = array();
        $valores[] = $id_mentor;
        $tipodatos[] = "s";
        $campos_condicion = array("id");
        $campos_condicion_valor = array($id_actividad_inscripcion);
        $tipodatos_condicion = array("i");
        $this->con->Actualizar("actividad_edicion", $campos, $tipodatos, $valores, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion);
    }
    
    /*Mantenimientos*/
    function getEtapa($id_etapa) {
        $sql = "Select * from etapa where id = $id_etapa";
        return $this->con->getEntidad($sql);
    }
    
    function insertarActividadEtapa($actividad) {
        $campos = array("nombre", "estado", "id_etapa", "id_tipo_actividad", "orden",
            "antecesor", "hora_max", "hora_min", "cod_referencia", "predecesor", "icono", "logo",
            "banner", "actividad_igual", "url", "aprueba_etapa", "boton_finalizar", "boton_guardar",
            "id_tipo_ejecucion", "id_rubrica", "archivo", "nemonico_file", "componente", "cod_aplicacion_externa",
            "cod_trama", "id_actividad_padre", "metodo_api", "actividad_paralelo", "mensaje_estado_ina");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $actividad->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("actividad_etapa", $campos, $tipodatos, $valores);
    }

    function actualizarActividadEtapa($actividad) {
        $campos = array("nombre", "estado", "id_etapa", "id_tipo_actividad", "orden",
            "antecesor", "hora_max", "hora_min", "cod_referencia", "predecesor", "icono", "logo",
            "banner", "actividad_igual", "url", "aprueba_etapa", "boton_finalizar", "boton_guardar",
            "id_tipo_ejecucion", "id_rubrica", "archivo", "nemonico_file", "componente", "cod_aplicacion_externa",
            "cod_trama", "id_actividad_padre", "metodo_api", "actividad_paralelo", "mensaje_estado_ina");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $actividad->{$campo};
            $tipodatos[] = "s";
        }
        $campos_condicion = array("id");
        $campos_condicion_valor = array($actividad->id);
        $tipodatos_condicion = array("i");
        $this->con->Actualizar("actividad_etapa", $campos, $tipodatos, $valores, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion);
    }
    
    function getEtapaxSubPrograma($id_sub_programa) {
        $sql = "select 	c.nombre as sub_programa,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.logo) as url_logo,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.banner) as url_banner,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.icono) as url_icono,
                concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), b.plan_trabajo) as url_plan_trabajo,
                b.*
        from etapa b
        inner join sub_programa c on c.id = b.id_sub_programa
        where b.id_sub_programa = $id_sub_programa";
        return $this->con->getArraySQL($sql);
    }
    
    function insertarEtapa($etapa) {
        $campos = array("nombre", "id_sub_programa", "estado", "inicio", "fin",
            "orden", "antecesor", "predecesor", "icono", "logo", "banner", "img1", "plan_trabajo");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $etapa->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("etapa", $campos, $tipodatos, $valores);
    }

    function actualizarEtapa($etapa) {
        $campos = array("nombre", "id_sub_programa", "estado", "inicio", "fin",
            "orden", "antecesor", "predecesor", "icono", "logo", "banner", "img1", "plan_trabajo");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $etapa->{$campo};
            $tipodatos[] = "s";
        }
        $campos_condicion = array("id");
        $campos_condicion_valor = array($etapa->id);
        $tipodatos_condicion = array("i");
        $this->con->Actualizar("etapa", $campos, $tipodatos, $valores, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion);
    }
    
    function getMensajePersonalizadoXIdActividadEtapa($id_actividad_etapa) {
        $sql = "select a.id, a.codigo, a.nombre, b.id_actividad_etapa, b.texto_mensaje, a.codigo as estado
            from estado_actividad a
            left outer join actividad_estado_mensaje b on b.estado = a.codigo and b.id_actividad_etapa = $id_actividad_etapa
            where a.estado = 'A' ";
        return $this->con->getArraySQL($sql);
    }
    
    function insertarMensajePersonalizadoActividadEtapa($etapa) {
        $campos = array("id_actividad_etapa", "texto_mensaje", "estado");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $etapa->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("actividad_estado_mensaje", $campos, $tipodatos, $valores);
    }
    
    function eliminarMensajePersonalizadoActividadEtapa($id_actividad_etapa) {
        $sql = "DELETE FROM `actividad_estado_mensaje` WHERE id_actividad_etapa = $id_actividad_etapa";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
}
