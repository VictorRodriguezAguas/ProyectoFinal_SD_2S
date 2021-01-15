<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProgramaBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../bo/MailBO.php';
require_once '../dao/EmprendimientoDAO.php';
require_once '../dao/ProgramaDAO.php';
require_once '../dao/PersonaDAO.php';
require_once '../dao/AgendaDAO.php';
require_once '../dao/TicketDAO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../util/General.php';
require_once '../bo/ArchivosBO.php';
require_once '../bo/AplicacionExternaBO.php';

class ProgramaBO extends BO {

    private $seActualizoSubActividades = false;

    //put your code here
    public function getProgramasPersona() {
        $respuesta = General::validarParametros($_POST, ["id_persona"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $respuesta = new stdClass();
        try {
            $id_persona = isset($_POST["id_persona"]) ? $_POST["id_persona"] : null;

            $programaDAO = new ProgramaDAO();

            $this->conectar();
            $programaDAO->setConexion($this->conexion);

            $lista = $programaDAO->getProgramasInscritosxIdPersona($id_persona);

            $respuesta = array();
            $c = count($lista);
            for ($i = 0; $i < $c; $i++) {
                try {
                    $data = $this->getProgramaxIdInscripcion($lista[$i]->id_inscripcion, null, null, null, null);
                    $respuesta[$i] = $data;
                } catch (Exception $ex) {
                    $respuesta[$i] = new stdClass();
                    $respuesta[$i]->sub_programa = $lista[$i];
                }
            }

            return General::setRespuesta("1", "Consulta éxitosa", $respuesta);
            //return General::setRespuesta("1", "Consulta éxitosa", null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getActividadesPrograma() {
        $respuesta = General::validarParametros($_POST, ["id_sub_programa", "id_persona"]);
        if (!is_null($respuesta)) {
            $respuesta = General::validarParametros($_POST, ["id_inscripcion"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
        }
        $id_inscripcion = isset($_POST["id_inscripcion"]) ? $_POST["id_inscripcion"] : null;
        $id_emprendedor = isset($_POST["id_emprendedor"]) ? $_POST["id_emprendedor"] : null;
        $id_persona = isset($_POST["id_persona"]) ? $_POST["id_persona"] : null;
        $id_emprendimiento = isset($_POST["id_emprendimiento"]) ? $_POST["id_emprendimiento"] : null;
        $id_sub_programa = $_POST["id_sub_programa"];

        try {
            $this->conectar();
            $respuesta = $this->getProgramaxIdInscripcion($id_inscripcion, $id_sub_programa, $id_persona, $id_emprendedor, $id_emprendimiento);
            return General::setRespuesta("1", "Consulta éxitosa", $respuesta);
            //return General::setRespuesta("1", "Consulta éxitosa", null);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getProgramaxIdInscripcion($id_inscripcion, $id_sub_programa, $id_persona, $id_emprendedor, $id_emprendimiento) {
        return $this->getProgramaxInscripcion($id_inscripcion, $id_sub_programa, $id_persona, $id_emprendedor, $id_emprendimiento, null, true);
    }

    public function getProgramaxInscripcion($id_inscripcion, $id_sub_programa, $id_persona, $id_emprendedor, $id_emprendimiento, $fase, $procesaActividad) {

        $band = false;
        $programaDAO = new ProgramaDAO();
        if (is_null($this->conexion)) {
            $band = true;
            $this->conectar();
        }

        $programaDAO->setConexion($this->conexion);

        try {
            $data = new stdClass();
            if (!is_null($id_inscripcion))
                $data->sub_programa = $programaDAO->getSubProgramaxIdInscripcion($id_inscripcion);
            else
                $data->sub_programa = $programaDAO->getSubProgramaxIds($id_persona, $id_emprendimiento, $id_emprendedor, $id_sub_programa);

            if (is_null($data->sub_programa)) {
                throw new Exception("No se encuentra configurado el programa");
            }

            if (is_null($fase)) {
                $fase = $data->sub_programa->fase;
            }

            $inscripcionEtapa = $programaDAO->getInscripcionEtapa($data->sub_programa->id_inscripcion, $fase, NULL);
            if (is_null($inscripcionEtapa) && $procesaActividad) {
                $inscripcionEtapa = $this->crearNewEtapaInscripcion($programaDAO, $data->sub_programa->id_inscripcion, $fase, null, 'NO');
            }

            $data->emprendimiento = new stdClass();
            if (!is_null($data->sub_programa->id_emprendimiento)) {
                $emprendimientoDAO = new EmprendimientoDAO();
                $emprendimientoDAO->setConexion($this->conexion);
                $data->emprendimiento = $emprendimientoDAO->getEmprendimientoPorId($data->sub_programa->id_emprendimiento);
            }

            $estados = array('AP', 'CF', 'AN');
            if (!is_null($inscripcionEtapa) && in_array($inscripcionEtapa->estado, $estados)) {
                $data->actividades = $programaDAO->getActividadesEtapaAprobada($data->sub_programa->id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa);
            } else {
                if ($procesaActividad) {
                    $resp = $this->getActividadesEtapa($programaDAO, $inscripcionEtapa, $data->sub_programa->id_inscripcion, $data->sub_programa->fase);
                    $data->actividades = $resp->actividades;
                    $data->ejecucion = $resp->ejecucion;
                } else {
                    if (!is_null($inscripcionEtapa) && $inscripcionEtapa->estado == 'PF') {
                        $data->actividades = $programaDAO->getActividadxEtapaInscripcion($data->sub_programa->id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa);
                    } else {
                        $data->actividades = $programaDAO->getActividadxEtapaxInscripcion($data->sub_programa->id_inscripcion, $fase);
                    }
                }
            }

            $data->etapas = $programaDAO->getEtapaxIdSubPrograma($data->sub_programa->id_sub_programa);

            if (count($data->etapas) == 0) {
                throw new Exception("No tiene configurado ninguna fase en este programa");
            }

            $data->pasoEtapa = 'NO';
            $faseAnt = $data->sub_programa->fase;
            if (count($data->actividades) > 0) {
                $fase = $data->actividades[0]->id_etapa;
                if ($fase != $faseAnt) {
                    $data->sub_programa = $programaDAO->getSubProgramaxIdInscripcion($data->sub_programa->id_inscripcion);
                    //$data->pasoEtapa='SI';
                }
            }
            $inscripcionEtapa = $programaDAO->getInscripcionEtapa($data->sub_programa->id_inscripcion, $fase, NULL);
            $data->inscripcionEtapa = $inscripcionEtapa;
            if (is_null($inscripcionEtapa)) {
                $data->pasoEtapa = 'NO';
            } else {
                $data->pasoEtapa = $inscripcionEtapa->ver_mensaje;
            }
            $etapaAnt;
            foreach ($data->etapas as &$etapa) {
                if ($etapa->id_etapa == $fase) {
                    $data->etapa = $etapa;
                }
                if ($etapa->id_etapa == $faseAnt) {
                    $etapaAnt = $etapa;
                }
            }

            if ($data->pasoEtapa == 'SI') {
                $this->generarTicket($data->sub_programa->id_persona, $etapaAnt, $data->etapa, "Aprobacion de etapa");
            }
        } finally {
            if ($band)
                $this->cerrarConexion();
        }

        return $data;
    }

    private function getActividadesEtapa($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase) {
        if (is_null($inscripcionEtapa)) {
            $inscripcionEtapa = $programaDAO->getInscripcionEtapa($id_inscripcion, $fase, NULL);
        }
        $data = $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, null);
        if ($this->seActualizoSubActividades) {
            $data = $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, null);
        }
        //$data = new stdClass();
        //$data->fase = 4;
        if ($inscripcionEtapa->estado == 'PF') {
            $actividades = $programaDAO->getActividadxEtapaInscripcion($id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa);
        } else {
            $actividades = $programaDAO->getActividadxEtapaxInscripcion($id_inscripcion, $data->fase);
        }
        if (count($actividades) == 0) {
            throw Exception("La etapa no tiene actividades configuradas");
        }
        $data->actividades = $actividades;
        return $data;
    }

    private function aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre = null, $id_actividad_inscripcion_padre = null) {
        if (is_null($inscripcionEtapa)) {
            $inscripcionEtapa = $programaDAO->getInscripcionEtapa($id_inscripcion, $fase, NULL);
        }
        $data = new stdClass();
        $dataAct = array();
        $data->fase = $fase;
        $data->ejecucion = $dataAct;
        if ($inscripcionEtapa->estado == 'AP' || $inscripcionEtapa->estado == 'CF') {
            return $data;
        }
        if ($inscripcionEtapa->estado == 'PF') {
            $actividades = $programaDAO->getActividadxEtapaInscripcion($id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa, $id_actividad_inscripcion_padre, false);
        } else {
            $actividades = $programaDAO->getActividadxEtapaxInscripcion($id_inscripcion, $fase, $id_actividad_padre, false);
        }

        if (count($actividades) == 0) {
            return;
        }
        $actividadAnterior = null;
        foreach ($actividades as &$actividad) {

            $actividadAinscripcion = $this->getActividad($id_inscripcion, $actividad, $actividadAnterior, $id_actividad_inscripcion_padre);

            if (is_null($actividad->id_actividad_inscripcion)) {
                if (is_null($actividadAnterior)) {
                    $actividadAinscripcion->estado = "IN";
                    $actividadAinscripcion->url = $actividad->url;
                    $actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
                    $this->_habilitarActividadesParalelo($programaDAO, $inscripcionEtapa, $id_inscripcion, $actividad, $fase, $id_actividad_padre, $id_actividad_inscripcion_padre);
                    if (!is_null($actividad->actividad_igual)) {
                        if ($this->aprobarActividadIgual($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre, $actividadAinscripcion, $actividad->actividad_igual)) {
                            $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre);
                        }
                    } else {
                        $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $actividadAinscripcion->id_actividad_etapa, $actividadAinscripcion->id_actividad_inscripcion);
                    }
                    break;
                }
                if ($actividadAnterior->estado == 'AP') {
                    $actividadAinscripcion->estado = "IN";
                    $actividadAinscripcion->antecesor = $actividadAnterior->id_actividad_inscripcion;
                    $actividadAinscripcion->url = $actividad->url;
                    $actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
                    $this->_habilitarActividadesParalelo($programaDAO, $inscripcionEtapa, $id_inscripcion, $actividad, $fase, $id_actividad_padre, $id_actividad_inscripcion_padre);
                    $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                    $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
                    $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $actividadAinscripcion->id_actividad_etapa, $actividadAinscripcion->id_actividad_inscripcion);
                    $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre, $id_actividad_inscripcion_padre);
                    break;
                }
            } else {
                // Cuando las actividades cambian de posicionamiento y ahora pertecen a un grupo
                if (!is_null($id_actividad_inscripcion_padre) && (
                        ($actividad->id_actividad_inscripcion_padre != $actividadAinscripcion->id_actividad_padre) ||
                        (is_null($actividad->id_actividad_inscripcion_padre) && !is_null($actividadAinscripcion->id_actividad_padre))
                        )) {
                    $programaDAO->actualizarActividadPadreActividadEdicion($actividadAinscripcion);
                    $this->seActualizoSubActividades = true;
                }

                //Actualizar antecesor y predecesor por cambio de orden de actividades
                $estados = array('IN', 'EP');
                if (!is_null($actividad->estado_actividad_inscripcion) && in_array($inscripcionEtapa->estado, $estados)) {
                    $actividadAinscripcion->antecesor = null;
                    if (!is_null($actividadAnterior)) {
                        $actividadAinscripcion->antecesor = $actividadAnterior->id_actividad_inscripcion;
                        $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                        $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
                    }
                    $programaDAO->actualizarActividadEtapaEdicion($actividadAinscripcion);
                }

                // actualizar estado de actividades agregadas manualmente
                if (is_null($actividad->estado_actividad_inscripcion) &&
                        (
                        (!is_null($actividadAnterior) && $actividadAnterior->estado == 'AP') ||
                        (is_null($actividadAnterior))
                        )
                ) {
                    $actividadAinscripcion->estado = "IN";
                    $programaDAO->actualizarActividadEtapaEdicion($actividadAinscripcion);
                    //Aprobar sub actividades manuales
                    $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $actividadAinscripcion->id_actividad_etapa, $actividadAinscripcion->id_actividad_inscripcion);
                } else {
                    //aprobar actividades hijo
                    if (!is_null($actividadAinscripcion->estado)) {
                        $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $actividad->id_actividad_etapa, $actividadAinscripcion->id_actividad_inscripcion);
                    }

                    if ($this->_habilitarActividadesParalelo($programaDAO, $inscripcionEtapa, $id_inscripcion, $actividad, $fase, $id_actividad_padre, $id_actividad_inscripcion_padre)) {
                        $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre, $id_actividad_inscripcion_padre);
                        break;
                    }

