<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CatalogoBO
 *
 * @author ernesto.ruales
 */
$newUrl = URL::getUrlLibreria();
require_once '../bo/BO.php';
require_once '../util/basedatos.php';
require_once '../dao/CatalogoDAO.php';
require_once '../dao/MentorDAO.php';
require_once '../util/General.php';

class CatalogoBO extends BO {

    public function getNivelEstudio() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getNivelEstudio($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getEtiquetas() {
        $respuesta = General::validarParametros($_POST, ["estado", "tipo"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getEtiquetas($_POST["estado"], $_POST["tipo"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getActividades() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getActividades($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getExperiencia() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getExperiencia($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getProgramas_mercado593() {
        $respuesta = General::validarParametros($_POST, ["idTipo"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getProgramas_mercado593($_POST['idTipo']);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function getCategorias() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getCategorias($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getProgramasxTipoxAnio() {
        $respuesta = General::validarParametros($_POST, ["anio", "id_tipo"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getProgramasxTipoxAnio($_POST["id_tipo"], $_POST["anio"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getGeneros() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getGeneros($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getSituacionLaboral() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getSituacionLaboral($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getTiposEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getTiposEmprendimiento($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getEtapasEmprendimientos() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getEtapasEmprendimientos($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getNivelAcademico() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getNivelAcademico($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getRedSocial() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getRedSocial($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function getLugarComercializacion() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getLugarComercializacion($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getCanalVentas() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getCanalVentas($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getEmpresaDelivery() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getEmpresaDelivery($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getActividadesComplementarias() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getActividadesComplementarias($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getPersonaSocietaria() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getPersonaSocietaria($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getInteresCentroEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getInteresCentroEmprendimiento($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getRazonesNoEmpreder() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getRazonesNoEmpreder($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getEjesMentoria() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getEjesMentoria($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getCiudades() {
        $respuesta = General::validarParametros($_POST, ["pais"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getCiudades($_POST["pais"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getCatalogoEmprendedor($estado) {
        $this->conectar();
        $catalogoDAO = new CatalogoDAO();
        $catalogoDAO->setConexion($this->conexion);
        $data = new stdClass();
        $data->listaGenero = $catalogoDAO->getGeneros($estado);
        //$data->listaPais = $catalogoDAO->getMateriales($estado);
        $data->listaCiudad = $catalogoDAO->getCiudades('EC');
        $data->listaSituacionLaboral = $catalogoDAO->getSituacionLaboral($estado);
        $data->listaTipoEmprendimiento = $catalogoDAO->getTiposEmprendimiento($estado);
        $data->listaEtapaEmprendimiento = $catalogoDAO->getEtapasEmprendimientos($estado);
        $data->listaNivelAcademico = $catalogoDAO->getNivelAcademico($estado);
        $data->listaRedesSociales = $catalogoDAO->getRedSocial($estado);
        $data->listaLugarComercializacion = $catalogoDAO->getLugarComercializacion($estado);
        $data->listaCanalVentas = $catalogoDAO->getCanalVentas($estado);
        $data->listaEmpresaDelivery = $catalogoDAO->getEmpresaDelivery($estado);
        $data->listaActividadesComplentarias = $catalogoDAO->getActividadesComplementarias($estado);
        $data->listaPersonaSocietaria = $catalogoDAO->getPersonaSocietaria($estado);
        $data->listaInteresCentroEmprendimiento = $catalogoDAO->getInteresCentroEmprendimiento($estado);
        $data->listaRazonesNoEmprender = $catalogoDAO->getRazonesNoEmpreder($estado);
        $data->listaEjesMentoria = $catalogoDAO->getEjesMentoria($estado);
        $data->listaEtiquetas = $catalogoDAO->getEtiquetas('A', 'INTERES');
        $this->conexion->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEstadoEmprendedor() {
        $respuesta = General::validarParametrosOR($_POST, ["identificacion", "email", "ruc_rise"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            //$fases = new stdClass();
            $fases = array();

            $parametros = new stdClass();
            $parametros->identificacion = $_POST["identificacion"];
            $parametros->email = $_POST["email"];
            $parametros->ruc_rise = $_POST["ruc_rise"];

            $catalogoDAO->getEstadosEmprendedor($parametros);

            $fase = new stdClass();
            $fase->tipo = "A";
            if (is_null($emp->emprendedor)) {
                $fase->valor = 1;
                $fase->mensaje = "No inscrito";
                $fase->tipo = "R";
                return General::setRespuestaOK($fase);
            } else {
                switch ($emp->emprendedor->estado) {
                    case 'I':
                        $fase->valor = 1;
                        $fase->mensaje = "Inscripcion";
                        return General::setRespuestaOK($fase);
                    case 'R':
                        $fase->valor = 2;
                        $fase->mensaje = "Rechazado";
                        $fase->tipo = "R";
                        return General::setRespuestaOK($fase);
                    case 'A':
                        $fase->valor = 2;
                        $fase->mensaje = "Aprobado";
                        break;
                    default :
                        break;
                }
            }

            if (is_null($emp->emprendedor->id_usuario)) {
                $fase->valor = 3;
                $fase->mensaje = "Usuario <br>no creado";
                return General::setRespuestaOK($fase);
            } else {
                $fase->valor = 3;
                $fase->mensaje = "Usuario <br>Creado";
            }

            if (is_null($emp->emprendimiento)) {
                //$fase->valor = 2; $fase->mensaje = "Formulario 2 33.33%";
            } else {
                switch ($emp->emprendimiento->estado) {
                    case 'I':
                        $fase->valor = 4;
                        $fase->mensaje = "Formulario 2 <br>(33.33%)";
                        return General::setRespuestaOK($fase);
                    case 'P':
                        $fase->valor = 4;
                        $fase->mensaje = "Formulario 2 <br>(66.67%)";
                        return General::setRespuestaOK($fase);
                    case 'C':
                        $fase->valor = 4;
                        $fase->mensaje = "Formulario <br>Completado";
                        break;
                    default :
                        break;
                }
            }

            if (is_null($emp->solicitud)) {
                //$fase[0] = General::setRespuesta(0, "Solicitud generada", NULL);
                return General::setRespuestaOK($fase);
            } else {
                switch ($emp->solicitud->estado) {
                    case 'I':
                        $fase->valor = 5;
                        $fase->mensaje = "Solicitud <br> Mercado 593";
                        break;
                    case 'RM':
                        $fase->valor = 6;
                        $fase->tipo = "R";
                        $fase->mensaje = "Solicitud <br>Rechazada <br>(Pendiente de Usuario)";
                        break;
                    case 'PM':
                        $fase->valor = 6;
                        $fase->mensaje = "Solicitud <br>Pendiente <br>en revision";
                        break;
                    case 'AM':
                        $fase->valor = 6;
                        $fase->mensaje = "Solicitud <br>Pendiente <br>supervisor";
                        break;
                    case 'AS':
                        $fase->valor = 6;
                        $fase->mensaje = "Solicitud <br>Aprobada";
                        break;
                    case 'RS':
                        $fase->valor = 6;
                        $fase->tipo = "R";
                        $fase->mensaje = "Solicitud <br>Rechazada"; // Solicitud Pendiente en revision (pasa a este estado)
                        break;
                    case 'SF':
                        $fase->valor = 7;
                        $fase->mensaje = "";
                        break;
                    default :
                        break;
                }
            }

            return General::setRespuestaOK($fase);
        } 
        catch (Exception $e) {
            return General::setRespuesta("1", "Se ha producido un error", $e->getMessage());
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getEventosEpico() {
        $this->conectar('epico.gob.ec', 'epicoez2_epibase', 'epicoez2_inscrip', 'Ep1c@ pr0Gr@m@s');
        $catalogoDAO = new CatalogoDAO();
        $catalogoDAO->setConexion($this->conexion);
        $fecha_desde = null;
        if(General::tieneValor($_POST, "fecha_desde"))
            $fecha_desde = $_POST["fecha_desde"];
        
        $data = $catalogoDAO->getEventosEpico($fecha_desde);
        foreach ($data as &$event) {
            $fecha = date_create();
            date_timestamp_set($fecha, $event->tstart);
            $event->hora_inicio = date_format($fecha, 'H:i:s');
            date_timestamp_set($fecha, $event->tend);
            $event->hora_fin = date_format($fecha, 'H:i:s');
            $event->start .= "T$event->hora_inicio";
            $event->end .= "T$event->hora_fin";
        }
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function getProgramas() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getProgramas($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getSubPrograma() {
        $respuesta = General::validarParametros($_POST, ["id_programa", "estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getSubProgramaxIdPrograma($_POST["id_programa"], $_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getEtapaxSubPrograma() {
        $respuesta = General::validarParametros($_POST, ["id_sub_programa", "estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getEtapasXSubPrograma($_POST["id_sub_programa"], $_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getAplicaciones() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getAplicaciones($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getTipoActividad() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getTipoActividad($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getActividadesxSubPrograma() {
        $respuesta = General::validarParametros($_POST, ["id_sub_programa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getActividadesxSubPrograma($_POST["id_sub_programa"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getAplicacionesExternas() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getAplicacionExterna($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getTipoEjecucion() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getTipoEjecucion($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getListaNemonicoFile() {
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getListaNemonicoFle();
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getRubricas() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getRubrica($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getTipoEvento() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getTipoEvento($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getEstadoActividad() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getEstadoActividad($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getMotivoCancelar() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getMotivoCancelar($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getTipoAsistencia() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getTipoAsistencia($_POST["estado"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function getInstitucion() {
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getInstitucion();
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function getFeriados() {
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getFeriados();
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getUbicaciones() {
        $respuesta = General::validarParametros($_POST, ["estado", "id_ubicacion_padre"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $catalogoDAO = new CatalogoDAO();
        $catalogoDAO->setConexion($this->conexion);
        $codigo = null;
        if(General::tieneValor($_POST, "id_ubicacion_padre")){
            $codigo = $_POST["id_ubicacion_padre"];
        }
        $data = $catalogoDAO->getUbicaciones($codigo, $_POST["estado"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }
    
    public function getMentores() {
        $respuesta = General::validarParametros($_POST, ["id_eje_mentoria"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $mentorDAO= new MentorDAO();
            $mentorDAO->setConexion($this->conexion);
            $data = $mentorDAO->getMentorPorEjeMentoria($_POST["id_eje_mentoria"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getEdiciones() {
        $respuesta = General::validarParametros($_POST, ["id_sub_programa"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getEdiciones($_POST["id_sub_programa"]);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
    
    public function getNemonicoFile() {
        $respuesta = General::validarParametros($_POST, ["nemonico"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        try {
            $this->conectar();
            $catalogoDAO = new CatalogoDAO();
            $catalogoDAO->setConexion($this->conexion);
            $data = $catalogoDAO->getNemonicoFile($_POST['nemonico']);
            $this->cerrarConexion();
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }
}
