<?php

/**
 * Description of ReunionDAO
 *
 * @author Mauricio Guzman
 */

class ReunionDAO {

    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function loadConfigType($row){
        $respuesta = new stdClass();
        $respuesta->id = $row["id"];
        $respuesta->nombre = $row["nombre"];
        $respuesta->valor = $row["valor"];
        $respuesta->estado = $row["estado"];
        return $respuesta;
    }

    function getMeetingConfigs() {
        $sql = "select ps.* from parametro_sistema ps where ps.estado = 'A'";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $lista = array();
        $i=0;

        while($row = $resultado_consulta->fetch_array()){
            $lista[$i] = $this->loadConfigType($row);
            $i++;
        }
        return $lista;
    }

    function getMeetingById($agenda) {
        $sql = "select a.*, a.id as id_reunion,
                concat((SELECT valor FROM parametro_sistema WHERE nombre = 'RUTA_ARCHIVOS_URL'), a.imagen_inicio) as url_imagen_inicio,
                concat((SELECT valor FROM parametro_sistema WHERE nombre = 'RUTA_ARCHIVOS_URL'), a.imagen_fin) as url_imagen_fin
                from reunion a
                where id_agenda = '$agenda'";
        return $this->con->getEntidad($sql);
    }

    function getEnrollmentActivity($diary) {
        $sql = "select c.nombre, b.id as id_actividad_inscripcion, b.* 
                from actividad_edicion  a
                inner join actividad_edicion  b on b.id = a.predecesor
                inner join actividad_etapa c on c.id = b.id_actividad_etapa -- and c.id_tipo_actividad = 10
                where a.id_agenda = '$diary'";

        $resultado_consulta = $this->con->getConexion()->query($sql);
        $id_actividad_inscripcion = 0;
        if($row = $resultado_consulta -> fetch_array(MYSQLI_ASSOC)){
            $id_actividad_inscripcion = $row["id_actividad_inscripcion"];
        }

        return $id_actividad_inscripcion;
    }

    function insertarReunion($reunion) {
        
        $campos=array("id_agenda","hora_inicio_agenda", "hora_fin_agendad", "fecha_agendada", 
            "hora_inicio", "hora_fin", "temas", "acuerdos", "observacion", 
            "estado");
        
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $reunion->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("reunion", $campos, $tipodatos, $valores);
    }

    function actualizarReunion($reunion) {        
        $campos=array("hora_fin", "temas", "acuerdos", 
            "observacion", "archivo", "tipo_reunion", 
            "url_reunion", "estado", "id_registro_formulario",
            "imagen_inicio", "imagen_fin");
        $tabla="reunion";
        $valores = array($reunion->hora_fin, $reunion->temas, $reunion->acuerdos, 
            $reunion->observacion, $reunion->archivo, $reunion->tipo_reunion, 
            $reunion->url_reunion, $reunion->estado, $reunion->id_registro_formulario,
            $reunion->imagen_inicio, $reunion->imagen_fin);
        $tipodatos = array('s', 's', 's', 's', 's', 's', 's', 's', 's', 's', 's');
        $campos_condicion=array("id");
        $campos_condicion_valor=array($reunion->id_reunion);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() == '1'){
            throw new Exception($this->con->getMensajeError());
        }
    }

    function getReunion($id){
        $sql = "Select a.*, 
                    concat((select valor from parametro_sistema where nombre = 'RUTA_ARCHIVOS_URL'), a.archivo) as url_archivo
                from reunion a where a.id = '$id'";
        return $this->con->getEntidad($sql);
    }
    
    function getReunionxAgenda($id){
        $sql = "Select * from reunion where id_agenda = '$id'";
        return $this->con->getEntidad($sql);
    }
}