                    if ($actividad->aprueba_etapa == 'SI' && $actividad->estado_actividad_inscripcion == 'AP') {
                        $inscripcion = new stdClass();
                        $inscripcion->id_inscripcion = $actividad->id_inscripcion;
                        $inscripcion->fase = $actividad->predecesor;
                        $programaDAO->actualizarFaseInscripcion($inscripcion);

                        $inscripcionEtapa->estado = 'AP';
                        $inscripcionEtapa->fecha_aprobacion = General::getFechaActualH();
                        $programaDAO->actualizarInscripcionEtapa($inscripcionEtapa);

                        $fase = $inscripcion->fase;
                        $this->crearNewEtapaInscripcion($programaDAO, $id_inscripcion, $fase, $inscripcionEtapa->id_inscripcion_etapa);

                        $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre, $id_actividad_inscripcion_padre);
                        break;
                    }
                }
            }

            if (!is_null($actividad->actividad_igual) && $actividad->is_agregada_manual == 'NO') {
                $this->aprobarActividadIgual($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre, $actividadAinscripcion, $actividad->actividad_igual);
            }

            $pro = new stdClass();
            $pro->antividadAnt = $actividadAnterior;
            $pro->antividad = $actividad;
            $dataAct[count($dataAct)] = $pro;
            $actividadAnterior = $actividadAinscripcion;
        }
        $data->fase = $fase;
        $data->ejecucion = $dataAct;
        return $data;
    }

    private function crearNewEtapaInscripcion($programaDAO, $id_inscripcion, $fase, $id_etapa_anterior, $verMensaje = 'SI', $estado = 'IN') {
        $inscripcionEtapa = new stdClass();
        $etapa = $programaDAO->getEtapa($fase);
        $inscripcionEtapa->id_inscripcion = $id_inscripcion;
        $inscripcionEtapa->id_etapa = $fase;
        $inscripcionEtapa->ver_mensaje = $verMensaje;
        $inscripcionEtapa->estado = $estado;
        $inscripcionEtapa->id_etapa_anterior = $id_etapa_anterior;
        $inscripcionEtapa->version = $etapa->version;
        $inscripcionEtapa->id_inscripcion_etapa = $programaDAO->insertarInscripcionEtapa($inscripcionEtapa);
        return $inscripcionEtapa;
    }

    private function _aplicacionExterna($id_inscripcion, $actividad) {
        if (General::tieneValor($actividad, "cod_aplicacion_externa")) {
            $aplicacionExternaBO = new AplicacionExternaBO();
            $aplicacionExternaBO->setConexion($this->conexion);
            $personaDAO = new PersonaDAO();
            $personaDAO->setConexion($this->conexion);
            $persona = $personaDAO->getPersonaxIdInscripcion($id_inscripcion);
            $datos = new stdClass();
            $datos->id_persona = $persona->id_persona;
            $datos->curso = $actividad->cod_referencia;
            $datos->cod_trama = $actividad->cod_trama;
            return $aplicacionExternaBO->ejecutarAplicacionExterna($datos, $actividad->cod_aplicacion_externa, $actividad->metodo_api);
        }
        return false;
    }

    private function _habilitarActividadesParalelo($programaDAO, $inscripcionEtapa, $id_inscripcion, $actividad, $fase, $id_actividad_padre, $id_actividad_inscripcion_padre) {
        $tieneSubActividades = false;
        if (General::tieneValor($actividad, "actividad_paralelo")) {
            foreach (explode(",", $actividad->actividad_paralelo) as &$value) {
                $actividadEtapa = $programaDAO->getActividadInscripcionxId(NULL, $value, $id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa);
                if (is_null($actividadEtapa->id_actividad_inscripcion)) { //id_actividad_inscripcion_padre
                    $actividadInscripcion = $this->getActividad($id_inscripcion, $actividadEtapa, null, $actividadEtapa->id_actividad_inscripcion_padre);
                    //if(!is_null($actividad->antecesor))
                    //    $actividadInscripcion->antecesor = $actividad->antecesor;
                    $actividadInscripcion->estado = 'IN';
                    $actividadInscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadInscripcion);
                    $tieneSubActividades = true;
                    /* Aprobar sub actividades */
                    $this->aprobarActividad($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $actividadInscripcion->id_actividad_etapa, $actividadInscripcion->id_actividad_inscripcion);
                }
            }
        }
        return $tieneSubActividades;
    }

    private function _registroTaller($id_inscripcion, $actividad) {
        if (General::tieneValor($actividad, "cod_referencia")) {
            $agendaDAO = new AgendaDAO();
            $personaDAO = new PersonaDAO();
            $this->conectar();
            $agendaDAO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);
            $evento = $agendaDAO->getEvento(null, $actividad->cod_referencia, 1, 'A');
            if (is_null($evento)) {
                return null;
            }
            $persona = $personaDAO->getPersonaxIdInscripcion($id_inscripcion);
            $agenda = new stdClass();
            $agenda->id_persona = $persona->id_persona;
            $agenda->id_persona2 = null;
            $agenda->tipo = $evento->tipo;
            $agenda->fecha = $evento->fecha;
            $agenda->hora_inicio = $evento->hora_inicio;
            $agenda->estado = 'AG';
            $agenda->tema = $evento->nombre;
            $agenda->id_evento = $evento->id;
            $agenda->hora_fin = $evento->hora_fin;
            $agenda->id = $agendaDAO->insertarAgenda($agenda);
            $agenda->id_agenda = $agenda->id;
            return $agenda;
        }
        return null;
    }

    private function aprobarActividadIgual($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $id_actividad_padre, $actividadAinscripcion, $id_actividad_igual) {
        $actividadIgual = $programaDAO->getActividadInscripcionIdActividadEtapa($id_inscripcion, $id_actividad_igual);
        if (!is_null($actividadIgual)) {
            if ($actividadIgual->estado_actividad_inscripcion == 'AP') {
                $actividadAinscripcion->estado = "AP";
                $actividadAinscripcion->id_actividad_igual = $actividadIgual->id_actividad_inscripcion;
                $programaDAO->actualizarActividadEtapaEdicion($actividadAinscripcion);
                //Aprobar sub actividades
                $this->aprobarSubActividades($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $actividadAinscripcion);
                return true;
            }
        }
        return false;
    }

    public function actualizarActividadEtapa() {
        $actividadD = null;
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            $respuesta = General::validarParametros($_POST, ["id_actividad_inscripcion", "estado"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
        } else {
            $actividadD = json_decode($_POST["datos"]);
        }

        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            if (is_null($actividadD)) {
                $actividadD = $programaDAO->getActividadInscripcionxId($_POST['id_actividad_inscripcion']);
                if (is_null($actividadD)) {
                    return General::setRespuesta("0", "No existe la actividad", null);
                }
                $actividadD->estado_actividad_inscripcion = $_POST['estado'];
            }
            $data = $this->_actualizarActividadEtapa($actividadD);
            if (is_null($data)) {
                return General::setRespuesta("1", "No se pudo acualizar la etapa", null);
            }
            if (General::tieneValor($data, "codigo")) {
                return $data;
            }
            $data->sub_programa = $this->getProgramaxIdInscripcion($actividadD->id_inscripcion, null, null, null, null);
            return General::setRespuesta("1", "Actividad actualizada con éxito", $data);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), null);
        } finally {
            $this->cerrarConexion();
        }
    }

    private function _actualizarActividadEtapa($actividadD, $actividadPadre = null) {
        $programaDAO = new ProgramaDAO();
        $mailBO = new MailBO();
        $consultaDAO = new ConsultasDAO();
        $this->conectar();
        $programaDAO->setConexion($this->conexion);
        $mailBO->setConexion($this->conexion);
        $consultaDAO->setConexion($this->conexion);
        $data = new stdClass();
        if (is_null($actividadD)) {
            return General::setRespuesta("0", "Error", null);
        }
        $actividad = $this->getActividad($actividadD->id_inscripcion, $actividadD, null, $actividadD->id_actividad_inscripcion_padre);
        switch ($actividadD->id_tipo_actividad) {
            case '2':
                if ($actividadD->estado_actividad_inscripcion == 'IN') {
                    if ($this->_aplicacionExterna($actividadD->id_inscripcion, $actividadD)) {
                        $actividad->estado = "PE";
                    }
                }
                break;
            case '3':
                if (General::tieneValor($actividad, "archivo")) {
                    break;
                }
                if (isset($_FILES['archivo'])) {
                    $file = $_FILES['archivo'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = $actividadD->nemonico . "_" . $actividad->id_actividad_inscripcion . "_" . date("YmdHis") . "." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $actividad->archivo = $nameImage;
                        $archivo = new stdClass();
                        $archivo->nemonico = $actividadD->nemonico;
                        $archivo->archivo = $nameImage;
                        $archivo->id_inscripcion_edicion = $actividadD->id_inscripcion;
                        $aux = $programaDAO->existeArchivoInscripcion($archivo);
                        if (is_null($aux)) {
                            $programaDAO->crearArchivoInscripcion($archivo);
                        } else {
                            $programaDAO->actualizarArchivoInscripcion($archivo);
                        }
                    } else {
                        return General::setRespuesta("0", "Debe adjuntar el archivo", null);
                    }
                } else {
                    return General::setRespuesta("0", "Debe adjuntar el archivo", null);
                }
                break;
            case '4':
                if (!is_null($actividad->id_agenda)) {

                    $datosTrama = new stdClass();
                    $datosTrama->id_agenda = $actividad->id_agenda;
                    $mailBO->enviarCorreoTrama("MXAT001", $datosTrama, null, null, "ASITENCIA TECNICA");

                    $mailBO->enviarCorreoTrama("MXAT002", $datosTrama, null, null, "ASITENCIA TECNICA");

                    $mailMesa = $consultaDAO->getParamSystem("MAIL_MESA_SERVICIO");
                    $mailBO->enviarCorreoTrama("MXAT003", $datosTrama, $mailMesa, null, "ASITENCIA TECNICA");

                    $agendaDAO = new AgendaDAO();
                    $agendaDAO->setConexion($this->conexion);
                    $data->agenda = $agendaDAO->getAgendaxId($actividad->id_agenda);
                }
                break;
            case '12':
                if ($actividad->estado == 'AP') {
                    if (is_null($actividad->id_agenda)) {
                        return General::setRespuesta("0", "No puede aprobar esta actividad porque no se encuentra registrado en el taller", null);
                    }
                    //if(!is_null($actividad->id_agenda)){
                    $agendaDAO = new AgendaDAO();
                    $agendaDAO->setConexion($this->conexion);
                    $agenda = $agendaDAO->getAgendaxId($actividad->id_agenda);
                    $agenda->estado = 'AS';
                    $agendaDAO->actualizarAgenda($agenda);
                    if ($this->conexion->getError() != 0) {
                        return General::setRespuesta("0", $this->conexion->getMensajeError(), null);
                    }
                    //}
                } else {
                    $actividad->estado = "EP";
                }
                break;

            case '15':
                if (!is_null($actividad->id_agenda)) {

                    $datosTrama = new stdClass();
                    $datosTrama->id_agenda = $actividad->id_agenda;
                    //Correo para mentor
                    //$mailBO->enviarCorreoTrama("MXMENT001", $datosTrama, null, null, "MENTORIA");
                    //correo para emprendedor
                    $mailBO->enviarCorreoTrama("MXMENT002", $datosTrama, null, null, "MENTORIA");

                    //correo para mesa de servido
                    $mailMesa = $consultaDAO->getParamSystem("MAIL_MESA_SERVICIO");
                    $mailBO->enviarCorreoTrama("MXMENT003", $datosTrama, $mailMesa, null, "MENTORIA");

                    $agendaDAO = new AgendaDAO();
                    $agendaDAO->setConexion($this->conexion);
                    $data->agenda = $agendaDAO->getAgendaxId($actividad->id_agenda);
                }
                break;

            default:
                break;
        }
        if (is_null($actividad->fecha_fin) && $actividad->estado == 'AP') {
            $actividad->fecha_fin = General::getFechaActualH();
        }
        $programaDAO->actualizarActividadEtapaEdicion($actividad);
        $inscripcionEtapa = $programaDAO->getInscripcionEtapa($actividadD->id_inscripcion, $actividadD->id_etapa, NULL);

        if ($actividadD->estado_actividad_inscripcion == 'AP') {
            // aprobar actividad padre
            if (!is_null($actividadD->id_actividad_padre) || !is_null($actividadD->id_actividad_inscripcion_padre)) {
                $this->_aprobarActividadPadre($programaDAO, $inscripcionEtapa, $actividadD, $actividadPadre);
            }

            $this->aprobarSubActividades($programaDAO, $inscripcionEtapa, $actividadD->id_inscripcion, $actividadD->id_etapa, $actividadD);
        }

        if ($actividadD->aprueba_etapa == 'SI' && $actividadD->estado_actividad_inscripcion == 'AP') {
            $inscripcion = new stdClass();
            $inscripcion->id_inscripcion = $actividadD->id_inscripcion;
            $inscripcion->fase = $actividadD->predecesor;
            $programaDAO->actualizarFaseInscripcion($inscripcion);
            $personaDAO = new PersonaDAO();
            $personaDAO->setConexion($this->conexion);
            $persona = $personaDAO->getPersonaxIdInscripcion($actividadD->id_inscripcion);
            $mailBO->enviarCorreoTrama("MXDES01", null, $persona->email, "Aprobacion de fase", "APROBAR ETAPA");
        }
        return $data;
    }

    private function _aprobarActividadPadre($programaDAO, $inscripcionEtapa, $actividadD, $actividadPadreAux = null) {
        // se valida si es personalizada para extraer la actividad padre
        if ($inscripcionEtapa->estado == 'PF') {
            $actividadPadre = $programaDAO->getActividadInscripcionxId($actividadD->id_actividad_inscripcion_padre);
        } else {
            $actividadPadre = $programaDAO->getActividadInscripcionxId(null, $actividadD->id_actividad_padre, $actividadD->id_inscripcion, $actividadD->id_inscripcion_etapa);
            // valida si se ha modificado el orden del flujo de la actividad
            if (!is_null($actividadPadreAux)) {
                if ($actividadPadre->id_actividad_inscripcion == $actividadPadreAux->id_actividad_inscripcion) {
                    return;
                }
            }
        }

        if (is_null($actividadPadre)) {
            return;
        }

        // se valida si es personalizada
        if ($inscripcionEtapa->estado == 'PF') {
            $actividades = $programaDAO->getActividadxEtapaInscripcion($actividadD->id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa, $actividadPadre->id_actividad_inscripcion, false);
        } else {
            $actividades = $programaDAO->getActividadxEtapaxInscripcion($actividadD->id_inscripcion, $actividadD->id_etapa, $actividadPadre->id_actividad_etapa, false);
        }

        $todosAprobados = true;
        foreach ($actividades as &$act) {
            if (is_null($act->estado_actividad_inscripcion) || $act->estado_actividad_inscripcion != 'AP') {
                $todosAprobados = false;
            }
        }
        if ($todosAprobados) {
            $actividadPadre->estado_actividad_inscripcion = 'AP';
            $actividadAct = $this->getActividad($actividadD->id_inscripcion, $actividadPadre, null, $actividadPadre->id_actividad_inscripcion);
            $programaDAO->actualizarActividadEtapaEdicion($actividadAct);
        }
    }

    private function aprobarSubActividades($programaDAO, $inscripcionEtapa, $id_inscripcion, $fase, $actividadPadre) {
        if ($inscripcionEtapa->estado == 'PF') {
            $actividades = $programaDAO->getActividadxEtapaInscripcion($id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa, $actividadPadre->id_actividad_inscripcion, false);
        } else {
            $actividades = $programaDAO->getActividadxEtapaxInscripcion($id_inscripcion, $fase, $actividadPadre->id_actividad_etapa, false);
        }

        $actividadAnterior = null;
        foreach ($actividades as &$actividad) {
            if ($actividad->estado_actividad_inscripcion != 'AP') {
                if (!is_null($actividadAnterior))
                    $actividad->antecesor_actividad_inscripcion = $actividadAnterior->id_actividad_inscripcion;
                $actividad->predecesor_actividad_inscripcion = 'AP';
                $actividad->estado_actividad_inscripcion = 'AP';
                $actividad->id_actividad_padre = $actividadPadre->id_actividad_inscripcion;
                $this->_actualizarActividadEtapa($actividad, $actividadPadre);
                $actividadAnterior = $actividad;
            }
        }
        //return $actividades;
    }

    public function actualizarInscripcion() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $inscripcion = json_decode($_POST["datos"]);
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);

            $programaDAO->actualizarFaseInscripcion($inscripcion);

            $data = $this->getProgramaxInscripcion($inscripcion->id_inscripcion, null, null, NULL, null, $inscripcion->fase, false);
            return General::setRespuesta("1", "Etapa aprobada con éxito", $data);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function etapasXidSubPrograma($id_subprograma) {
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();

            $programaDAO->setConexion($this->conexion);

            $data = $programaDAO->getEtapaxIdSubPrograma($id_subprograma);

            return $data;
        } finally {
            $this->cerrarConexion();
        }
    }

    public function actividadInduccion($datos, $id_sub_programa, $id_actividad_etapa) {
        try {
            $respuesta = array();
            $this->conectar();
            $personaDAO = new PersonaDAO();
            $programaDAO = new ProgramaDAO();
            $personaDAO->setConexion($this->conexion);
            $programaDAO->setConexion($this->conexion);

            foreach ($datos as &$item) {
                $persona = $personaDAO->getPersonaXEmail($item->email);
                $data = new stdClass();
                $data->email = $item->email;
                if (is_null($persona)) {
                    $data->mensaje = "Persona no existe";
                    $respuesta[] = $data;
                    continue;
                }
                $inscripcion = $programaDAO->getInscripcionXidPersonaxIdSubPrograma($persona->id_persona, $id_sub_programa);
                if (is_null($inscripcion)) {
                    $data->mensaje = "No esta inscrito al programa";
                    $respuesta[] = $data;
                    continue;
                }
                $actividad = $programaDAO->getActividadInscripcionIdActividadEtapa($inscripcion->id_inscripcion, $id_actividad_etapa);
                if (is_null($actividad)) {

                    $actividad = new stdClass();
                    $actividadEtapa = $programaDAO->getActividadEtapa($id_actividad_etapa);
                    $actividad->id_inscripcion = $inscripcion->id_inscripcion;
                    $actividad->id_actividad_etapa = $id_actividad_etapa;
                    $actividad->estado = 'AP';
                    $actividad->url = $actividadEtapa->url;
                    if ($inscripcion->fase == $actividadEtapa->id_etapa) {
                        $actividad->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividad);
                        $data->mensaje = "Insertado con éxito";
                        $data->data = $actividad;
                        $respuesta[] = $data;
                        continue;
                    }
                } else {
                    $actividad->estado = 'AP';
                    $programaDAO->actualizarActividadEtapaEdicion($actividad);
                    $data->mensaje = "Actualizado con éxito";
                    $data->data = $actividad;
                    $respuesta[] = $data;
                }
            }
            return General::setRespuesta("1", "Proceso ejecutado con éxito", $respuesta);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), NULL);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function cambiarFaseInscripcion() {
        $respuesta = General::validarParametros($_POST, ["id_inscripcion", "fase_anterior", "fase_nueva"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $data = $this->_cambiarFase($_POST["id_inscripcion"], $_POST["fase_anterior"], $_POST["fase_nueva"]);
            if (is_null($data)) {
                return General::setRespuesta("0", $this->conexion->getError(), null);
            }
            $mailBO = new MailBO();
            $personaDAO = new PersonaDAO();
            $mailBO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);
            $persona = $personaDAO->getPersonaxIdInscripcion($_POST["id_inscripcion"]);
            $datosTrama = new stdClass();
            $datosTrama->id_etapa = $_POST["fase_nueva"];
            $mailBO->enviarCorreoTrama("MXCF001", $datosTrama, $persona->email, null, "CAMBIO DE FASE");
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function aprobarFaseInscripcion() {
        $respuesta = General::validarParametros($_POST, ["id_inscripcion", "fase_anterior", "fase_nueva"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $data = $this->_cambiarFase($_POST["id_inscripcion"], $_POST["fase_anterior"], $_POST["fase_nueva"], 'AP');
            $mailBO = new MailBO();
            $personaDAO = new PersonaDAO();
            $mailBO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);
            $persona = $personaDAO->getPersonaxIdInscripcion($_POST["id_inscripcion"]);
            $mailBO->enviarCorreoTrama("MXDES01", null, $persona->email, "Aprobacion de fase", "APROBAR ETAPA");
            if (is_null($data)) {
                return General::setRespuesta("0", $this->conexion->getError(), null);
            }
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    private function _cambiarFase($id_inscripcion, $fase_anterior, $fase_nueva, $estado = 'CF') {
        $programaDAO = new ProgramaDAO();
        $this->conectar();
        $programaDAO->setConexion($this->conexion);
        $inscripcion = $programaDAO->getSubProgramaxIdInscripcion($id_inscripcion);
        $inscripcion->fase = $fase_nueva;
        $programaDAO->actualizarFaseInscripcion($inscripcion);

        $inscripcionEtapa = $programaDAO->getInscripcionEtapa($id_inscripcion, $fase_anterior, NULL);
        $inscripcionEtapa->estado = $estado;
        $etapaAnt = $programaDAO->getEtapa($fase_anterior);
        $etapa = $programaDAO->getEtapa($fase_nueva);
        if (!is_null($this->user)) {
            $inscripcionEtapa->id_usuario_aprobacion = $this->user->id;
        }
        if ($estado == 'CF') {
            $this->crearNewEtapaInscripcion($programaDAO, $id_inscripcion, $fase_nueva, $inscripcionEtapa->id_inscripcion_etapa, "NO");
            $this->generarTicket($inscripcion->id_persona, $etapaAnt, $etapa);
        } else {
            $inscripcionEtapa->fecha_aprobacion = General::getFechaActualH();
            $this->crearNewEtapaInscripcion($programaDAO, $id_inscripcion, $fase_nueva, $inscripcionEtapa->id_inscripcion_etapa);
            $this->generarTicket($inscripcion->id_persona, $etapaAnt, $etapa, "Aprobacion de etapa");
        }
        $programaDAO->actualizarInscripcionEtapa($inscripcionEtapa);

        if ($this->conexion->getError() != 0) {
            return null;
        }
        return $inscripcion;
    }

    private function generarTicket($id_persona, $etapaAnt, $etapa, $descripcion = "Cambio de fase") {
        $personaDAO = new PersonaDAO();
        $ticketDAO = new TicketDAO();
        $personaDAO->setConexion($this->conexion);
        $ticketDAO->setConexion($this->conexion);
        $persona = $personaDAO->getPersonaXId($id_persona);
        $ticket = new stdClass();
        $ticket->id_tipo = 1;
        $ticket->id_usuario_creacion = $persona->id_usuario;
        $ticket->fecha_creacion = General::getFechaActualH();
        $ticket->id_categoria = 9;
        $ticket->id_subcategoria = null;
        $ticket->id_tipo_atencion = 3;
        $ticket->descripcion = $descripcion;
        $ticket->id_persona = $persona->id_persona;
        $ticket->estado = 'A';
        $ticket->id_reunion = null;
        $ticket->id_usuario_atencion = 1;
        $ticket->fecha_finalizacion = General::getFechaActualH();
        $ticket->observacion = "Paso de la etapa " . $etapaAnt->nombre . " a la etapa " . $etapa->nombre;
        $ticket->id_ticket = $ticketDAO->insertarTicket($ticket);
        $ticketDAO->doneTicketByAttended($ticket);
    }

    public function actualizarActividades() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $actividades = json_decode($_POST["datos"]);
            $this->conectar();
            $errores = array();
            foreach ($actividades as &$actividad) {
                if ($actividad->selected) {
                    $actividad->estado_actividad_inscripcion = 'AP';
                    try {
                        $data = $this->_actualizarActividadEtapa($actividad);
                    } catch (Exception $e) {
                        $data = new stdClass();
                        $data->codigo = "0";
                        $data->mensaje = $e->getMessage();
                        $data->data = $actividad;
                        $errores[] = $data;
                    }
                    if (is_null($data)) {
                        $errores[] = General::setRespuesta("0", "No se pudo aprobar esta actividad", $actividad);
                        $actividad->mensaje_error = "No se pudo aprobar esta actividad";
                    } else {
                        if (General::tieneValor($data, "codigo")) {
                            $data->data = $actividad;
                            $errores[] = $data;
                            $actividad->mensaje_error = "No se pudo aprobar esta actividad";
                        }
                    }
                }
            }
            if (count($errores) > 0) {
                return General::setRespuesta("0", "No se actualizaron todos las actividades", $errores);
            }
            return General::setRespuestaOK(null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function revertirActividad() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        $actividadD = null;
        if (!is_null($respuesta)) {
            $respuesta = General::validarParametros($_POST, ["id_actividad_inscripcion", "estado"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
        } else {
            $actividadD = json_decode($_POST["datos"]);
        }
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            if (is_null($actividadD)) {
                $actividadD = $programaDAO->getActividadInscripcionxId($_POST['id_actividad_inscripcion']);
                if (is_null($actividadD)) {
                    return General::setRespuesta("0", "No existe la actividad", null);
                }
            }
            return $this->_revertirActividad($programaDAO, $actividadD);
        } catch (Exception $ex) {
            return General::setRespuesta("0", "Ha ocurrido un error en la actualizacion de la actividad", null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function _revertirActividad($programaDAO, $actividadD, $agenda = null) {
        $actividad = $this->getActividad($actividadD->id_inscripcion, $actividadD, null, $actividadD->id_actividad_inscripcion_padre);
        switch ($actividadD->id_tipo_actividad) {
            case '2':
                if ($actividadD->estado_actividad_inscripcion == 'AP') {
                    $actividad->estado = "PE";
                }
                break;
            case '3':
                if ($actividadD->estado_actividad_inscripcion == 'AP') {
                    $actividad->estado = "PE";
                    $actividad->archivo = null;
                }
                break;
            case '4':
                if ($actividadD->estado_actividad_inscripcion == 'AP') {
                    $agendaDAO = new AgendaDAO();
                    $agendaDAO->setConexion($this->conexion);
                    if (is_null($agenda)) {
                        $agenda = $agendaDAO->getAgendaxId($actividad->id_agenda);
                    }
                    if (!is_null($agenda->id_reunion)) {
                        return General::setRespuesta("0", "No puede revertir la agenda porque tiene una reunion ya establecida", null);
                    }
                    $agenda->estado = 'CA';
                    $agendaDAO->actualizarAgenda($agenda);
                    $actividad->id_agenda = null;
                    $actividad->estado = "PE";
                }
                break;
            case '12':
                //if ($actividadD->estado_actividad_inscripcion == 'AP') {
                if (!is_null($actividad->id_agenda)) {
                    $agendaDAO = new AgendaDAO();
                    $agendaDAO->setConexion($this->conexion);
                    if (is_null($agenda)) {
                        $agenda = $agendaDAO->getAgendaxId($actividad->id_agenda);
                    }
                    if ($agenda->estado == 'AS') {
                        return General::setRespuesta("0", "No puede reversar el estado de la actividad porque tiene registro de asistencia", null);
                    }
                    $agenda->estado = 'CA';
                    $agendaDAO->actualizarAgenda($agenda);
                    $actividad->id_agenda = null;
                }
                $actividad->estado = "IN";
                //}
                break;
            case '15':
                if ($actividadD->estado_actividad_inscripcion == 'AP') {
                    $agendaDAO = new AgendaDAO();
                    $agendaDAO->setConexion($this->conexion);
                    if (is_null($agenda)) {
                        $agenda = $agendaDAO->getAgendaxId($actividad->id_agenda);
                    }
                    if (!is_null($agenda->id_reunion)) {
                        return General::setRespuesta("0", "No puede revertir la agenda porque tiene una reunion ya establecida", null);
                    }
                    $agenda->estado = 'CA';
                    $agendaDAO->actualizarAgenda($agenda);
                    $actividad->id_agenda = null;
                    $actividad->estado = "PE";
                }
                break;
            default:
                if ($actividadD->estado_actividad_inscripcion == 'AP') {
                    $actividad->estado = "PE";
                }
                break;
        }
        $programaDAO->actualizarActividadEtapaEdicion($actividad);
        if (!is_null($actividadD->id_actividad_padre)) {
            $actividadPadre = $programaDAO->getActividadInscripcionxId(null, $actividadD->id_actividad_padre, $actividadD->id_inscripcion, $actividadD->id_inscripcion_etapa);
            return $this->_revertirActividad($programaDAO, $actividadPadre);
        }
        return General::setRespuestaOK(null);
    }

    public function asignarActividades() {
        $respuesta = General::validarParametros($_POST, ["datos", "id_inscripcion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $actividades = json_decode($_POST["datos"]);
        $id_inscripcion = $_POST["id_inscripcion"];
        $id_reunion = null;
        if (General::tieneValor($_POST, "id_reunion")) {
            $id_reunion = $_POST["id_reunion"];
        }
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            $inscripcion = $programaDAO->getSubProgramaxIdInscripcion($id_inscripcion);
            $inscripcionEtapa = $programaDAO->getInscripcionEtapa($id_inscripcion, $inscripcion->fase, NULL);
            $actividadesEjecutadas = $this->_asignarActividades($programaDAO, $inscripcion, $actividades, $inscripcionEtapa, NULL, $id_reunion);
            $inscripcionEtapa->estado = 'PF';
            $inscripcionEtapa->fecha_aprobacion = null;
            $programaDAO->actualizarInscripcionEtapa($inscripcionEtapa);
            return General::setRespuestaOK($actividadesEjecutadas);
        } catch (Exception $ex) {
            return General::setRespuesta("0", "Ha ocurrido un error en la actualizacion de la actividad", null);
        } finally {
            $this->cerrarConexion();
        }
    }

    private function _asignarActividades($programaDAO, $inscripcion, $actividades, $inscripcionEtapa, $actividadPadre, $id_reunion = null) {
        $id_actividad_padre = null;
        $actividadAnterior = null;
        $actividadesEjecutadas = array();
        foreach ($actividades as $act) {
            if (!is_null($actividadPadre)) {
                $id_actividad_padre = $actividadPadre->id_actividad_inscripcion;
            }
            if ($act->id_actividad_inscripcion > 0) {
                $actividadAinscripcion = $this->getActividad($inscripcion->id_inscripcion, $act, $actividadAnterior, $id_actividad_padre);
                $actividadAinscripcion->id_actividad_inscripcion_aux = $act->id_actividad_inscripcion;
                $actividadAinscripcion->orden = $act->orden;
                $programaDAO->actualizarActividadEtapaEdicion($actividadAinscripcion);
                if (count($act->child) > 0) {
                    $actividadAinscripcion->child = $this->_asignarActividades($programaDAO, $inscripcion, $act->child, $inscripcionEtapa, $actividadAinscripcion, $id_reunion);
                }
                $actividadAnterior = $actividadAinscripcion;
                $actividadesEjecutadas[] = $actividadAinscripcion;
                continue;
            }
            /* Cuando la actividad es "Nuevas actividades" */
            if ($act->id_actividad_inscripcion == -1) {
                if (count($act->child) > 0) {
                    $actividadRaiz = $programaDAO->getActividadEtapaTranversal($inscripcion->id_inscripcion, $inscripcion->fase, 'ET-001');
                    $actividadRaiz->id_etapa = $inscripcion->fase;
                    $actividadRaiz->id_inscripcion_etapa = $inscripcionEtapa->id_inscripcion_etapa;
                    $actividadAinscripcion = $this->getActividad($inscripcion->id_inscripcion, $actividadRaiz, $actividadAnterior, $id_actividad_padre);
                    $actividadAinscripcion->orden = $act->orden;
                    $actividadAinscripcion->is_agregada_manual = 'SI';
                    $actividadAinscripcion->is_obligatorio = 'SI';
                    $actividadAinscripcion->id_reunion_asig = $id_reunion;
                    $actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
                    $actividadAinscripcion->id_actividad_inscripcion_aux = $act->id_actividad_inscripcion;
                    $actividadAinscripcion->child = $this->_asignarActividades($programaDAO, $inscripcion, $act->child, $inscripcionEtapa, $actividadAinscripcion, $id_reunion);
                    $actividadAnterior = $actividadAinscripcion;
                }
                $actividadesEjecutadas[] = $actividadAinscripcion;
                continue;
            }

            $actividadEtapa = $programaDAO->getActividadEtapa($act->id_actividad_etapa);
            if (is_null($act->is_agregada_manual) || $act->is_agregada_manual == 'NO') {
                $actividadAinscripcion = $this->getActividad($inscripcion->id_inscripcion, $act, $actividadAnterior, $id_actividad_padre);
            } 
            else {
                $act->id_etapa = $inscripcion->fase;
                $act->id_actividad_etapa = $actividadEtapa->id;
                $act->id_rubrica = $actividadEtapa->id_rubrica;
                $act->cod_referencia = $actividadEtapa->cod_referencia;
                $act->url = $actividadEtapa->url;
                $act->id_inscripcion_etapa = $inscripcionEtapa->id_inscripcion_etapa;
                $actividadAinscripcion = $this->getActividadAdicional($inscripcion->id_inscripcion, $act, $actividadAnterior, $id_actividad_padre);
                $actividadAinscripcion->id_reunion_asig = $id_reunion;
            }
            $actividadAinscripcion->orden = $act->orden;
            $actividadAinscripcion->id_actividad_inscripcion_aux = $act->id_actividad_inscripcion;
            $actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
            if (!is_null($actividadAnterior)) {
                $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
            }
            if (count($act->child) > 0) {
                $actividadAinscripcion->child = $this->_asignarActividades($programaDAO, $inscripcion, $act->child, $inscripcionEtapa, $actividadAinscripcion, $id_reunion);
            }
            $actividadAnterior = $actividadAinscripcion;
            $actividadesEjecutadas[] = $actividadAinscripcion;
        }
        return $actividadesEjecutadas;
    }

    private function getActividadAdicional($id_inscripcion, $actividadD, $actividadAnterior, $id_actividad_padre) {
        $actividadAinscripcion = new stdClass();
        $actividadAinscripcion->id_actividad_inscripcion = null;
        $actividadAinscripcion->id_inscripcion = $id_inscripcion;
        $actividadAinscripcion->id_actividad_etapa = $actividadD->id;
        $actividadAinscripcion->estado = null;
        $actividadAinscripcion->id_agenda = null;
        $actividadAinscripcion->archivo = null;
        $actividadAinscripcion->id_rubrica = $actividadD->id_rubrica;
        $actividadAinscripcion->id_evaluacion = null;
        $actividadAinscripcion->antecesor = null;
        $actividadAinscripcion->codigo_referencia = $actividadD->cod_referencia;
        $actividadAinscripcion->fecha_inicio = General::getFechaActualH();
        $actividadAinscripcion->fecha_fin = null;
        $actividadAinscripcion->fecha_max_inicio = null;
        $actividadAinscripcion->tipo_duracion = null;
        $actividadAinscripcion->duracion = null;
        $actividadAinscripcion->orden = $actividadD->orden;
        if (!is_null($actividadAnterior)) {
            $actividadAinscripcion->antecesor = $actividadAnterior->id_actividad_inscripcion;
        }
        $actividadAinscripcion->is_obligatorio = 'SI';
        $actividadAinscripcion->predecesor = null;
        $actividadAinscripcion->fecha_aprobacion = null;
        $actividadAinscripcion->id_usuario_mod = null;
        $actividadAinscripcion->id_usuario_aprobacion = null;
        $actividadAinscripcion->tiempo_actividad = null;
        $actividadAinscripcion->id_mentor = null;
        $actividadAinscripcion->id_asistente_tecnico = null;
        $actividadAinscripcion->id_actividad_igual = null;
        $actividadAinscripcion->url = $actividadD->url;
        $actividadAinscripcion->id_actividad_padre = $id_actividad_padre;
        //$actividadAinscripcion->id_etapa = $inscripcion->fase;
        $actividadAinscripcion->id_etapa = $actividadD->id_etapa;
        $actividadAinscripcion->is_agregada_manual = 'SI';
        $actividadAinscripcion->id_inscripcion_etapa = $actividadD->id_inscripcion_etapa;
        $actividadAinscripcion->id_eje_mentoria = null;
        $actividadAinscripcion->nombre = null;
        return $actividadAinscripcion;
    }

    private function getActividad($id_inscripcion, $actividad, $actividadAnterior = null, $id_actividad_inscripcion_padre = null) {
        $actividadAinscripcion = new stdClass();
        $actividadAinscripcion->id_actividad_inscripcion = $actividad->id_actividad_inscripcion;
        $actividadAinscripcion->id_inscripcion = $id_inscripcion;
        $actividadAinscripcion->id_actividad_etapa = $actividad->id_actividad_etapa;
        $actividadAinscripcion->estado = $actividad->estado_actividad_inscripcion;
        $actividadAinscripcion->id_agenda = $actividad->id_agenda;
        $actividadAinscripcion->archivo = $actividad->archivo;
        $actividadAinscripcion->id_rubrica = $actividad->id_rubrica;
        $actividadAinscripcion->id_evaluacion = $actividad->id_evaluacion;
        $actividadAinscripcion->antecesor = $actividad->antecesor_actividad_inscripcion;
        $actividadAinscripcion->codigo_referencia = $actividad->cod_referencia_actividad_inscripcion;
        if (is_null($actividad->cod_referencia_actividad_inscripcion)) {
            $actividadAinscripcion->codigo_referencia = $actividad->cod_referencia;
        }
        $actividadAinscripcion->fecha_inicio = $actividad->fecha_inicio_actividad_inscripcion;
        if (is_null($actividadAinscripcion->fecha_inicio)) {
            $actividadAinscripcion->fecha_inicio = General::getFechaActualH();
        }
        $actividadAinscripcion->fecha_fin = $actividad->fecha_fin_actividad_inscripcion;
        $actividadAinscripcion->fecha_max_inicio = $actividad->fecha_max_inicio_actividad_inscripcion;
        $actividadAinscripcion->tipo_duracion = $actividad->tipo_duracion;
        $actividadAinscripcion->duracion = $actividad->duracion;
        $actividadAinscripcion->orden = $actividad->orden_actividad_inscripcion;
        if (is_null($actividad->orden_actividad_inscripcion)) {
            $actividadAinscripcion->orden = $actividad->orden_actividad;
        }
        if (!is_null($actividadAnterior) && is_null($actividadAinscripcion->antecesor)) {
            $actividadAinscripcion->antecesor = $actividadAnterior->id_actividad_inscripcion;
        }
        $actividadAinscripcion->predecesor = $actividad->predecesor_actividad_inscripcion;
        $actividadAinscripcion->is_obligatorio = $actividad->is_obligatorio;
        $actividadAinscripcion->fecha_aprobacion = $actividad->fecha_aprobacion;
        $actividadAinscripcion->id_usuario_mod = $actividad->id_usuario_mod;
        $actividadAinscripcion->id_usuario_aprobacion = $actividad->id_usuario_aprobacion;
        $actividadAinscripcion->tiempo_actividad = $actividad->tiempo_actividad;
        $actividadAinscripcion->id_mentor = $actividad->id_mentor;
        $actividadAinscripcion->is_agregada_manual = $actividad->is_agregada_manual;
        if (is_null($actividadAinscripcion->is_agregada_manual)) {
            $actividadAinscripcion->is_agregada_manual = 'NO';
        }
        $actividadAinscripcion->id_asistente_tecnico = $actividad->id_asistente_tecnico;
        $actividadAinscripcion->id_actividad_igual = $actividad->id_actividad_igual_inscripcion;
        if (!is_null($actividad->url_actividad_inscripcion)) {
            $actividadAinscripcion->url = $actividad->url_actividad_inscripcion;
        } else {
            $actividadAinscripcion->url = $actividad->url;
        }
        $actividadAinscripcion->id_actividad_padre = $id_actividad_inscripcion_padre;
        /* $actividadAinscripcion->id_actividad_padre = $actividad->id_actividad_inscripcion_padre;
          if (is_null($actividadAinscripcion->id_actividad_padre)) {
          $actividadAinscripcion->id_actividad_padre = $id_actividad_inscripcion_padre;
          } */
        $actividadAinscripcion->id_etapa = $actividad->id_etapa_actividad_inscripcion;
        $actividadAinscripcion->id_inscripcion_etapa = $actividad->id_inscripcion_etapa;
        $actividadAinscripcion->id_eje_mentoria = $actividad->id_eje_mentoria;
        $actividadAinscripcion->nombre = $actividad->actividad;
        $actividadAinscripcion->id_reunion_asig = $actividad->id_reunion_asig;
        if (is_null($actividad->id_etapa_actividad_inscripcion)) {
            $actividadAinscripcion->id_etapa = $actividad->id_etapa;
        }
        return $actividadAinscripcion;
    }

    public function actualizarMensajeAprobacion() {
        $respuesta = General::validarParametros($_POST, ["id_inscripcion", "fase"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $programaDAO = new ProgramaDAO();
            $programaDAO->setConexion($this->conexion);

            $inscripcionEtapa = $programaDAO->getInscripcionEtapa($_POST["id_inscripcion"], $_POST["fase"], NULL);
            $inscripcionEtapa->estado = 'EP';
            $inscripcionEtapa->ver_mensaje = 'NO';
            $programaDAO->actualizarInscripcionEtapa($inscripcionEtapa);

            if ($this->conexion->getError() != 0) {
                return General::setRespuesta("0", $this->conexion->getError());
            }
            return General::setRespuestaOK();
        } finally {
            $this->cerrarConexion();
        }
    }

    public function updateModuloAtrevete() {
        $respuesta = General::validarParametros($_POST, ["email", "curso", "estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        try {
            $email = $_POST['email'];
            $curso = $_POST['curso'];
            $estado = $_POST['estado'];
            $programaDAO = new ProgramaDAO();
            $personaDAO = new PersonaDAO();

            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);

            $persona = $personaDAO->getPersonaXEmail($email);
            if (is_null($persona)) {
                return General::setRespuesta("0", "No exite el usuario", $respuesta);
            }

            $estados = array('FI', 'EP');
            if (!in_array($estado, $estados)) {
                return General::setRespuesta("0", "Estado not found", $respuesta);
            }

            $cursos = $programaDAO->getActividadModuloAtrevete($persona->id_persona, $curso);
            if (count($cursos) == 0) {
                return General::setRespuesta("0", "No existe el curso", $respuesta);
            }

            if ($estado == 'FI') {
                $estado = 'AP';
            }
            foreach ($cursos as &$actividad) {
                if (!is_null($actividad->estado) && $actividad->estado != 'AP') {
                    $programaDAO->actualizarActividadModuloAtrevete($actividad->id_actividad_inscripcion, $estado);
                }
            }

            return General::setRespuesta("1", "Consulta éxitosa", $respuesta);
            //return General::setRespuesta("1", "Consulta éxitosa", null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function asignarMentorias() {
        $respuesta = General::validarParametros($_POST, ["datos", "id_inscripcion", "id_inscripcion_etapa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $id_reunion = null;
        if (General::tieneValor($_POST, "id_reunion")) {
            $id_reunion = $_POST["id_reunion"];
        }
        $mentorias = json_decode($_POST["datos"]);
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            //$inscripcion = $programaDAO->getSubProgramaxIdInscripcion($_POST["id_inscripcion"]);
            $inscripcionEtapa = $programaDAO->getInscripcionEtapaXId($_POST["id_inscripcion_etapa"]);
            if (is_null($inscripcionEtapa)) {
                return General::setRespuesta("0", "No existe la inscripcion en la etapa");
            }
            if ($inscripcionEtapa->id_inscripcion != $_POST["id_inscripcion"]) {
                return General::setRespuesta("0", "La etapa no pertenece a la inscripcion del emprendedor");
            }
            $actividadesP = $programaDAO->getActividadesInscripcionxIdTipoActividad(5, $inscripcionEtapa->id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa);

            if (count($actividadesP) == 0) {
                return General::setRespuesta("0", "No tiene configurada la actividad de grupos de mentorías en esta etapa");
            }

            if (count($actividadesP) > 1) {
                return General::setRespuesta("0", "Hay mas de una actividad de grupos de mentorias en esta etapa");
            }

            $actividadPadre = $this->getActividad($inscripcionEtapa->id_inscripcion, $actividadesP[0], null, null);
            if (is_null($actividadPadre->id_actividad_inscripcion)) {
                $actividadPadre->is_agregada_manual = 'SI';
                $actividadPadre->estado = 'EP';
                $actividadPadre->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadPadre);
            } 
            else {
                $actividadPadre->estado = 'EP';
                $programaDAO->actualizarActividadEtapaEdicion($actividadPadre);
            }

            if (is_null($actividadPadre->id_actividad_inscripcion)) {
                return General::setRespuesta("0", "No se pudo crear la actividad de grupos de mentorías");
            }

            //$etapa = $programaDAO->getEtapa($inscripcionEtapa->fase);
            
            
            $actividadesP = $programaDAO->getActividadesInscripcionXTipoActividad("15,16", $inscripcionEtapa->id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa);
            $cantidadSesionesPermitidas = $inscripcionEtapa->max_horas_mentoria;
            $cantidadSesiones = count($actividadesP)/2;

            $mentoriasAsign = $programaDAO->getMentoriasAsignadas($inscripcionEtapa->id_inscripcion, $inscripcionEtapa->id_inscripcion_etapa);
            $cantMentorias = count($mentoriasAsign);

            foreach ($mentorias as &$mentoria) {
                $cantidadSesiones += $mentoria->cant;
                if (isset($mentoriasAsign[$mentoria->id_eje_mentoria])) {
                    $cantMentorias++;
                }
            }

            if ($cantidadSesiones > $cantidadSesionesPermitidas) {
                return General::setRespuesta("0", "Ha superado el maximo de sesiones permitidas. Tiene " . (count($actividadesP)/2) . " sesiones registradas");
            }

            if ($cantMentorias > $inscripcionEtapa->max_mentoria) {
                return General::setRespuesta("0", "Ha superado el maximo de mentorías permitidad. Tiene " . count($mentoriasAsign) . " mentorías registradas");
            }
            
            $actividadAnterior = null;
            //$orden = count($actividadesP);
            $actividadesEjecutadas = array();
            
            $mentoriasNuevas = array();
            foreach ($mentorias as &$mentoria) {
                $sesion = 0;
                if (isset($mentoriasAsign[$mentoria->id_eje_mentoria])) {
                    $sesion = $mentoriasAsign[$mentoria->id_eje_mentoria]->cant;
                }
                $actividadesXMentoria = array();
                for ($i = 0; $i < $mentoria->cant; $i++) {
                    $sesion += $i;
                    $actividadEjecutada = new stdClass();
                    $actividadRaiz = $programaDAO->getActividadEtapaTranversal($inscripcionEtapa->id_inscripcion, $inscripcionEtapa->id_etapa, 'ET-002', false);
                    $actividadRaiz2 = $programaDAO->getActividadEtapaTranversal($inscripcionEtapa->id_inscripcion, $inscripcionEtapa->id_etapa, 'ET-003', false);
                    if (is_null($actividadRaiz)) {
                        return General::setRespuesta("0", "No se encuentra configurada la actividad de mentoría");
                    }
                    if (is_null($actividadRaiz2)) {
                        return General::setRespuesta("0", "No se encuentra configurada la actividad de reunion de mentoría");
                    }
                    $actividadEjecutada->actMentoriaR1 = $actividadRaiz;
                    $actividadEjecutada->actMentoriaR2 = $actividadRaiz2;
                    /* Insertar la actividad de mentoria */
                    $actividadRaiz->id_etapa = $inscripcionEtapa->id_etapa;
                    $actividadRaiz->id_inscripcion_etapa = $inscripcionEtapa->id_inscripcion_etapa;
                    $actividadRaiz->id_eje_mentoria = $mentoria->id_eje_mentoria;
                    $actividadRaiz->id_mentor = $mentoria->id_mentor;
                    $actividadRaiz->id_inscripcion = $inscripcionEtapa->id_inscripcion;

                    //$orden++;
                    $actividadAinscripcion = $this->getActividad($inscripcionEtapa->id_inscripcion, $actividadRaiz, $actividadAnterior, $actividadPadre->id_actividad_inscripcion);
                    //$actividadAinscripcion->orden = $orden;
                    $actividadAinscripcion->is_agregada_manual = 'SI';
                    $actividadAinscripcion->is_obligatorio = 'SI';
                    $actividadAinscripcion->id_reunion_asig = $id_reunion;
                    
                    //Verificar el estado en el que debe iniciar la agenda de mentoria
                    if ($sesion == 0)
                        $actividadAinscripcion->estado = 'IN';
                    else
                        $actividadAinscripcion->estado = null;

                    $actividadAinscripcion->id_eje_mentoria = $mentoria->id_eje_mentoria;
                    $actividadAinscripcion->nombre .= ': ' . $mentoria->tema_mentoria . ": SESION " . ($sesion + 1);
                    //$actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
                    $actividadesXMentoria[] = $actividadAinscripcion;
                    
                    /*if (!is_null($actividadAnterior)) {
                        $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                        $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
                    }
                    $actividadAnterior = $actividadAinscripcion;
                    $actividadEjecutada->actividadI1 = $actividadAinscripcion;*/

                    /* Insertar la actividad de reunion de mentoria */
                    $actividadRaiz2->id_etapa = $inscripcionEtapa->id_etapa;
                    $actividadRaiz2->id_inscripcion_etapa = $inscripcionEtapa->id_inscripcion_etapa;
                    $actividadRaiz2->id_eje_mentoria = $mentoria->id_eje_mentoria;
                    $actividadRaiz2->id_mentor = $mentoria->id_mentor;
                    $actividadRaiz2->id_inscripcion = $inscripcionEtapa->id_inscripcion;

                    //$orden++;
                    $actividadAinscripcion = $this->getActividad($inscripcionEtapa->id_inscripcion, $actividadRaiz2, $actividadAnterior, $actividadPadre->id_actividad_inscripcion);
                    //$actividadAinscripcion->orden = $orden;
                    $actividadAinscripcion->is_agregada_manual = 'SI';
                    $actividadAinscripcion->is_obligatorio = 'SI';
                    $actividadAinscripcion->id_reunion_asig = $id_reunion;
                    $actividadAinscripcion->estado = null;
                    $actividadAinscripcion->nombre .= ': ' . $mentoria->tema_mentoria . ": SESION " . ($sesion + 1);
                    //$actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
                    
                    $actividadesXMentoria[] = $actividadAinscripcion;
                    
                    /*if (!is_null($actividadAnterior)) {
                        $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                        $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
                    }
                    $actividadAnterior = $actividadAinscripcion;
                    $actividadEjecutada->actividadI2 = $actividadAinscripcion;
                    $actividadesEjecutadas[] = $actividadEjecutada;*/
                }
            
                $mentoriasNuevas[$mentoria->id_eje_mentoria]=$actividadesXMentoria;
            }
            
            
            $orden = 1;
            $id_eje_mentoriaAnt = 0;
            $actividadAnterior = null;
            //Reordenar las actividades de mentoria
            //En caso de tener actividades de mentoria ya creadas se verifica el orden que debe presidir a la actividad
            $cont=0;
            foreach($actividadesP as $actividadExistente){
                $actividadExistente->orden = $orden;
                $actividadAinscripcion = $actividadExistente;
                if (!is_null($actividadAnterior)) {
                    $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                    $actividadAinscripcion->antecesor = $actividadAnterior->id_actividad_inscripcion;
                    $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
                }
                $programaDAO->actualizarActividadEtapaEdicion($actividadAinscripcion);
                $actividadAnterior = $actividadAinscripcion;
                $actividadesEjecutadas[] = $actividadAinscripcion;
                $cont++;
                $asignar = false;
                if(count($actividadesP) == $cont){
                    $asignar = true;
                    $id_eje_mentoriaAnt = $actividadExistente->id_eje_mentoria;
                }
                $orden++;
                if($actividadExistente->id_eje_mentoria != $id_eje_mentoriaAnt || $asignar){
                    if($id_eje_mentoriaAnt != 0){
                        if(isset($mentoriasNuevas[$id_eje_mentoriaAnt])){
                            $mentoriaNueva = $mentoriasNuevas[$id_eje_mentoriaAnt];
                            foreach ($mentoriaNueva as $actividadNueva){
                                $actividadNueva->orden = $orden;
                                $actividadAinscripcion = $actividadNueva;
                                if (!is_null($actividadAnterior)) {
                                    if($actividadAnterior->estado == 'AP'){
                                        $actividadAinscripcion->estado = 'IN';
                                    }else{
                                        $actividadAinscripcion->estado = null;
                                    }
                                    $actividadAinscripcion->antecesor = $actividadAnterior->id_actividad_inscripcion;
                                }
                                $actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
                                if (!is_null($actividadAnterior)) {
                                    $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                                    $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
                                }
                                $actividadAnterior = $actividadAinscripcion;
                                $orden++;
                                $actividadesEjecutadas[] = $actividadAinscripcion;
                            }
                            unset($mentoriasNuevas[$id_eje_mentoriaAnt]);
                        }
                    }
                    $id_eje_mentoriaAnt = $actividadExistente->id_eje_mentoria;
                }
            }
            
            //agregar las mentorias que no tienen mentorias previas agregadas
            foreach ($mentoriasNuevas as $mentoriaNueva){
                foreach ($mentoriaNueva as $actividadNueva){
                    $respuesta->recorrido1[]=$actividadNueva;
                    $actividadNueva->orden = $orden;
                    $actividadAinscripcion = $actividadNueva;
                    if (!is_null($actividadAnterior)) {
                        $actividadAinscripcion->antecesor = $actividadAnterior->id_actividad_inscripcion;
                    }
                    $actividadAinscripcion->id_actividad_inscripcion = $programaDAO->insertarActividadEtapaEdicion($actividadAinscripcion);
                    if (!is_null($actividadAnterior)) {
                        $actividadAnterior->predecesor = $actividadAinscripcion->id_actividad_inscripcion;
                        $programaDAO->actualizarActividadEtapaEdicion($actividadAnterior);
                    }
                    $actividadAnterior = $actividadAinscripcion;
                    $actividadesEjecutadas[] = $actividadAinscripcion;
                    $orden++;
                }
            }
            
            $estados = array('AP', 'CF', 'AN', 'PF');

            if (!in_array($inscripcionEtapa->estado, $estados)) {
                $inscripcion = new stdClass();
                $inscripcion->fase = $inscripcionEtapa->id_etapa;
                $inscripcion->id_inscripcion = $inscripcionEtapa->id_inscripcion;
                $actividades = $this->cargarArbolActividades($programaDAO, $inscripcionEtapa->id_inscripcion, $inscripcionEtapa->id_etapa, null);
                $actividadesEjecutadas = array_merge($this->_asignarActividades($programaDAO, $inscripcion, $actividades, $inscripcionEtapa, null), $actividadesEjecutadas, $id_reunion);
                $inscripcionEtapa->estado = 'PF';
                $inscripcionEtapa->fecha_aprobacion = null;
                $programaDAO->actualizarInscripcionEtapa($inscripcionEtapa);
            }
            return General::setRespuestaOK($actividadesEjecutadas);
        } 
        catch (Exception $ex) {
            return General::setRespuesta("0", "Ha ocurrido un error en la actualizacion de la actividad", null);
        } finally {
            $this->cerrarConexion();
        }
    }

    private function cargarArbolActividades($programaDAO, $id_inscripcion, $fase, $id_actividad_padre) {
        $actividades = $programaDAO->getActividadxEtapaxInscripcion($id_inscripcion, $fase, $id_actividad_padre, false);
        if (is_null($actividades)) {
            $actividades = array();
        }
        foreach ($actividades as &$actividad) {
            $actividad->orden = $actividad->orden_actividad_inscripcion;
            $actividad->child = $this->cargarArbolActividades($programaDAO, $id_inscripcion, $fase, $actividad->id_actividad_etapa);
        }
        return $actividades;
    }

    public function getInscripcionEtapa() {
        $respuesta = General::validarParametrosOR($_POST, ["id_inscripcion_etapa", "id_actividad_inscripcion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            if (General::tieneValor($_POST, "id_inscripcion_etapa")) {
                $data = $programaDAO->getInscripcionEtapaXId($_POST["id_inscripcion_etapa"]);
            } else {
                $data = $programaDAO->getInscripcionEtapaXIdActividad($_POST["id_actividad_inscripcion"]);
            }
            return General::setRespuestaOK($data);
        } catch (Exception $ex) {
            return General::setRespuesta("0", "Ha ocurrido un error en la actualizacion de la actividad", null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getActividadxIdAgenda() {
        $respuesta = General::validarParametrosOR($_POST, ["id_agenda"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            $data = $programaDAO->getActividadInscripcionxIdAgenda($_POST["id_agenda"]);
            return General::setRespuestaOK($data);
        } 
        catch (Exception $ex) {
            return General::setRespuesta("0", "Ha ocurrido un error en la actualizacion de la actividad", null);
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function getMentoriasPendientesEtapa() {
        $respuesta = General::validarParametrosOR($_POST, ["id_inscripcion", "id_inscripcion_etapa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            $lista = $programaDAO->getMentoriasPendientesEtapa($_POST["id_inscripcion"], $_POST["id_inscripcion_etapa"]);
            $listaMentorias = array();
            $idAnt = null;
            foreach ($lista as $actividad){
                if($idAnt != $actividad->id_eje_mentoria){
                    $mentoria = new stdClass();
                    $mentoria->id_persona = $actividad->id_persona;
                    $mentoria->id_mentor = $actividad->id_mentor;
                    $mentoria->id_eje_mentoria = $actividad->id_eje_mentoria;
                    $mentoria->nombre_mentor = $actividad->nombre_mentor;
                    $mentoria->tema_mentoria = $actividad->tema_mentoria;
                    $mentoria->id_inscripcion = $actividad->id_inscripcion;
                    $mentoria->id_etapa = $actividad->id_etapa;
                    $mentoria->id_inscripcion_etapa = $actividad->id_inscripcion_etapa;
                    $mentoria->cant = 0;
                    
                    $mentor = new stdClass();
                    $mentor->id_persona = $actividad->id_persona;
                    $mentor->id_mentor = $actividad->id_mentor;
                    $mentor->nombre_mentor = $actividad->nombre_mentor;
                    $mentor->id_mentor = $actividad->id_mentor;
                    $mentor->descripcion_perfil = $actividad->descripcion_perfil;
                    
                    $mentoria->mentor = $mentor;
                    $mentoria->mentorAnt = $mentor;
                    $mentoria->actividades = array();
                    $listaMentorias[] = $mentoria;
                    $idAnt = $actividad->id_eje_mentoria;
                }
                $mentoria->cant++;
                $mentoria->actividades[]=$actividad;
            }
            return General::setRespuestaOK($listaMentorias);
        } 
        catch (Exception $ex) {
            return General::setRespuesta("0", "Ha ocurrido un error en la actualizacion de la actividad", null);
        } finally {
            $this->cerrarConexion();
        }
    }
    
    public function cambiarMentores() {
        $respuesta = General::validarParametrosOR($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            $lista = json_decode($_POST["datos"]);
            $listaMentorias = array();
            $idAnt = null;
            foreach ($lista as $mentoria){
                foreach ($mentoria->actividades as $actividad){
                    $programaDAO->actualizarMentor($mentoria->id_mentor, $actividad->id_actividad_inscripcion);
                    $programaDAO->actualizarMentor($mentoria->id_mentor, $actividad->predecesor);
                }
            }
            return General::setRespuestaOK($listaMentorias);
        } 
        catch (Exception $ex) {
            return General::setRespuesta("0", "Ha ocurrido un error en la actualizacion de la actividad", null);
        } finally {
            $this->cerrarConexion();
        }
    }

    /* Mantenimiento */

    public function getActividades() {
        $respuesta = General::validarParametros($_POST, ["id_etapa", "estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $programaDAO = new ProgramaDAO();
            $programaDAO->setConexion($this->conexion);
            $data = $programaDAO->getActividadesxIdEtapa($_POST["id_etapa"], $_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function grabarActividadEtapa() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $actividadD = json_decode($_POST["datos"]);
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            if (!General::tieneValor($actividadD, "id")) {
                $actividadD->id = $programaDAO->insertarActividadEtapa($actividadD);
            }
            if (!General::tieneValor($actividadD, "id")) {
                return General::setRespuesta("0", $this->conexion->getMensajeError(), $actividadD);
            }
            if (isset($_FILES['logoFile'])) {
                $file = $_FILES['logoFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "actividad" . $actividadD->id . "_logo." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $actividadD->logo = $nameImage;
                }
            }
            if (isset($_FILES['iconoFile'])) {
                $file = $_FILES['iconoFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "actividad" . $actividadD->id . "_icono." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $actividadD->icono = $nameImage;
                }
            }
            if (isset($_FILES['bannerFile'])) {
                $file = $_FILES['bannerFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "actividad" . $actividadD->id . "_banner." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $actividadD->banner = $nameImage;
                }
            }
            if (isset($_FILES['archivoFile'])) {
                $file = $_FILES['archivoFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "actividad" . $actividadD->id . "_archivo." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $actividadD->archivo = $nameImage;
                }
            }
            $programaDAO->actualizarActividadEtapa($actividadD);
            if ($this->conexion->getError() != 0) {
                return General::setRespuesta("0", $this->conexion->getMensajeError(), $actividadD);
            }
            if (General::tieneValor($actividadD, "listaMensajes")) {
                $programaDAO->eliminarMensajePersonalizadoActividadEtapa($actividadD->id);
                foreach ($actividadD->listaMensajes as &$mensaje) {
                    if ($mensaje->texto_mensaje != $mensaje->nombre && General::tieneValor($mensaje, "texto_mensaje")) {
                        $mensaje->id_actividad_etapa = $actividadD->id;
                        $programaDAO->insertarMensajePersonalizadoActividadEtapa($mensaje);
                    }
                }
            }

            return General::setRespuesta("1", "Actividad graba con éxito", $actividadD);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getEtapasXSubPrograma() {
        $respuesta = General::validarParametros($_POST, ["id_sub_programa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $programaDAO = new ProgramaDAO();
            $programaDAO->setConexion($this->conexion);
            $data = $programaDAO->getEtapaxSubPrograma($_POST["id_sub_programa"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function grabarEtapa() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $etapa = json_decode($_POST["datos"]);
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            if (!General::tieneValor($etapa, "id")) {
                $etapa->id = $programaDAO->insertarEtapa($etapa);
            }
            if (isset($_FILES['logoFile'])) {
                $file = $_FILES['logoFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "etapa" . $etapa->id . "_logo." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $etapa->logo = $nameImage;
                }
            }
            if (isset($_FILES['iconoFile'])) {
                $file = $_FILES['iconoFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "etapa" . $etapa->id . "_icono." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $etapa->icono = $nameImage;
                }
            }
            if (isset($_FILES['bannerFile'])) {
                $file = $_FILES['bannerFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "etapa" . $etapa->id . "_banner." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $etapa->banner = $nameImage;
                }
            }
            if (isset($_FILES['archivoFile'])) {
                $file = $_FILES['archivoFile'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "etapa_" . $etapa->id . "_plan_trabajo." . $ext;
                    ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    $etapa->plan_trabajo = $nameImage;
                }
            }
            $programaDAO->actualizarEtapa($etapa);
            if ($this->conexion->getError() != 0) {
                return General::setRespuesta("0", $this->conexion->getMensajeError(), $etapa);
            }
            return General::setRespuesta("1", "Etapa graba con éxito", $etapa);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getMensajePersonalizadoXIdActividadEtapa() {
        $respuesta = General::validarParametros($_POST, ["id_actividad_etapa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $programaDAO = new ProgramaDAO();
            $programaDAO->setConexion($this->conexion);
            $data = $programaDAO->getMensajePersonalizadoXIdActividadEtapa($_POST["id_actividad_etapa"]);
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function grabarActividadesEtapa() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $actividades = json_decode($_POST["datos"]);
        try {
            $programaDAO = new ProgramaDAO();
            $this->conectar();
            $programaDAO->setConexion($this->conexion);
            foreach ($actividades as &$actividad) {
                $programaDAO->actualizarActividadEtapa($actividad);
            }
            if ($this->conexion->getError() != 0) {
                return General::setRespuesta("0", $this->conexion->getMensajeError(), $actividades);
            }
            return General::setRespuesta("1", "Actividades grabadas con éxito", $actividades);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), null);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function actualizarNewOrdenActividades() {
        $respuesta = General::validarParametros($_POST, ["id_etapa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $programaDAO = new ProgramaDAO();
            $programaDAO->setConexion($this->conexion);
            $programaDAO->actualizarNewOrdenActividades($_POST["id_etapa"]);
            $this->cerrarConexion();
            return General::setRespuestaOK();
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getAsistenteTecnicoAsignado() {
        $respuesta = General::validarParametros($_POST, ["id_inscripcion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $programaDAO = new ProgramaDAO();
            $programaDAO->setConexion($this->conexion);
            $data = $programaDAO->getAsistenteTecnicoAsignado($_POST["id_inscripcion"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

}
