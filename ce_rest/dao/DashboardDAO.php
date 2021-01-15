<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboadDAO
 *
 * @author ernesto.ruales
 */
class DashboardDAO {

    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getInscripcionesxFases() {
        $sql = "select 	a.fase_asignada, 
		b.nombre as etapa, 
		count(a.id) as cantidad
                from inscripcion_edicion a
               inner join etapa b on b.id = a.fase_asignada
               inner join persona c on c.id = a.id_persona
               where a.fecha_registro >= '2020-07-02'
               group by a.fase_asignada , b.nombre";
        //return $this->con->getArraySQL($sql);
        return [];
    }

    function getEmprendedoresFase() {
        $sql = "select 	-1 as fase_asignada, 
		'Total registrados' as etapa, 
		count(a.id) as cantidad
	from inscripcion_edicion a
   inner join etapa b on b.id = a.fase
   inner join persona c on c.id = a.id_persona
   where a.fecha_registro >= '2020-07-02'
   
union all
   
    select 	a.fase as fase_asignada, 
                    b.nombre as etapa, 
                    count(a.id) as cantidad
            from inscripcion_edicion a
       inner join etapa b on b.id = a.fase
       inner join persona c on c.id = a.id_persona
       where a.fecha_registro >= '2020-07-02'
       group by a.fase , b.nombre
   
union all

    select  0 as fase_mer, 
                    'Mercado 593' as etapa, 
                    count(1) as cantidad
    from emprendimiento a
    inner join emprendedor_emprendimiento c on c.id_emprendimiento = a.id
    inner join emprendedor d on d.id = c.id_emprendedor
    inner join inscripcion_edicion b on b.id_persona = d.id_persona and b.fecha_registro >= '2020-07-02'
    where a.is_ce = 1 and a.is_mercado = 1";
        return $this->con->getArraySQL($sql);
    }

    function getInscripcionesFecha() {
        $sql = "select DATE_FORMAT(a.fecha_registro, '%Y-%m-%d') as fecha, b.id as id_etapa, b.orden, b.nombre, count(a.id) as cantidad
            from inscripcion_edicion a
            inner join persona c on c.id = a.id_persona
            inner join etapa b on b.id = a.fase_asignada
            where a.fecha_registro >= '2020-07-01'
            group by fecha, b.orden
            order by fecha asc";
        return $this->con->getArraySQL($sql);
    }

    function getInscripcionesMes() {
        $sql = "select MONTHNAME(a.fecha_registro) as fecha, b.id as id_etapa, b.orden, b.nombre, count(a.id) as cantidad
            from inscripcion_edicion a
            inner join persona c on c.id = a.id_persona
            inner join etapa b on b.id = a.fase_asignada
            where a.fecha_registro >= '2020-07-01'
            group by fecha, b.orden
            order by fecha asc";
        return $this->con->getArraySQL($sql);
    }

    function getActividadesPorEtapa($id_etapa) {
        $sql = "select 	a.id, a.nombre, a.orden, COUNT(b.id) as inscritos, a.id_etapa,
		count(c.id) as tomado, 
		(COUNT(b.id) - count(c.id)) as no_tomado,
		sum(case c.estado when 'AP' then 1 else 0 end) as finalizado,
                sum(case when c.estado is null then 0 when c.estado = 'AP' then 0 else 1 end) as noFinalizado
          from actividad_etapa a
         inner join inscripcion_edicion b on b.fase = a.id_etapa
         left outer join actividad_edicion c on c.id_actividad_etapa = a.id and c.id_inscripcion = b.id
         where a.id_etapa = $id_etapa
         group by a.id, a.nombre, a.orden
         order by a.id_actividad_padre, a.orden";
        return $this->con->getArraySQL($sql);
    }

    function getDataPivot() {
        $sql = "select y.*,
	(case when edad < 25 then '0 a 25 años'
		  when edad > 25 and edad <= 35 then '26 - 35 años'
          when edad > 35 and edad <= 45 then '36 - 45 años'
          when edad > 45 then '46 años o más' end) as rango_edad 
 from (
select concat(b.apellido, ' ', b.nombre) as emprendedor, b.email, 
		b.fecha_nacimiento, 
                a.fecha_registro,
		YEAR(CURDATE())-YEAR(b.fecha_nacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(b.fecha_nacimiento,'%m-%d'), 0 , -1 ) as edad,
                c.nombre as genero,
                d.nombre as ciudad,
                e.nombre as situacion_laboral,
                f.nombre as nivel_academico,
                h.nombre as fase_inicial,
                g.nombre as fase_actual,
                1 as cantidad,
                prov.id as id_provincia, 
                prov.nombre as provincia,
                (case when emd.id is null then 'NO' else 'SI' end ) as tiene_emprendedimiento,
                te.nombre as tipo_emprendimiento,
                et.nombre as etapa_emprendimiento                
          from inscripcion_edicion a
         inner join persona b on b.id = a.id_persona
         inner join genero c on c.id = b.id_genero
         inner join ubicacion d on d.id = b.id_ciudad
         left outer join ubicacion prov on prov.codigo = substr(d.codigo, 1, 2)
         inner join situacion_laboral e on e.id = b.id_situacion_laboral
         inner join nivel_academico f on f.id = b.id_nivel_academico
         inner join etapa g on g.id = a.fase
         inner join etapa h on h.id = a.fase_asignada
         left outer join emprendedor emp on emp.id_persona = b.id
         left outer join emprendedor_emprendimiento ee on ee.id_emprendedor = emp.id
         left outer join emprendimiento emd on emd.id = ee.id_emprendimiento
         left outer join tipo_emprendimiento te on te.id = emd.id_tipo_emprendimiento
         left outer join etapa_emprendimiento et on et.id = emd.id_etapa_emprendimiento
         where a.fecha_registro >= '2020-07-02' ) y";
        return $this->con->getArraySQL($sql);
    }

    
    /* Emprendedor */

