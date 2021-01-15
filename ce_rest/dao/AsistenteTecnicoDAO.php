<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AsistenteTecnico
 *
 * @author ernesto.ruales
 */

class AsistenteTecnicoDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function consultaAsistentesTecnicos() {
        $sql="
        select a.id as 'id_asistente_tecnico',a.fecha_registro as 'fecha_registro_asistente_tecnico',
        a.estado as 'estado_asistente_tecnico',a.id_edicion,(select e.nombre from edicion e where a.id_edicion=e.id) as 'edicion',a.fecha_inicio,a.fecha_fin,a.id_usuario_registro,a.id_usuario_modifica,
        a.fecha_modificacion as 'fecha_modificacion_asistente_tecnico',
        p.id as 'id_persona',p.nombre,p.apellido,p.fecha_nacimiento,p.id_genero,(select g.nombre from genero g where g.id=p.id_genero) as 'genero',
        p.id_ciudad,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad',p.email,p.telefono,p.id_situacion_laboral,
        (select sl.nombre from situacion_laboral sl where sl.id=p.id_situacion_laboral) as 'situacion_laboral',p.tipo_identificacion,
        p.identificacion,p.id_nivel_academico,(select nl.nombre from nivel_academico nl where nl.id=p.id_nivel_academico) as 'nivel_academico',
        p.id_usuario,p.direccion,p.id_ciudad_domicilio,(select c.nombre from ubicacion c where c.id=p.id_ciudad) as 'ciudad_domicilio',
        p.fecha_registro as 'fecha_registro_persona',p.fecha_modificacion as 'fecha_modificacion_persona',p.telefono_fijo,p.cv,p.estado as 'estado_persona',p.uso_datos,
        concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'),usu.foto) as url_foto
        from asistencia_tecnica a
        left join persona p
        on a.id_persona=p.id
        left join ".basedatos::$baseSeguridad.".usuario usu
        on p.id_usuario = usu.id
        where a.estado='A' and p.estado='A';";

        $asistentestecnicos=$this->con->Select($sql);
        return $asistentestecnicos;
    }
    
    function consultaAsistenteTecnicoHorario($id_asistencia_tecnica) {
        $sql="SELECT id_asistencia_tecnica,dia,hora_inicio,hora_fin
            FROM  asistencia_tecnica_horario
            WHERE id_asistencia_tecnica=?;";

        $valores=array($id_asistencia_tecnica);
        $tipodatos=array("i");
        $horario=$this->con->Select($sql,$valores,$tipodatos);
        return $horario;
    }
    
    function insertarAsistenteTecnico($asistenteTecnico) {
        $tabla = "asistencia_tecnica";
        $campos = array("id_persona", "fecha_registro", "estado", "id_edicion",
            "fecha_inicio", "fecha_fin", "id_usuario_registro");
        $tipodatos = array("i", "s", "s", "i", "s", "s", "i");
        $valores = array($asistenteTecnico->id_persona, date("Y-m-d H:i:s"), "A", $asistenteTecnico->id_edicion, $asistenteTecnico->fecha_inicio, $asistenteTecnico->fecha_fin, $asistenteTecnico->id_usuario_registro);
        return $this->con->Insertar($tabla, $campos, $tipodatos, $valores);
    }
    
    function consultaAsistenteTecnico($idPersona) {
        $sql="Select * from asistencia_tecnica where id_persona = $idPersona
                and estado = 'A'";
        return $this->con->getEntidad($sql);
    }
    
    function actualizarAsistenteTecnico($asistente) {
        $campos = array("estado");
        $tabla="asistencia_tecnica";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$asistente->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($asistente->id_asistencia_tecnica);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
    }
    
    function eliminarHorariosAsistenteTecnico($id_asistencia_tecnica) {
        $tabla="asistencia_tecnica_horario";
        $campos_condicion=array("id_asistencia_tecnica");
        $campos_condicion_valor=array($id_asistencia_tecnica);
        $tipodatos_condicion=array("i");
        $this->con->Eliminar($tabla,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
    }
    
    function insertarHorarioAsistenteTecnico($horario) {
        $tabla="asistencia_tecnica_horario";
	$campos=array("id_asistencia_tecnica","dia","hora_inicio","hora_fin");
	$tipodatos=array("i","s","s","s");
	$valores=array($horario->id_asistencia_tecnica,$horario->dia,$horario->hora_inicio,$horario->hora_fin);
	$this->con->Insertar($tabla,$campos,$tipodatos,$valores);
        if($this->con->getError() != 0){
            throw new Exception($this->con->getMensajeError(), "0");
        }
    }
    
    function consultaAsistenteTecnicoAgenda($id_persona) {
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
                where id_persona2 = ? 
                  and a.tipo = 'ASISTENCIA TECNICA'
                  and a.estado <> 'CA';";

        $valores = array($id_persona);
        $tipodatos = array("i");
        $agenda = $this->con->Select($sql, $valores, $tipodatos);
        return $agenda;
    }
    /*
    function consultaAsistenteTecnico() {
        
    }
    
    function consultaAsistenteTecnico() {
        
    }
    
    function consultaAsistenteTecnico() {
        
    }
    
    function consultaAsistenteTecnico() {
        
    }*/
}
