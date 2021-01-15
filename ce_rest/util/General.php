<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of General
 *
 * @author ernesto.ruales
 */
class General {

    public static $dias = array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");

    //put your code here

    public static function tieneValor($data, $campo) {
        if (is_array($data)) {
            if (isset($data[$campo])) {
                if ($data[$campo] !== "") {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            if (isset($data->{$campo})) {
                if ($data->{$campo} !== "") {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public static function setNullSql($data, $campo) {
        if (isset($data->{$campo})) {
            if ($data->{$campo} !== "") {
                if ($data->{$campo} !== "null") {
                    $valor = str_replace("'", "", $data->{$campo});
                    $data->{$campo} = "'" . $valor . "'";
                }
            } else {
                $data->{$campo} = "null";
            }
        } else {
            $data->{$campo} = "null";
        }
    }

    public static function validarParametros($data, $campos) {
        $respuesta = null;
        if (is_array($data)) {
            foreach ($campos as &$campo) {
                if (!isset($data[$campo])) {
                    $respuesta = new stdClass();
                    $respuesta->mensaje = "Faltan parametros";
                    $respuesta->codigo = "0";
                    break;
                }
            }
        } else {
            foreach ($campos as &$campo) {
                if (!isset($data->{$campo})) {
                    $respuesta = new stdClass();
                    $respuesta->mensaje = "Faltan parametros";
                    $respuesta->codigo = "0";
                    break;
                }
            }
        }
        return $respuesta;
    }

    public static function validarParametrosOR($data, $campos) {
        $respuesta = null;
        $existe = false;
        if (is_array($data)) {
            foreach ($campos as &$campo) {
                if (isset($data[$campo])) {
                    $existe = true;
                    break;
                }
            }
        } else {
            foreach ($campos as &$campo) {
                if (isset($data->{$campo})) {
                    $existe = true;
                    break;
                }
            }
        }
        if (!$existe) {
            $respuesta = new stdClass();
            $respuesta->mensaje = "Faltan parametros";
            $respuesta->codigo = "0";
        }
        return $respuesta;
    }

    public static function asignarNull($data, $campo) {
        if (!isset($data[$campo])) {
            $data[$campo] = null;
        }
    }

    public static function asignarNullEntidad($data, $campo) {
        if (!isset($data->{$campo})) {
            $data->{$campo} = null;
        }
    }

    public static function guardarArchivo($fileName, $target_path) {
        /* if (!is_writeable($target_path)) {
          die("$fileName no tiene permisos de escritura");
          } */
        /* if (!is_writeable($target_path)) {
          die("$target_path no tiene permisos de escritura");
          } */
        if (move_uploaded_file($fileName, $target_path))
            print "OK";
        else
            print "Ha ocurrido un error, trate de nuevo!";
    }

    public static function getImagenBase64($fileName) {
        $im = file_get_contents($fileName);
        $imdata = base64_encode($im);
        print $imdata;
    }

    public static function setRespuestaOK($data = null) {
        $respuesta = new stdClass();
        $respuesta->codigo = "1";
        $respuesta->mensaje = "Consulta éxitosa";
        $respuesta->data = $data;
        $respuesta->mensaje_error = "";
        return $respuesta;
    }

    public static function setRespuesta($codigo, $mensaje, $data = null, $mensaje_error = "") {
        $respuesta = new stdClass();
        $respuesta->codigo = $codigo;
        $respuesta->mensaje = $mensaje;
        $respuesta->data = $data;
        $respuesta->mensaje_error = $mensaje_error;
        return $respuesta;
    }

    public static function setRespuestaError(Exception $ex) {
        $respuesta = new stdClass();
        $respuesta->codigo = "0";
        $respuesta->mensaje = "Ha ocurrido un error inesperado. Consulte al administrador de sistemas";
        $respuesta->data = null;
        $respuesta->mensaje_error = $ex->getMessage();
        $respuesta->code_error = $ex->getCode();
        return $respuesta;
    }

    public static function array_remove_object(&$array, $value, $prop) {
        return array_filter($array, function($a) use($value, $prop) {
            return $a->$prop !== $value;
        });
    }

    public static function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }

    public static function copyValorCampoIFNULL($data, $campo, $campo2) {
        if (isset($data->{$campo})) {
            if (is_null($data->{$campo}) || $data->{$campo} === "") {
                $data->{$campo} = $data->{$campo2};
            }
        } else {
            $data->{$campo} = $data->{$campo2};
        }
    }

    public static function insertSQL($tabla, $data, $campos) {
        $respuesta = "INSERT INTO $tabla ( ";

        $i = 0;
        foreach ($campos as &$campo) {
            if (General::tieneValor($data, $campo)) {
                if ($i > 0)
                    $respuesta .= ",";
                $respuesta .= " $campo";
                $i++;
            }
        }
        //$respuesta = substr($respuesta, 0, -1);

        $respuesta .= " ) VALUES (";

        $i = 0;
        foreach ($campos as &$campo) {
            if (General::tieneValor($data, $campo)) {
                if ($i > 0)
                    $respuesta .= ",";
                $respuesta .= " '" . $data->{$campo} . "' ";
                $i++;
            }
        }
        //$respuesta = substr($respuesta, 0, -1);

        $respuesta .= ");";

        return $respuesta;
    }

    public static function updateSQL($tabla, $data, $campos, $filtro) {
        $respuesta = "UPDATE $tabla  SET ";

        $i = 0;
        foreach ($campos as &$campo) {
            if (General::tieneValor($data, $campo)) {
                if ($i > 0)
                    $respuesta .= ",";
                $respuesta .= " $campo = '" . $data->{$campo} . "' ";
                $i++;
            }
        }
        $respuesta = substr($respuesta, 0, -1);

        $respuesta .= " $filtro ";

        return $respuesta;
    }

    public static function findArray($objects, $campo, $value) {
        return array_filter($objects, function($toCheck) use ($value, $campo) {
            return $toCheck->{$campo} == $value;
        });
    }

    public static function dividirHorario($horasDias, $dia) {
        for ($i = intval(str_replace(':', '.', $dia->hora_inicio)); $i < intval(str_replace(':', '.', $dia->hora_fin)); $i++) {
            $hnew = new stdClass();
            $hi = $i;
            $hf = ($i + 1);
            $hnew->hora_inicio = ($hi < 10 ? '0' . $hi : $hi) . ':00:00';
            $hnew->hora_fin = ($hf < 10 ? '0' . $hf : $hf) . ':00:00';
            $hnew->dia = $dia->dia;
            if (General::tieneValor($dia, "fecha"))
                $hnew->fecha = $dia->fecha;
            $hnew->count = 0;
            $horasDias[] = $hnew;
        }
        return $horasDias;
    }

    public static function contabilizarHorario($horasDiasUniq, $horasDias) {
        $horarios = array();
        foreach ($horasDiasUniq as &$horaUni) {
            $horario = General::findArray($horarios, "dia", $horaUni->dia);
            if (count($horario) > 0) {
                $horario = reset($horario);
                $horario->horas[] = $horaUni;
                if (intval(substr($horario->hora_min, 0, 2)) > intval(substr($horaUni->hora_inicio, 0, 2))) {
                    $horario->hora_min = $horaUni->hora_inicio;
                }
                if (intval(substr($horario->hora_max, 0, 2)) < intval(substr($horaUni->hora_fin, 0, 2))) {
                    $horario->hora_max = $horaUni->hora_fin;
                }
            } else {
                $horario = new stdClass();
                $horario->dia = $horaUni->dia;
                $horario->diaN = array_search($horaUni->dia, General::$dias);
                $horario->hora_min = $horaUni->hora_inicio;
                $horario->hora_max = $horaUni->hora_fin;
                $horario->horas = array();
                $horario->horas[] = $horaUni;
                $horarios[] = $horario;
            }
            foreach ($horasDias as &$hora) {
                if ($horaUni->dia == $hora->dia && $horaUni->hora_inicio == $hora->hora_inicio) {
                    $horaUni->count++;
                }
            }
        }
        return $horarios;
    }

    public static function contrainsHorario($horarios) {
        $horariosContrains = array();
        $ejecucion = array();
        foreach ($horarios as &$horario) {
            $horarioCons = new stdClass();
            $horarioCons->dia = $horario->dia;
            $horarioCons->diaN = $horario->diaN;
            $horarioCons->horas = array();
            for ($i = 0; $i < 24; $i++) {
                $hora = General::findArray($horario->horas, "hora_inicio", ($i < 10 ? "0$i:00:00" : "$i:00:00"));
                $ejecucion[] = $hora;
                if (count($hora) == 0) {
                    $hora = new stdClass();
                    $hf = ($i + 1);
                    $hora->hora_inicio = $i < 10 ? "0$i:00:00" : "$i:00:00";
                    if ($hf == 24) {
                        $hora->hora_fin = "00:00:00";
                    } else {
                        $hora->hora_fin = $hf < 10 ? "0$hf:00:00" : "$hf:00:00";
                    }
                    $horarioCons->horas[] = $hora;
                }
            }
            $horariosContrains[] = $horarioCons;
        }
        return $horariosContrains;
    }

    public static function getContrais($horarioDia, $horariosConstrains) {
        $constrains = General::findArray($horariosConstrains, "dia", $horarioDia->dia);
        //return $constrains;
        if (count($constrains) > 0) {
            $constrains = reset($constrains);
            $constrains = $constrains->horas;
        } else {
            if (count($horarioDia->disponibilidad) == 0){
                $constrains = array();
                $hora = new stdClass();
                $hora->hora_inicio = "01:00:00";
                $hora->hora_fin = "23:00:00";
                $constrains[] = $hora;
            }else{
                $constrains = null;
            }
        }
        return $constrains;
    }

    public static function cargarDisponibilidad($horario, $horarioDia, $horasInicio, $llenarDispo = true) {
        $fechaActual = date('Y-m-d');
        $today = date('Y-m-d', strtotime($fechaActual . " + $horasInicio hour"));
        $horaDate = date('H', strtotime(date('Y-m-d H:i:s') . " + $horasInicio hour"));

        foreach ($horario as &$horas) {
            foreach ($horas->horas as &$hora) {
                if ($horarioDia->fecha == $today && intval(str_replace(':00:00', '', $hora->hora_inicio)) <= intval($horaDate)) {
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
                                } 
                                else {
                                    if ($llenarDispo){
                                        $horarioDia->disponibilidad[] = $hora;
                                    }
                                }
                                $band = false;
                                break;
                            }
                        }
                    }
                    if ($band) {
                        if ($llenarDispo){
                            $horarioDia->disponibilidad[] = $hora;
                        }
                    }
                }
                else {
                    if ($llenarDispo){
                        $horarioDia->disponibilidad[] = $hora;
                    }
                }
            }
        }
        return $horarioDia;
    }

    public static function estructuraJerarquica($datos, $camposNiveles, $padre, $camposPadre, $nivel) {
        $respuesta = array();
        $entidad = new stdClass();
        foreach ($datos as &$registro) {
            $igual = true;
            $entidadAux = new stdClass();
            foreach ($camposNiveles[$nivel] as &$campo) {
                $entidadAux->{$campo} = $registro->{$campo};
                if ($registro->{$campo} != $entidad->{$campo}) {
                    $igual = false;
                }
            }
            if (!$igual) {
                $pertenece = true;
                if (!is_null($padre)) {
                    foreach ($camposPadre as &$campo) {
                        if ($registro->{$campo} != $padre->{$campo}) {
                            $pertenece = false;
                        }
                    }
                }
                if ($pertenece)
                    $respuesta[] = $entidad;
                //$entidad = new stdClass();
                $entidad = $entidadAux;
                if ($nivel < count($camposNiveles)) {
                    $entidad->lista = General::estructuraJerarquica($datos, $camposNiveles, $registro, $camposNiveles[$nivel], $nivel + 1);
                }
            }
        }
        $respuesta[] = $entidad;
        return $respuesta;
    }

    public static function getFechaActualH() {
        $date = new DateTime();
        return $date->format('Y-m-d H:i:s');
    }

    public static function getInicioSemana($fecha = null) {
        // Si hoy es lunes, nos daría el lunes pasado.
        if (is_null($fecha)) {
            $fecha = time();
        }
        if (date("D") == "Mon") {
            $week_start = date("Y-m-d");
        } else {
            $week_start = date("Y-m-d", strtotime('last Monday', $fecha));
        }
        return $week_start;
    }

    public static function getFinSemana($fecha = null) {
        if (is_null($fecha)) {
            $fecha = time();
        }
        $week_end = strtotime('next Sunday', $fecha);
        return date('Y-m-d', $week_end);
    }

    public static function getInicioMes($fecha = null) {
        if (is_null($fecha)) {
            $month_start = strtotime('first day of this month', time());
        } else {
            $month_start = strtotime('first day of this month', strtotime($fecha));
        }
        return date('Y-m-d', $month_start);
    }

    public static function getFinMes($fecha = null) {
        if (is_null($fecha)) {
            $month_end = strtotime('last day of this month', time());
        } else {
            $month_end = strtotime('last day of this month', strtotime($fecha));
        }
        return date('Y-m-d', $month_end);
    }

    public static function getDiferencia($fecha1, $fecha2) {
        $datetime1 = date_create($fecha1);
        $datetime2 = date_create($fecha2);
        $contador = date_diff($datetime1, $datetime2);
        $differenceFormat = '%a';
        return $contador->format($differenceFormat);
    }

    public static function getMes($fecha = null) {
        if (is_null($fecha))
            $fechaComoEntero = strtotime($fecha);
        else
            $fechaComoEntero = time();
        return date("m", $fechaComoEntero);
    }

    public static function printD($fecha, $fechaValidar, $texto) {
        if ($fecha == $fechaValidar) {
            print "$texto";
        }
    }
    
    public static function generarArbolHash($lista, $campoPadre){
        $arbol = array();
        foreach ($lista as $item) {
            if(!isset($item->hijos)){
                $item->hijos = array();
            }
            if(General::tieneValor($item, $campoPadre)){
                $itemPadre = $lista[$item->{$campoPadre}];
                if(!isset($itemPadre->hijos)){
                    $itemPadre->hijos = array();
                }
                $itemPadre->hijos[] = $item;
            }
            if(!General::tieneValor($item, $campoPadre)){
                $arbol[] = $item;
            }
        }
        return $arbol;
    }

}
