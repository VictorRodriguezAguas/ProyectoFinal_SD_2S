<?php

/**
 * Description of ReunionBO
 *
 * @author Mauricio Guzman
 */
$newUrl = URL::getUrlLibreria();
require_once '../bo/BO.php';
require_once '../util/basedatos.php';
require_once '../dao/ReunionDAO.php';
require_once '../util/General.php';
require_once '../bo/ArchivosBO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../dao/EvaluacionDAO.php';
require_once '../dao/ProgramaDAO.php';

class ReunionBO extends BO {

    public function getMeetingConfigs() {
        $this->conectar();
        $reunionDAO = new ReunionDAO();
        $reunionDAO->setConexion($this->conexion);
        $data = $reunionDAO->getMeetingConfigs();
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getMeetingById() {
        $respuesta = General::validarParametros($_POST, ["id_agenda"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $id_rubrica = null;
        $id_evaluado = null;
        if (General::tieneValor($_POST, "id_rubrica")) {
            $id_rubrica = $_POST['id_rubrica'];
        }
        if (General::tieneValor($_POST, "id_evaluado")) {
            $id_evaluado = $_POST['id_evaluado'];
        }
        try {
            $this->conectar();
            $reunionDAO = new ReunionDAO();
            $programaDAO = new ProgramaDAO();
            $reunionDAO->setConexion($this->conexion);
            $programaDAO->setConexion($this->conexion);
            $data = $reunionDAO->getMeetingById($_POST["id_agenda"]);

            if (!is_null($data)) {
                $data->actividades_asignadas = $programaDAO->getActividadesAsignadasxReunion($data->id_reunion);
                $data->mentorias_asignadas = $programaDAO->getMentoriasAsignadasxReunion($data->id_reunion);
            }
        } 
        finally {
            $this->cerrarConexion();
        }

        return General::setRespuestaOK($data);
    }

    public function getEnrollmentActivity($reunion) {
        try {
            $this->conectar();
            $reunionDAO = new ReunionDAO();
            $reunionDAO->setConexion($this->conexion);
            $data = $reunionDAO->getEnrollmentActivity($reunion->id_agenda);
            $this->cerrarConexion();
            return $data;
        } finally {
            $this->cerrarConexion();
        }
    }

    public function insertarReunion() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $reunion = json_decode($_POST["datos"]);
        $reunion = $this->grabarReunion($reunion);
        $reunion->id_actividad_inscripcion = $this->getEnrollmentActivity($reunion);
        return General::setRespuesta("1", "Se grabó con éxito", $reunion);
    }

    public function grabarReunion($reunion) {

        $reunionDAO = new ReunionDAO();

        $this->conectar();
        try {
            $reunionDAO->setConexion($this->conexion);
            if (General::tieneValor($reunion, "id_reunion")) {
                $reunionAux = $reunionDAO->getReunion($reunion->id_reunion);
                $reunion->id_reunion = $reunionAux->id;
                $reunion->id_agenda = $reunionAux->id_agenda;
                $reunion->hora_inicio_agenda = $reunionAux->hora_inicio_agenda;
                $reunion->hora_fin_agendad = $reunionAux->hora_fin_agendad;
                $reunion->fecha_agendada = $reunionAux->fecha_agendada;
                $reunion->hora_inicio_reunion = $reunionAux->hora_inicio;
                $reunion->hora_fin_reunion = $reunionAux->hora_fin;
                $reunion->url_archivo_reunion = $reunionAux->url_archivo;
                $reunion->temas = $reunionAux->temas;
                $reunion->acuerdos = $reunionAux->acuerdos;
                $reunion->observacion = $reunionAux->observacion;
                $reunion->estado = $reunionAux->estado;
                $reunion->tipo_reunion = $reunionAux->tipo_reunion;
                $reunion->url_reunion = $reunionAux->url_reunion;
            } 
            else {
                $reunion->id_reunion = $reunionDAO->insertarReunion($reunion);
            }
            return $reunion;
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function actualizarReunion() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $reunionDAO = new ReunionDAO();
        $reunion = json_decode($_POST["datos"]);

        $this->conectar();
        try {
            $reunionDAO->setConexion($this->conexion);
            if (!General::tieneValor($reunion, "imagen_fin")) {
                $reunion->imagen_fin = null;
            }
            if (!General::tieneValor($reunion, "imagen_inicio")) {
                $reunion->imagen_inicio = null;
            }
            if (General::tieneValor($reunion, "id_reunion")) {
                if (isset($_FILES['imagen1'])) {
                    $file = $_FILES['imagen1'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "reunion_" . $reunion->id_reunion . "_imagen_inicio." . $ext;
                        $reunion->imagen1_error = ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $reunion->imagen_inicio = $nameImage;
                    }
                }
                if (isset($_FILES['imagen2'])) {
                    $file = $_FILES['imagen2'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "reunion_" . $reunion->id_reunion . "_imagen_fin." . $ext;
                        $reunion->imagen2_error = ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $reunion->imagen_fin = $nameImage;
                    }
                }
                $reunionDAO->actualizarReunion($reunion);
            }
            $reunion->id_actividad_inscripcion = $this->getEnrollmentActivity($reunion);
            return General::setRespuesta("1", "Se actualizo con éxito", $reunion);
        } 
        catch (Exception $ex) {
            return General::setRespuestaError($ex);
        } finally {
            $this->cerrarConexion();
        }
    }

}
