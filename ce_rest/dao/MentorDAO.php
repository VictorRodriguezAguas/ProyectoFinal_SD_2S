<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MentorDAO
 *
 * @author ernesto.ruales
 */
class MentorDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function getEjeMentoria($id_mentor) {
        $sql = "
            SELECT a.*, b.otro as valor, (case when b.id_mentor is null then false else true end) as selected
             FROM eje_mentoria a
            left outer join mentor_eje_mentoria b on b.id_eje_mentoria = a.id and b.id_mentor = '$id_mentor'";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function insertar($mentor) {
        $campos=array("descripcion_perfil","descripcion_motivacion_mentor", 
            "dias_disponibilidad", "max_emprendedores", "max_dias_semana", 
            "max_horas_dia", "max_horas_semana", "max_horas_mes",
            "opinion_red_mentores", "id_persona", "estado");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $mentor->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("mentor", $campos, $tipodatos, $valores);
    }
    
    function actualizar($mentor) {
        $campos=array("descripcion_perfil","descripcion_motivacion_mentor", 
            "dias_disponibilidad", "max_emprendedores", "max_dias_semana", 
            "max_horas_dia", "max_horas_semana", "max_horas_mes",
            "opinion_red_mentores", "id_persona", "estado");
        $tabla="mentor";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$mentor->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($mentor->id_mentor);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
    }
    
    function getMentorPorIdPersona($id) {
        $sql = "Select a.id as id_mentor, a.*
            from mentor a 
            where a.id_persona = $id";
        //print $sql;
        $respuesta = $this->con->getEntidad($sql);
        return $respuesta;
    }
    
    function eliminarEjeMentoria($id_mentor) {
        $sql = "DELETE FROM `mentor_eje_mentoria` WHERE id_mentor = $id_mentor";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function insertarEjeMentoria($id_mentor, $id_eje_mentoria, $otro) {
        $sql = "INSERT INTO `mentor_eje_mentoria`
                (`id_mentor`,`id_eje_mentoria`,`otro`)
                VALUES
                ($id_mentor,$id_eje_mentoria,$otro);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function getMentorPorEjeMentoria($idEjeMentoria, $estado='A') {
        $sql = "select a.nombre, a.apellido, a.id_usuario, b.id as id_mentor, a.id as id_persona,
                   b.descripcion_perfil,
                   concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),u.foto) as url_foto
            from persona a
            inner join mentor b on b.id_persona = a.id
            inner join mentor_eje_mentoria c on c.id_mentor = b.id
            left outer join ".basedatos::$baseSeguridad.".usuario u on u.id = a.id_usuario
            where c.id_eje_mentoria = $idEjeMentoria
              and b.estado = '$estado'
              and exists(select 1 from mentor_periodo mp 
                    where mp.id_mentor = b.id and mp.fecha_inicio <= now() 
                    and mp.fecha_fin >= now() )
                "
               ;
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function getMentores() {
        $sql = "select 
                    b.*, 
                    a.id as id_mentor,
                    a.*,
                    (case a.estado 
                        when 'A' then ifnull((select 'V' from mentor_periodo me where me.id_mentor = a.id and me.fecha_inicio <= now() and me.fecha_fin >= now() limit 1), a.estado)
                        else a.estado end
                    )as estado
              from mentor a
             inner join persona b on b.id = a.id_persona";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function getMentoresAllInfo() {
        $sql = "select a.*, a.id as id_persona, b.nombre as nivel_academico, c.nombre as genero, d.nombre as situacion_laboral, 
                        e.nombre as ciudad,
                    prov.id as id_provincia, prov.nombre as provincia,
                    m.*, m.id as id_mentor,
                    (case m.estado 
                        when 'A' then ifnull((select 'V' from mentor_periodo me where me.id_mentor = m.id and me.fecha_inicio <= now() and me.fecha_fin >= now() limit 1), m.estado)
                        else m.estado end
                    )as estado,
                    concat(ps.valor, a.cv) as url_cv
                  from persona a
               inner join mentor m on m.id_persona = a.id
               left outer join nivel_academico b on b.id = a.id_nivel_academico
               left outer join genero c on c.id = a.id_genero
               left outer join situacion_laboral d on d.id = a.id_situacion_laboral
               left outer join ubicacion e on e.id = a.id_ciudad
               left outer join parametro_sistema ps on ps.nombre = 'RUTA_ARCHIVOS_URL'
               left outer join ubicacion prov on prov.codigo = substr(e.codigo, 1, 2) ";
        return $this->con->getArraySQL($sql);
    }
    
    function getEjeMentoriaxIdMentor($id_mentor) {
        $sql = "
            SELECT a.*, b.otro as valor, (case when b.id_mentor is null then false else true end) as selected
             FROM eje_mentoria a
            inner join mentor_eje_mentoria b on b.id_eje_mentoria = a.id and b.id_mentor = '$id_mentor'";
        return $this->con->getArraySQL($sql);
    }
    
    function getEjeMentoriaxIdMentorText($id_mentor) {
        $sql = "
            SELECT concat(a.nombre, (case a.id when 7 then concat(': ',b.otro) else '' end))
             FROM eje_mentoria a
            inner join mentor_eje_mentoria b on b.id_eje_mentoria = a.id and b.id_mentor = '$id_mentor'";
        return $this->con->getArraySQL($sql);
    }
    
    function getMentorxIdPersona($id) {
        $sql = "select a.*, a.id as id_persona, b.nombre as nivel_academico, c.nombre as genero, d.nombre as situacion_laboral, 
                        e.nombre as ciudad,
                    prov.id as id_provincia, prov.nombre as provincia,
                    m.*, m.id as id_mentor,
                    (case m.estado 
                        when 'A' then ifnull((select 'V' from mentor_periodo me where me.id_mentor = m.id and me.fecha_inicio <= now() and me.fecha_fin >= now() limit 1), m.estado)
                        else m.estado end
                    )as estado
                  from persona a
               inner join mentor m on m.id_persona = a.id
               left outer join nivel_academico b on b.id = a.id_nivel_academico
               left outer join genero c on c.id = a.id_genero
               left outer join situacion_laboral d on d.id = a.id_situacion_laboral
               left outer join ubicacion e on e.id = a.id_ciudad
               left outer join ubicacion prov on prov.codigo = substr(e.codigo, 1, 2)
                where m.id_persona = '$id' ";
        return $this->con->getEntidad($sql);
    }
    
    function getMentor($id) {
        $sql = "select a.*, a.id as id_persona, b.nombre as nivel_academico, c.nombre as genero, d.nombre as situacion_laboral, 
                        e.nombre as ciudad,
                    prov.id as id_provincia, prov.nombre as provincia,
                    m.*, m.id as id_mentor,
                    (case m.estado 
                        when 'A' then ifnull((select 'V' from mentor_periodo me where me.id_mentor = m.id and me.fecha_inicio <= now() and me.fecha_fin >= now() limit 1), m.estado)
                        else m.estado end
                    )as estado
                  from persona a
               inner join mentor m on m.id_persona = a.id
               left outer join nivel_academico b on b.id = a.id_nivel_academico
               left outer join genero c on c.id = a.id_genero
               left outer join situacion_laboral d on d.id = a.id_situacion_laboral
               left outer join ubicacion e on e.id = a.id_ciudad
               left outer join ubicacion prov on prov.codigo = substr(e.codigo, 1, 2)
                where m.id = '$id' ";
        return $this->con->getEntidad($sql);
    }
    
    function getHorarioMentor($id_mentor, $dia=null) {
        $sql = "select * from mentor_horario where id_mentor = $id_mentor ";
        if(!is_null($dia)){
            $sql .= " and dia = '$dia'";
        }
        return $this->con->getArraySQL($sql);
    }
    
    function eliminarHorariosMentor($id_mentor) {
        $tabla="mentor_horario";
        $campos_condicion=array("id_mentor");
        $campos_condicion_valor=array($id_mentor);
        $tipodatos_condicion=array("i");
        $this->con->Eliminar($tabla,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
    }
    
    function insertarHorarioMentor($horario) {
        $tabla="mentor_horario";
	$campos=array("id_mentor","dia","hora_inicio","hora_fin");
	$tipodatos=array("i","s","s","s");
	$valores=array($horario->id_mentor,$horario->dia,$horario->hora_inicio,$horario->hora_fin);
	$this->con->Insertar($tabla,$campos,$tipodatos,$valores);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
    }
    
    function getPeriodos($id_mentor) {
        $sql = "select a.*, a.id as id_periodo from mentor_periodo a where id_mentor = $id_mentor ";
        return $this->con->getArraySQL($sql);
    }
    
    function insertarPeriodoMentor($periodo) {
        $tabla="mentor_periodo";
	$campos=array("id_mentor","id_edicion","fecha_inicio","fecha_fin", "max_horas_semana", "max_horas_mes", "contrato");
	$tipodatos=array("i","i","s","s", "i","i","s");
	$valores=array($periodo->id_mentor,$periodo->id_edicion,$periodo->fecha_inicio,$periodo->fecha_fin,
                $periodo->max_horas_semana,$periodo->max_horas_mes,$periodo->contrato);
	$id = $this->con->Insertar($tabla,$campos,$tipodatos,$valores);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
        return $id;
    }
    
    function actualizarPeriodoMentor($periodo) {
        $campos=array("fecha_inicio", "fecha_fin", "max_horas_semana", "max_horas_mes", "contrato");
        $tabla="mentor_periodo";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$periodo->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($periodo->id_periodo);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
    }
    
    function getPeriodoMentor($id_mentor, $fecha=null) {
        $sql = "select * 
                from mentor_periodo a
               where a.id_mentor = $id_mentor ";
        if(is_null($fecha)){
            $sql .= " and a.fecha_inicio <= now()
                 and a.fecha_fin >= now()";
        }else{
            $sql .= " and a.fecha_inicio <= '$fecha'
                 and a.fecha_fin >= '$fecha'";
        }
        //print $sql;
        return $this->con->getEntidad($sql);
    }
    
    function getMentoresXestado($estado) {
        $estadoAux = $estado;
        if($estado == 'V'){
            $estadoAux = 'A';
        }
        $sql = "select 
                    b.*, 
                    a.*,
                    a.id as id_mentor,
                    ifnull((select 'V' from mentor_periodo me where me.id_mentor = a.id and me.fecha_inicio <= now() and me.fecha_fin >= now() limit 1), a.estado) as estado
              from mentor a
             inner join persona b on b.id = a.id_persona
             where a.estado = (case '$estadoAux' when 'T' then a.estado else '$estadoAux' end) order by apellido, nombre"
                ;
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
}
