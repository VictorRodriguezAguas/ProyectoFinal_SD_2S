<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AgendaBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/AsistenciaTecnicaDAO.php';
require_once '../dao/AgendaDAO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../util/General.php';
require_once '../dao/CorreoDAO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../bo/ProgramaBO.php';
require_once '../dao/MentorDAO.php';

class AgendaBO extends BO {

    public function getAgenda() {
        $id_agenda = null;
        $id_actividad_inscripcion = null;
        $respuesta = General::validarParametros($_POST, ["id_agenda"]);
        if (!is_null($respuesta)) {
            $respuesta = General::validarParametros($_POST, ["id_actividad_inscripcion"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
            $id_actividad_inscripcion = $_POST["id_actividad_inscripcion"];
        } 
        else {
            $id_agenda = $_POST["id_agenda"];
        }
        $this->conectar();
        $agendaDAO = new AgendaDAO();
        $agendaDAO->setConexion($this->conexion);
        if (is_null($id_agenda)) {
            $programaDAO = new ProgramaDAO();
            $programaDAO->setConexion($this->conexion);
            $actividad = $programaDAO->getActividadInscripcionxId($_POST["id_actividad_inscripcion"]);
            if (is_null($actividad)) {
                return General::setRespuesta("0", "No existe la actividad");
            }
            if (is_null($actividad->id_agenda)) {
                $actividadD = $programaDAO->getActividadInscripcionxId($actividad->antecesor_actividad_inscripcion);
                if (is_null($actividadD->id_agenda)) {
                    return General::setRespuesta("0", "No se encontro la agenda");
                }
                $id_agenda = $actividadD->id_agenda;
            } 
            else {
                $id_agenda = $actividad->id_agenda;
            }
        }
        $data = $agendaDAO->getAgendaxId($id_agenda);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function insertarAgendaAT() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $id_asistente_tecnico = null;
        if (General::tieneValor($_POST, "id_asistente_tecnico")) {
            $id_asistente_tecnico = $_POST["id_asistente_tecnico"];
        }
        $asistenciaTecnicaDAO = new AsistenciaTecnicaDAO();
        $consultasDAO = new ConsultasDAO();
        $agendaDAO = new AgendaDAO();
        try {
            $agenda = json_decode($_POST["datos"]);
            
            $this->conectar();
            $asistenciaTecnicaDAO->setConexion($this->conexion);
            $consultasDAO->setConexion($this->conexion);
            $agendaDAO->setConexion($this->conexion);
            $dia = null;
            if (General::tieneValor($agenda, "diaN")){
                $dia = General::$dias[$agenda->diaN + 1];
            }

            /* Extraer la agenda de todos los asistentes tecnicos en el dia y hora agendada */
            $hagendado = $asistenciaTecnicaDAO->getAsistenciaAgendadaXdiaXhora($agenda->fecha, $agenda->hora_inicio, $agenda->hora_fin, 'ASISTENCIA TECNICA', $id_asistente_tecnico);
            /* Extraer los horarios configurados de los asistentes tecnicos correspondientes al dia */
            $lista = $asistenciaTecnicaDAO->getHorarioAsistenciaTecnica($dia, $agenda->fecha, $id_asistente_tecnico);
            if (count($lista) == 0) {
                return General::setRespuesta("0", "No existe horarios para esta fecha", null);
            }

            /* Fragmentar los horarios por hora */
            $horasDias = array();
            foreach ($lista as &$diaH) {
                $horasDias = General::dividirHorario($horasDias, $diaH);
            }

            /* Extraer los valores unicos segun dia y hora ejemplo Lunes 08:00, Lunes: 09:00, Lunes: 10:00 ... */
            $horasDiasUniq = array_unique($horasDias, SORT_REGULAR);
            /* Contabilizar cuantos asistentes tecnicos hay asignado para cada hora del dia */
            $horarios = General::contabilizarHorario($horasDiasUniq, $horasDias);
            /* Valida si hay disponibilidad de horario en el dia y hora señalada */
            if (count($hagendado) > 0) {
                $agendado = $hagendado[0];
                $horario = $horarios[0];
                foreach ($horario->horas as &$hora) {
                    if ($hora->hora_inicio == $agendado->hora_inicio) {
                        if ($hora->count <= intval($agendado->cant)) {
                            return General::setRespuesta("2", "Horario ya fue agendado por otro usuario", null);
                        }
                    }
                }
            }
            /* Extraer el asistente tecnico que menos asignaciones en el dia tiene */
            $asistenteTecnico = $asistenciaTecnicaDAO->getAsistenteTecnicoCantAgendada($dia, $agenda->fecha, $agenda->hora_inicio, $agenda->hora_fin, 'ASISTENCIA TECNICA', $id_asistente_tecnico);
            $agenda->id_persona2 = $asistenteTecnico->id_persona;
            $agenda->id_asistente_tecnico = $asistenteTecnico->id;
            $agenda->color = '#a389d4';
            $agenda->id_agenda = $agendaDAO->insertarAgenda($agenda);
            $agenda->lugar = $consultasDAO->getParamSystem("UBICACION_EPICO");
            
            /*$respuesta=new stdClass();
            $respuesta->agenda = $agenda;
            $respuesta->horarios = $horarios;
            $respuesta->horasDiasUniq = $horasDiasUniq;
            $respuesta->horasDias = $horasDias;
            $respuesta->hagendado = $hagendado;
            $respuesta->lista = $lista;
            $respuesta->id_asistente_tecnico = $id_asistente_tecnico;
            return General::setRespuestaOK($respuesta);*/
        } 
        finally {
            $this->cerrarConexion();
        }

        return General::setRespuestaOK($agenda);
    }

    public function getAgendaxPersona() {
        $respuesta = General::validarParametros($_POST, ["id_persona"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $agendaDAO = new AgendaDAO();
        $agendaDAO->setConexion($this->conexion);
        $data = $agendaDAO->getAgendaxIdPersona($_POST["id_persona"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getHorarioDisponibilidadAT() {
        date_default_timezone_set('America/Bogota');
        $this->conectar();
        try {
            $id_asistente_tecnico = null;
            if (General::tieneValor($_POST, "id_asistente_tecnico")) {
                $id_asistente_tecnico = $_POST["id_asistente_tecnico"];
            }
            $respuesta = $this->_getHorarioDisponibilidadAT($id_asistente_tecnico);
        } 
        finally {
            $this->cerrarConexion();
        }
        return General::setRespuestaOK($respuesta);
    }

    private function _getHorarioDisponibilidadAT($id_asistente_tecnico) {
        $respuesta = new stdClass();
        $asistenciaTecnicaDAO = new AsistenciaTecnicaDAO();
        $consultasDAO = new ConsultasDAO();
        $asistenciaTecnicaDAO->setConexion($this->conexion);
        $consultasDAO->setConexion($this->conexion);
        $horasInicio = $consultasDAO->getParamSystem("HORAS_MIN_AGENDA_AT");
        $maxDias = $consultasDAO->getParamSystem("MAX_DIAS_AGENDA_AT");
        if (is_null($horasInicio)) {
            $horasInicio = 0;
        }
        if (is_null($maxDias)) {
            $maxDias = 30;
        }

        $today = date('Y-m-d');
        $today = date('Y-m-d', strtotime($today . " + $horasInicio hour"));
        $horaDate = date('H', strtotime(date('Y-m-d H:i:s') . " + $horasInicio hour"));
        $horarioMes = array();
        for ($i = 0; $i <= $maxDias; $i++) {
            $next_date = date('Y-m-d', strtotime($today . " + $i days"));

            $lista = $asistenciaTecnicaDAO->getHorarioAsistenciaTecnica(null, $next_date, $id_asistente_tecnico);

            $horasDias = array();
            foreach ($lista as &$dia) {
                $horasDias = General::dividirHorario($horasDias, $dia);
            }
            $horasDiasUniq = array_unique($horasDias, SORT_REGULAR);
            $horarios = General::contabilizarHorario($horasDiasUniq, $horasDias);

            $diaTxt = General::$dias[date('w', strtotime($next_date))];
            $horario = General::findArray($horarios, "dia", $diaTxt);
            $listaAgenda = $asistenciaTecnicaDAO->getAsistenciaAgendadaXfechas($next_date, $next_date, $id_asistente_tecnico);
            $horasTomadas = array();
            foreach ($listaAgenda as &$diaHorario) {
                $diaHorario->dia = $diaTxt;
                $horasTomadas = General::dividirHorario($horasTomadas, $diaHorario);
            }
            $horarioDia = new stdClass();
            $horarioDia->fecha = $next_date;
            $horarioDia->dia = $diaTxt;
            $horarioDia->horasTomadas = $horasTomadas;
            $horarioDia->horasTomadasU = array_unique($horasTomadas, SORT_REGULAR);
            $horarioDia->agenda = General::contabilizarHorario($horarioDia->horasTomadasU, $horasTomadas);
            $horarioDia->disponibilidad = array();
            $horarioDia->noDisponibilidad = array();
            $horarioDia->horarios = $horarios;

            $horarioMes[] = General::cargarDisponibilidad($horario, $horarioDia, $horasInicio);
            
            /*foreach ($horario as &$horas) {
                foreach ($horas->horas as &$hora) {
                    if ($horarioDia->fecha == $today &&  intval(str_replace(':00:00', '', $hora->hora_inicio)) <= intval($horaDate)) {
                        continue;
                    }
                    if (count($horarioDia->agenda) > 0) {
                        $band = true;
                        foreach ($horarioDia->agenda as &$diaAgendado) {
                            foreach ($diaAgendado->horas as &$horaAgenda) {
                                if ($hora->hora_inicio == $horaAgenda->hora_inicio) {
                                    if ($hora->count <= $horaAgenda->count) {
                                        $horaAgenda->titulo = 'Agendado';
                                        $horarioDia->noDisponibilidad[] = $horaAgenda;
                                    } else {
                                        $horarioDia->disponibilidad[] = $hora;
                                    }
                                    $band = false;
                                    break;
                                }
                            }
                        }
                        if ($band) {
                            $horarioDia->disponibilidad[] = $hora;
                        }
                    } else {
                        $horarioDia->disponibilidad[] = $hora;
                    }
                }
            }
            $horarioMes[] = $horarioDia;*/
        }

        $respuesta->horarios = $horarios;
        $respuesta->maxDias = $maxDias;
        $respuesta->minHora = $horasInicio;
        $respuesta->horarioMes = $horarioMes;
        $respuesta->horaDate = $horaDate;
        $respuesta->fecha_actual = date('Y-m-d H:i:s');
        return $respuesta;
    }

    public function insertarAgendaEvento() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $agenda = json_decode($_POST["datos"]);
        $agendaDAO = new AgendaDAO();
        try {
            $this->conectar();
            $agendaDAO->setConexion($this->conexion);
            $evento = $agendaDAO->getEvento($agenda->id_evento, NULL, NULL);
            if (is_null($evento)) {
                return General::setRespuesta("2", "No existe el evento o esta inactivo", null);
            }
            if (!is_null($evento->cupo)) {
                if ($evento->registrados >= $evento->cupo) {
                    return General::setRespuesta("2", "No hay cupos disponibles", null);
                }
            }
            $agenda->id_agenda = $agendaDAO->insertarAgenda($agenda);
            $mailBO = new MailBO();
            $consultaDAO = new ConsultasDAO();
            $mailBO->setConexion($this->conexion);
            $consultaDAO->setConexion($this->conexion);
            $datosTrama = new stdClass();
            $datosTrama->id_agenda = $agenda->id_agenda;
            $mailMesa = $consultaDAO->getParamSystem("MAIL_MESA_SERVICIO");
            $agenda->trama1 = $mailBO->enviarCorreoTrama("MXTALLER01", $datosTrama, null, null, null);
            $agenda->trama2 = $mailBO->enviarCorreoTrama("MXTALLER02", $datosTrama, $mailMesa, null, null);
            return General::setRespuestaOK($agenda);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function cancelarAgenda() {
        $respuesta = General::validarParametros($_POST, ["id_agenda", "observacion", "id_motivo"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $agendaDAO = new AgendaDAO();
        try {
            $this->conectar();
            $agendaDAO->setConexion($this->conexion);
            $agenda = $agendaDAO->getAgendaxId($_POST["id_agenda"]);
            if (is_null($agenda)) {
                return General::setRespuesta("0", "No existe agenda", null);
            }
            $agenda->observacion = $_POST["observacion"];
            $agenda->id_motivo_cancelar = $_POST["id_motivo"];
            $agenda->id_usuario_can = $this->user->id_usuario;
            if (General::tieneValor($agenda, "id_actividad_inscripcion")) {
                $programaBO = new ProgramaBO();
                $programaDAO = new ProgramaDAO();
                $consultaDAO = new ConsultasDAO();
                $programaBO->setConexion($this->conexion);
                $programaDAO->setConexion($this->conexion);
                $consultaDAO->setConexion($this->conexion);
                $actividadD = $programaDAO->getActividadInscripcionxId($agenda->id_actividad_inscripcion);

                switch ($actividadD->id_tipo_actividad) {
                    case '4':
                        $valor = $consultaDAO->getParamSystem("HORAS_MIN_AGENDA_CAN");
                        if (!is_null($valor)) {
                            $valor = intval($valor);
                            $hora = explode(":", $agenda->hora_inicio);
                            $fechaAgenda = new DateTime($agenda->fecha);
                            $fechaAgenda->setTime($hora[0], $hora[1], $hora[2]);

                            $fechaActual = new DateTime();
                            //$fechaActual->setTime(10, $hora[1], $hora[2]);
                            $fechaActual->add(new DateInterval("PT{$valor}H"));
                            if ($fechaActual > $fechaAgenda) {
                                return General::setRespuesta("0", "No se pudo cancelar la cita. Debe cancelar la cita con {$valor} horas de anticipacion", null);
                            }
                        }
                        break;
                    case '15':
                        $valor = $consultaDAO->getParamSystem("HORAS_MIN_AGENDA_CAN");
                        if (!is_null($valor)) {
                            $valor = intval($valor);
                            $hora = explode(":", $agenda->hora_inicio);
                            $fechaAgenda = new DateTime($agenda->fecha);
                            $fechaAgenda->setTime($hora[0], $hora[1], $hora[2]);

                            $fechaActual = new DateTime();
                            //$fechaActual->setTime(10, $hora[1], $hora[2]);
                            $fechaActual->add(new DateInterval("PT{$valor}H"));
                            if ($fechaActual > $fechaAgenda) {
                                return General::setRespuesta("0", "No se pudo cancelar la cita. Debe cancelar la cita con {$valor} horas de anticipacion", null);
                            }
                        }
                        break;
                    default :
                        break;
                }
                return $programaBO->_revertirActividad($programaDAO, $actividadD, $agenda);
            } 
            else {
                $agenda->estado = 'CA';
                $agendaDAO->actualizarAgenda($agenda);
            }
            return General::setRespuestaOK($agenda);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function getHorarioMentor() {
        $respuesta = General::validarParametros($_POST, ["id_mentor"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        date_default_timezone_set('America/Bogota');
        $this->conectar();
        try {
            $mentorDAO = new MentorDAO();
            $agendaDAO = new AgendaDAO();
            $consultasDAO = new ConsultasDAO();
            $mentorDAO->setConexion($this->conexion);
            $consultasDAO->setConexion($this->conexion);
            $agendaDAO->setConexion($this->conexion);
            $id_mentor = $_POST["id_mentor"];
            $mentor = $mentorDAO->getMentor($id_mentor);

            $horasInicio = $consultasDAO->getParamSystem("HORAS_MIN_AGENDA_AT");
            $maxMes = 1;
            if (is_null($horasInicio)) {
                $horasInicio = 0;
            }

            $data = new stdClass();
            $data->inicio_semana = General::getInicioSemana();
            $data->fin_semana = General::getFinSemana();
            $data->inicio_mes = General::getInicioMes();
            $data->fin_mes = General::getFinMes();

            $fecha_inicio_semana = date("Y-m-d", strtotime($data->inicio_semana . "+$horasInicio hour"));
            $fecha_fin_semana = General::getFinSemana(strtotime("$fecha_inicio_semana"));

            if ($fecha_inicio_semana < $data->inicio_mes) {
                $fecha_inicio_semana = $data->inicio_mes;
            }

            $fechaInicioUltimoMes = date("Y-m-d", strtotime($data->inicio_mes . "+$maxMes months"));
            $fechaFinUltimoMes = date("Y-m-d", strtotime($fechaInicioUltimoMes . "-1 days"));
            $data->fecha_final = $fechaFinUltimoMes;

            $maxDias = General::getDiferencia($fechaFinUltimoMes, $fecha_inicio_semana);

            /* Extraer horarios del mentor y dividirlos por hora */
            $lista = $mentorDAO->getHorarioMentor($id_mentor);

            /* Distribuir por cada dia las horas correspondientes */
            $horasDias = array();
            foreach ($lista as &$dia) {
                $horasDias = General::dividirHorario($horasDias, $dia);
            }
            $horasDiasUniq = array_unique($horasDias, SORT_REGULAR);
            $horarios = General::contabilizarHorario($horasDiasUniq, $horasDias);
            //$horariosConstrains = General::contrainsHorario($horarios);

            $data->horarioMentor = $horasDias;
            $data->horarios = $horarios;
            //$data->constrains = $horariosConstrains;
            $data->horasDiasUniq = $horasDiasUniq;
            /* Recorrer inicio del primer mes hasta el fin del ultimo mes */
            $mesAnt = 0;
            while ($fecha_fin_semana < $fechaFinUltimoMes) {
                //$fecha_fin_semana = date("Y-m-d", strtotime($fecha_inicio_semana . "+6 days"));
                $fecha_fin_semana = General::getFinSemana(strtotime("$fecha_inicio_semana"));
                if ($fecha_fin_semana > $fechaFinUltimoMes) {
                    $fecha_fin_semana = $fechaFinUltimoMes;
                }

                $semana = new stdClass();
                $semana->inicio_semana = $fecha_inicio_semana;
                $semana->fin_semana = $fecha_fin_semana;
                $fechasMeses[] = $semana;

                /* Validar que tenga habilitada la semana de mentoria */
                $agregarDispo = true;
                if ($fecha_inicio_semana < $data->inicio_semana) {
                    $periodoMentor = $mentorDAO->getPeriodoMentor($id_mentor, $data->inicio_semana);
                } 
                else {
                    $periodoMentor = $mentorDAO->getPeriodoMentor($id_mentor, $fecha_inicio_semana);
                }
                if (is_null($periodoMentor)) {
                    $fecha_inicio_semana = date("Y-m-d", strtotime($fecha_fin_semana . "+1 days"));
                    continue;
                }
                if ($periodoMentor->fecha_fin < $fecha_fin_semana) {
                    $fecha_fin_semana = $periodoMentor->fecha_fin;
                }

                /* Validar limite max de sesiones por semana */
                if (!$this->validarMaxSesionesMentor($agendaDAO, $fecha_inicio_semana, $fecha_fin_semana, $mentor->id_persona, $periodoMentor, 1)) {
                    //$fecha_inicio_semana = date("Y-m-d", strtotime($fecha_fin_semana . "+1 days"));
                    $agregarDispo = false;
                }

                /* Validar limite de sesiones por mes */
                $mes = General::getMes($fecha_inicio_semana);
                if ($mesAnt != $mes) {
                    if (!$this->validarMaxSesionesMesMentor($agendaDAO, $fecha_inicio_semana, $mentor->id_persona, $periodoMentor, 1)) {
                        //$fecha_inicio_semana = date("Y-m-d", strtotime($fecha_fin_semana . "+1 days"));
                        $agregarDispo = false;
                    }
                    $mesAnt = $mes;
                }

                $fecha_inicio_dia = $fecha_inicio_semana;
                $i = 0;
                while ($fecha_fin_semana > $fecha_inicio_dia) {
                    $diaTxt = General::$dias[date('w', strtotime($fecha_inicio_dia))];

                    /* Horas agendadas en el dia */
                    $param = new stdClass();
                    $param->fecha = $fecha_inicio_dia;
                    $param->id_persona2 = $mentor->id_persona;
                    $param->tipo = 'MENTORIA';
                    $listaAgenda = $agendaDAO->getAgendaxParam($param);
                    $horasTomadas = array();
                    foreach ($listaAgenda as &$diaHorario) {
                        $diaHorario->dia = $diaTxt;
                        $horasTomadas = General::dividirHorario($horasTomadas, $diaHorario);
                    }

                    /* Cargar disponibilidad por dia */
                    $horarioDia = new stdClass();
                    $horarioDia->fecha = $fecha_inicio_dia;
                    $horarioDia->dia = $diaTxt;
                    //horarios agendados
                    $horarioDia->agenda = array_unique($horasTomadas, SORT_REGULAR);
                    $horarioDia->agenda = General::contabilizarHorario($horarioDia->agenda, $horasTomadas);
                    $horarioDia->disponibilidad = array();
                    $horarioDia->noDisponibilidad = array();
                    $horarioDia->horarios = $horarios;
                    $horario = General::findArray($horarios, "dia", $diaTxt);
                    $horarioDia = General::cargarDisponibilidad($horario, $horarioDia, $horasInicio, $agregarDispo);
                    //$horarioDia->constrains = General::findArray($horariosConstrains, "dia", $diaTxt);
                    //$horarioDia->constrains = General::getContrais($horarioDia, $horariosConstrains);
                    $horarioMes[] = $horarioDia;
                    $fecha_inicio_dia = date("Y-m-d", strtotime($fecha_inicio_dia . "+1 days"));
                    $i++;
                }

                $fecha_inicio_semana = date("Y-m-d", strtotime($fecha_fin_semana . "+1 days"));
            }
            $data->semanas_mes = $fechasMeses;
            $data->mentor = $mentor;
            $data->horarioMes = $horarioMes;
            $data->maxDias = $maxDias + 1;
            $data->fecha_actual = date('Y-m-d H:i:s');
            $data->horaDate = date('H', strtotime(date('Y-m-d H:i:s') . " + $horasInicio hour"));
            $data->minHora = $horasInicio;
            return General::setRespuestaOK($data);
            //return General::setRespuestaOK();
        } 
        finally {
            $this->cerrarConexion();
        }
        return General::setRespuestaOK($respuesta);
    }

    public function insertarAgendaMentor() {
        $respuesta = General::validarParametros($_POST, ["datos", "id_mentor"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $mentorDAO = new MentorDAO();
        $consultasDAO = new ConsultasDAO();
        $agendaDAO = new AgendaDAO();
        try {
            $agenda = json_decode($_POST["datos"]);
            $this->conectar();
            $mentorDAO->setConexion($this->conexion);
            $consultasDAO->setConexion($this->conexion);
            $agendaDAO->setConexion($this->conexion);
            $dia = null;
            if (General::tieneValor($agenda, "diaN")){
                $dia = General::$dias[$agenda->diaN + 1];
            }

            $id_mentor = $_POST["id_mentor"];
            $periodoMentor = $mentorDAO->getPeriodoMentor($id_mentor, $agenda->fecha);
            $mentor = $mentorDAO->getMentor($id_mentor);

            if (is_null($periodoMentor)) {
                return General::setRespuesta("0", "No tiene periodo vigente para crear agenda", null);
            }

            /* Extraer la agenda de todos los asistentes tecnicos en el dia y hora agendada */
            $param = new stdClass();
            $param->fecha = $agenda->fecha;
            $param->id_persona2 = $this->user->id_persona;
            $param->hora_inicio = $agenda->hora_inicio;
            $param->hora_fin = $agenda->hora_fin;
            $param->tipo = "MENTORIA";
            $hagendado = $agendaDAO->getAgendaxParam($param);

            if (count($hagendado) > 0) {
                return General::setRespuesta("0", "El horario ya fue tomado por otro emprendedor", null);
            }

            /* Extraer los horarios configurados de los asistentes tecnicos correspondientes al dia */
            //$lista = $asistenciaTecnicaDAO->getHorarioAsistenciaTecnica($dia, $agenda->fecha);
            $lista = $mentorDAO->getHorarioMentor($id_mentor, $dia);

            if (count($lista) == 0) {
                return General::setRespuesta("0", "No existe horarios para esta fecha", null);
            }

            /* Fragmentar los horarios por hora */
            $horasDias = array();
            foreach ($lista as &$diaH) {
                $horasDias = General::dividirHorario($horasDias, $diaH);
            }

            /* Extraer los valores unicos segun dia y hora ejemplo Lunes 08:00, Lunes: 09:00, Lunes: 10:00 ... */
            $horasDiasUniq = array_unique($horasDias, SORT_REGULAR);
            /* Contabilizar cuantos asistentes tecnicos hay asignado para cada hora del dia */
            $horarios = General::contabilizarHorario($horasDiasUniq, $horasDias);
            /* Valida si hay disponibilidad de horario en el dia y hora señalada */
            /* Validar limite max de sesiones por semana */

            $fecha_inicio_semana = General::getInicioSemana(strtotime("$agenda->fecha"));
            $fecha_fin_semana = General::getFinSemana(strtotime("$agenda->fecha"));
            $fecha_inicio_mes = General::getInicioMes(strtotime("$agenda->fecha"));
            if ($fecha_inicio_semana < $fecha_inicio_mes) {
                $fecha_inicio_semana = $fecha_inicio_mes;
            }

            if (!$this->validarMaxSesionesMentor($agendaDAO, $fecha_inicio_semana, $fecha_fin_semana, $mentor->id_persona, $periodoMentor, 1)) {
                return General::setRespuesta("0", "Se han terminado los cupos disponibles para esta semana para este mentor", null);
            }

            /* Validar limite de sesiones por mes */
            if (!$this->validarMaxSesionesMesMentor($agendaDAO, $fecha_inicio_semana, $mentor->id_persona, $periodoMentor, 1)) {
                return General::setRespuesta("0", "Se han terminado los cupos disponibles para este mes para este mentor", null);
            }
            $agenda->id_persona2 = $mentor->id_persona;
            $agenda->id_mentor = $id_mentor;
            $agenda->color = "#3C7EB9";
            $agenda->id_agenda = $agendaDAO->insertarAgenda($agenda);
            $agenda->lugar = $consultasDAO->getParamSystem("UBICACION_EPICO");
        } 
        finally {
            $this->cerrarConexion();
        }

        return General::setRespuestaOK($agenda);
    }
    
    public function getAgendaxPersona2() {
        $respuesta = General::validarParametros($_POST, ["id_persona", "tipo"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $agendaDAO = new AgendaDAO();
        $agendaDAO->setConexion($this->conexion);
        $data = $agendaDAO->getAgendasXIdPersona2($_POST["id_persona"], $_POST["tipo"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    private function validarMaxSesionesMentor($agendaDAO, $fecha_inicio, $fecha_fin, $id_persona, $periodoMentor, $i = 0) {
        $param = new stdClass();
        $param->fecha_inicio = $fecha_inicio;
        $param->fecha_fin = $fecha_fin;
        $param->id_persona2 = $id_persona;
        $param->tipo = "MENTORIA";
        $agendamientos = $agendaDAO->getAgendaxParam($param);
        $cantSesionSemana = count($agendamientos) + $i;
        if ($periodoMentor->max_horas_semana < $cantSesionSemana) {
            return false;
        }
        return true;
    }

    private function validarMaxSesionesMesMentor($agendaDAO, $fecha, $id_persona, $periodoMentor, $i = 0) {
        $param = new stdClass();
        $param->fecha_inicio = General::getInicioMes($fecha);
        $param->fecha_fin = General::getFinMes($fecha);
        $param->id_persona2 = $id_persona;
        $param->tipo = "MENTORIA";
        $agendamientos = $agendaDAO->getAgendaxParam($param);
        $sesionesXmes = count($agendamientos) + $i;
        if ($periodoMentor->max_horas_mes < $sesionesXmes) {
            return false;
        }
        return true;
    }

}