    function getEtapaEmprendedor($id_persona, $id_sub_programa = 1) {
        $sql = "select 
		a.fase as etapa,
		(Select count(1) from etapa where etapa.id_sub_programa = b.id_sub_programa) as etapas,
                a.id as id_inscripcion,
                c.nombre as etapaText
          from inscripcion_edicion a 
         inner join edicion b on b.id = a.id_edicion
         inner join etapa c on c.id = a.fase
           where a.id_persona = $id_persona and b.id_sub_programa = $id_sub_programa";
        return $this->con->getEntidad($sql);
    }
    
    function getAvancePrograma($id_inscripcion, $id_etapa = 1) {
        $sql = " select sum(total) as total,
		sum(completadas) as completadas,
                sum(asignadas) as asignadas,
                sum(en_proceso) as en_proceso,
                sum(avance) as avance,
		round((sum(avance)/sum(total) * 100), 2) as porAvance
                  from (
                        select 
                                                       count(1) as total,
                                                       sum(case b.estado when 'AP' then 1 else 0 end) as completadas,
                                                       sum(case b.estado when 'IN' then 1 when 'PE' then 1  else 0 end) as asignadas,
                                                       sum(case when b.estado is null then 1 else 0 end) as noAsignadas,
                                                       sum(case b.estado when 'EP' then 1 else 0 end) as en_proceso,
                                                       sum(case b.estado when 'AP' then 1 when 'EP' then 0.5 else 0 end) as avance
                         from actividad_etapa a
                         left outer join actividad_edicion b on b.id_actividad_etapa = a.id 
                          and b.id_inscripcion = $id_inscripcion
                          and b.is_agregada_manual = 'NO'
                         where a.id_etapa = $id_etapa
                               and a.estado = 'A'

                       union all

                       select 
                                                       count(1) as total,
                                                       sum(case b.estado when 'AP' then 1 else 0 end) as completadas,
                                                       sum(case when b.estado is null  || b.estado = 'IN' || b.estado = 'PE' then 1 else 0 end) as asignadas,
                                                       0 as noAsignadas,
                                                       sum(case b.estado when 'EP' then 1 else 0 end) as en_proceso,
                                                       sum(case b.estado when 'AP' then 1 when 'EP' then 0.5 else 0 end) as avance
                         from inscripcion_etapa ie
                         inner join actividad_edicion b 
                            on b.id_inscripcion_etapa = ie.id 
                               and b.is_agregada_manual = 'SI'
                         where ie.id_inscripcion = $id_inscripcion
                           and ie.id_etapa = $id_etapa
                           and ie.estado <> 'CF' ) y
  ";
        return $this->con->getEntidad($sql);
    }

    
    /*Asistencia tecnica*/
    function getResumenAgendaDia($id_persona, $tipo="ASISTENCIA TECNICA") {
        $sql = "select count(*) as total,
		sum(case b.estado when 'AP' then 1 else 0 end) as atendidos,
                sum(case when b.estado is null then 1 else 0 end) as pendientes,
                sum(case b.estado when 'EP' then 1 else 0 end) as enproceso
          from agenda a 
         left outer join reunion b on b.id_agenda = a.id
         where a.id_persona2 = $id_persona
           and a.fecha = DATE_FORMAT(now(), '%Y-%m-%d')
           and a.tipo = '$tipo'
           group by a.fecha";
        //print $sql;
        return $this->con->getEntidad($sql);
    }
    
    function getIndicadoresSitucionLaboral() {
        $sql = "select c.id_situacion_laboral, d.nombre as situacion_laboral, count(1) as total
                from inscripcion_edicion a
               inner join etapa b on b.id = a.fase_asignada
               inner join persona c on c.id = a.id_persona
               inner join situacion_laboral d on d.id = c.id_situacion_laboral
               where a.fecha_registro >= '2020-07-02'
               group by c.id_situacion_laboral, d.nombre
               ;";
        return $this->con->getArraySQL($sql);
    }
}
