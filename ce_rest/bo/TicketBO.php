<?php

/**
 * Description of CatalogoBO
 *
 * @author Mauricio Guzman
 */

$newUrl = URL::getUrlLibreria();
require_once '../bo/BO.php';
require_once '../util/basedatos.php';
require_once '../dao/TicketDAO.php';
require_once '../util/General.php';
require_once '../bo/ArchivosBO.php';
require_once '../dao/ConsultasDAO.php';

class TicketBO extends BO {

    public function getTicketTypes() {
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketTypes();
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getTicketStatus() {
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketStatus();
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getTicketCategories() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $ticket = json_decode($_POST["datos"]);

        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketCategories($ticket->id);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getTicketSubcategories() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $ticket = json_decode($_POST["datos"]);

        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketSubcategories($ticket->id);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getTicketProfile() {
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketProfile();
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }


    public function getTicketsByUser() {
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketsByUser(2);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }


    public function getTicketsByPerfil($usuario){
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketsByPerfil($usuario);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getTicketsByParams(){
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $ticket = json_decode($_POST["datos"]);
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketsByParam($ticket->id_usuario_atencion, 
                $ticket->id_tipo_atencion,
                $ticket->id_categoria,
                $ticket->id_subcategoria,
                $ticket->id_usuario_creacion,
                $ticket->nombre_usuario_creacion,
                $ticket->descripcion);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getCreatedTicketsByParams($usuario){
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $ticket = json_decode($_POST["datos"]);
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getCreatedTicketsByParam($usuario, 
                $ticket->id_tipo_atencion,
                $ticket->id_categoria,
                $ticket->id_subcategoria);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }    

    public function getTicketsByParamsHistorical(){
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $ticket = json_decode($_POST["datos"]);
        try {
            $this->conectar();
            $ticketDAO = new TicketDAO();
            $ticketDAO->setConexion($this->conexion);
            $data = $ticketDAO->getTicketsByParamHistorical($ticket->id_tipo_atencion,
                $ticket->id_categoria,
                $ticket->id_subcategoria,
                $ticket->id_usuario_creacion,
                $ticket->nombre_usuario_creacion,
                $ticket->fecha_toma,
                $ticket->fecha_finalizacion);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }


    public function insertarTicket() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $ticket = json_decode($_POST["datos"]);
        $ticket = $this->grabarTicket($ticket);
        return General::setRespuesta("1", "Se grabó con éxito", $ticket);
    }


    public function grabarTicket($ticket) {

        $ticketDAO = new TicketDAO();

        $this->conectar();
        try {
            $ticketDAO->setConexion($this->conexion);
            $ticket->id_ticket = $ticketDAO->insertarTicket($ticket);
            return $ticket;
        } 
        finally {
            $this->cerrarConexion();
        }
    }


    public function updateTicketByAttended() {   
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $ticketDAO = new TicketDAO();
        $ticket = json_decode($_POST["datos"]);

        $this->conectar();
        try {
            $ticketDAO->setConexion($this->conexion);
            if (General::tieneValor($ticket, "id_ticket")) {
                $ticketDAO->updateTicketByAttended($ticket);
            }
            return General::setRespuesta("1", "Se actualizo con éxito", $ticket);
        } finally {
            $this->cerrarConexion();
        }
    }


    public function doneTicketByAttended() {   
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $ticketDAO = new TicketDAO();
        $ticket = json_decode($_POST["datos"]);
        
        $this->conectar();
        try {
            $ticketDAO->setConexion($this->conexion);
            if (General::tieneValor($ticket, "id_ticket")) {
                $ticketDAO->doneTicketByAttended($ticket);
            }
            return General::setRespuesta("1", "Se finalizo con éxito", $ticket);
        } finally {
            $this->cerrarConexion();
        }
    }


    public function deleteTicketByAttended() {   
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $ticketDAO = new TicketDAO();
        $ticket = json_decode($_POST["datos"]);
        
        $this->conectar();
        try {
            $ticketDAO->setConexion($this->conexion);
            if (General::tieneValor($ticket, "id_ticket")) {
                $ticketDAO->deleteTicketByAttended($ticket);
            }
            return General::setRespuesta("1", "Se elimino con éxito", $ticket);
        } finally {
            $this->cerrarConexion();
        }
    }


    public function saveMeetingFileDos($usuario) {

        try {

            $consultaDAO = new ConsultasDAO();
            $this->conectar();
            $consultaDAO->setConexion($this->conexion);
            if (isset($_FILES['archivos'])) {
                $file = $_FILES['archivos'];
                if (!is_null($file)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nameImage = "archivo_agenda_" . $usuario . "." . $ext;
                    $mensaje = ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                    if($mensaje!='OK')
                        return General::setRespuesta("0", $mensaje, $usuario);
                }
            }

            $url = $consultaDAO->getParamSystem("RUTA_ARCHIVOS_URL");
            return General::setRespuesta("1", "Se grabó con éxito", $url);
        } finally {
            $this->cerrarConexion();
        }
    }

}