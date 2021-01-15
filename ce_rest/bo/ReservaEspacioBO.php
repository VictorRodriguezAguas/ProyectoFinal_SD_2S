<?php
require_once '../bo/BO.php';
require_once '../util/General.php';
require_once '../dao/ReservaEspacioDAO.php';

class ReservaEspacioBO extends BO
{
    function index($date)
    {
        $fecha = $date;
        $this->conectar();
        $reserva_espacio_dao = new ReservaEspacioDAO();
        $reserva_espacio_dao->setConexion($this->conexion);
        $data = $reserva_espacio_dao->getIndex();
        foreach ($data as $item) {
            $data_set = $reserva_espacio_dao->getDataSetByDate($fecha, $item->id_espacio_tipo);
            $item->data_set = self::formatDataSet($data_set);
        }
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    function formatDataSet($dataset)
    {
        $array = array_pad(array(), 24, 0);
        $array2 = array();
        $pos = 0;

        foreach ($dataset as $item) {
            $start = intval(explode(":", $item->hora_inicio)[0]);
            $end = intval(explode(":", $item->hora_fin)[0]);
            $length = $end - $start;

            for ($i = $start; $i <= $start + $length; $i++) {
                $array[$i] = $array[$i] + 1;
            }
        }
        foreach ($array as $item) {
            //$key = str_pad($pos, 2, "0", STR_PAD_LEFT);
            array_push($array2, array('x' => $pos, 'y' => $item));
            $pos++;
        }
        return $array2;
    }

    function grabaEspacio()
    {
        //todo para cuando se pida mantenededor de espacios
    }

    function getPerson($id_espacio)
    {
        $this->conectar();
        $reserva_espacio_dao = new ReservaEspacioDAO();
        $reserva_espacio_dao->setConexion($this->conexion);
        $data = $reserva_espacio_dao->getPerson($id_espacio);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    function getAddress()
    {
        $this->conectar();
        $reserva_espacio_dao = new ReservaEspacioDAO();
        $reserva_espacio_dao->setConexion($this->conexion);
        $data = $reserva_espacio_dao->getAddress();
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    function indexReservation($id_espacio)
    {
        $this->conectar();
        $reserva_espacio_dao = new ReservaEspacioDAO();
        $reserva_espacio_dao->setConexion($this->conexion);
        $data = $reserva_espacio_dao->getIndexReservation($id_espacio);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function grabarReservacion()
    {
        $respuesta = General::validarParametros($_POST, ["datos"]);

        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $reserva_espacio_dao = new ReservaEspacioDAO();
            $reserva_espacio_dao->setConexion($this->conexion);
            $reserva_espacio = json_decode($_POST["datos"]);
            if (!General::tieneValor($reserva_espacio, "id")) {
                $reserva_espacio->id = $reserva_espacio_dao->storeReservation($reserva_espacio, $this->user->id);
            } else {
                $reserva_espacio_dao->updateReservation($reserva_espacio, $this->user->id);
            }
            $this->cerrarConexion();
            return General::setRespuestaOK($reserva_espacio);
        } finally {
            $this->cerrarConexion();
        }
    }

    function indexAvailable($id_espacio)
    {
        $array = [];
        $this->conectar();
        $reserva_espacio_dao = new ReservaEspacioDAO();
        $reserva_espacio_dao->setConexion($this->conexion);
        $param_days = $reserva_espacio_dao->getParamMaxDaysAvailable();
        $days = intval($param_days[0]->valor);
        $max_available_in_each_place = $reserva_espacio_dao->getMaxAvailableInEachPlace($id_espacio);
        $max = intval($max_available_in_each_place[0]->valor);
        $data = $reserva_espacio_dao->getIndexFutureReservation($id_espacio);
        $current_date = date("Y-m-d");
        for ($i = 0; $i < $days; $i++) {
            $events_day = self::eventsDay($data, $current_date);
            $fill_occupied_place = self::fillOccupiedPlace($events_day);
            $available_place = self::availablePlace($fill_occupied_place, $max);
            $object = self::createObject($available_place);
            $format_object = self::formatObject($object, $current_date);
            foreach ($format_object as $item) {
                array_push($array, $item);
            }
            $current_date = date("Y-m-d", strtotime($current_date . "+ 1 days"));
        }
        $this->cerrarConexion();
        return General::setRespuestaOK($array);
    }

    public function eventsDay($data, $current_date)
    {
        // este metodo crea un nuevo array con todos los eventos de un solo dia
        $array = array();
        foreach ($data as $item) {
            if ($item->fecha_inicio == $current_date) {
                array_push($array, $item);
            }
        }
        return $array;
    }

    public function fillOccupiedPlace($events_day)
    {
        //Este metodo crea 48 slots 1 por cada media hora del dia y los llena segun la cantidad de eventos que existen
        $array = array_pad(array(), 48, 0);
        foreach ($events_day as $item) {
            $start = $item->hora_inicio;

            $end = $item->hora_fin;
            $length = (new DateTime($start))->diff(new DateTime($end));

            $minutes = $length->h * 60 + $length->i;

            $slots = $minutes / 30;

            $explode_start = explode(":", $start);

            $init_slot = $explode_start[1] == '00' ? intval($explode_start[0]) * 2 : (intval($explode_start[0]) * 2) + 1;
            for ($i = $init_slot; $i < ($init_slot + $slots); $i++) {
                $array[$i] = $array[$i] + 1;
            }
        }
        return $array;
    }

    public function availablePlace($fill_occupied_place, $max)
    {
        /*
         * Este metodo crea el inverso ( Resta el maximo declaradon en cupo de la BD
         * de cada espacio con los ocupados
         * y luego crea un array que los llena con los disponibles
         */
        $array = array_pad(array(), 48, 0);
        foreach ($fill_occupied_place as $key => $item) {
            $array[$key] = $max - $item;
        }
        return $array;
    }

    public function createObject($available_place)
    {
        // Este metodo crea el objeto con los rangos de slots disponibles
        $pos = 0;
        $start = 0;
        $end = 0;
        $array = [];
        $continue = false;
        if ($available_place[$start] >= 0) {
            $start = $pos;
            $end = self::findEndPos($start + 1, $available_place);
            if ($end['status']) {
                array_push($array, array(
                    'start' => 0,
                    'end' => $end['pos'],
                    'msg' => $end['F']
                ));
                $continue = true;
            } else {
                array_push($array, array(
                    'start' => 0,
                    'end' => 47,
                    'b' => $end['F']
                ));
                $continue = false;
            }
        }
        if ($continue) {
            do {
                $start = self::findInitPos(($end['pos'] + 1), $available_place);

                $end = self::findEndPos(($start['pos'] + 1), $available_place);
                if ($end['status']) {
                    array_push($array, array(
                        'start' => $start['pos'],
                        'end' => $end['pos']
                    ));
                } else {
                    array_push($array, array(
                        'start' => $start['pos'],
                        'end' => 47
                    ));
                }
                $pos = $end['pos'] + 1;
            } while ($pos < 47);
        }
        return $array;
    }

    public function findInitPos($start, $available_place)
    {
        // Busca el slot inicial para crear un rango
        $flag = count($available_place);
        for ($i = $start; $i < $flag; $i++) {
            if ($available_place[$i] >= 0) {
                return [
                    'status' => true,
                    'pos' => $i
                ];
            }
        }
        return [
            'status' => false,
            'pos' => $i
        ];
    }

    public function findEndPos($start, $available_place)
    {
        // Busca el slot final para crear un rango
        $flag = count($available_place);
        for ($i = $start; $i < $flag; $i++) {
            if ($available_place[$i] < 0) {
                return [
                    'status' => true,
                    'pos' => $i,
                    'F' => 'hay un slot no disponible en pos: '.$i
                ];
            }
        }
        return [
            'status' => false,
            'pos' => $i,
            'F' => 'llego al fin todo esta disponible'
        ];
    }

    public function formatObject($object, $current_date)
    {
        /* Este metodo formatea los slots a un formato de hora
         * Cada Slot lo divide en 2 para crear espacios de media hora
         */
        $array = [];
        $time = $current_date;
        foreach ($object as $item) {
            $h_start = $item['start'] / 2;
            $h_start_rounded= round($h_start, 0, PHP_ROUND_HALF_DOWN);
            $h_start_string = str_pad($h_start_rounded, 2, "0", STR_PAD_LEFT);

            $h_end = $item['end'] / 2;
            $h_end_rounded= round($h_end, 0, PHP_ROUND_HALF_DOWN);
            $h_end_string = str_pad($h_end_rounded, 2, "0", STR_PAD_LEFT);


            if (is_float($h_start)) {
                $start = $time . 'T' . $h_start_string . ':30';
            } else {
                $start = $time . 'T' . $h_start_string . ':00';
            }
            if (is_float($h_end)) {
                $end = $time . 'T' . round($h_end_string, 0, PHP_ROUND_HALF_DOWN) . ':30';
            } else {
                $end = $time . 'T' . $h_end_string . ':00';
            }
            array_push($array, array(
                'groupId' => 'disponibleID',
                'start' => $start,
                'end' => $end,
                'rendering' => 'background',
                'overlap' => true,
                'backgroundColor' => '#4dd636',
                'stick' => true
            ));
        }
        return $array;
    }
}
