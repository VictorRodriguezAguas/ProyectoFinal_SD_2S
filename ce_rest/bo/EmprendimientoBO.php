<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmprendimientoBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/EmprendedorDAO.php';
require_once '../dao/EmprendimientoDAO.php';
require_once '../dao/PersonaDAO.php';
require_once '../dao/UsuarioDAO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../util/General.php';
//require_once '../util/Mail.php';
require_once '../bo/ArchivosBO.php';
require_once '../dao/CorreoDAO.php';
require_once '../dao/PerfilDAO.php';

class EmprendimientoBO extends BO {

    //put your code here
    public function insertarEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $emprendimiento = json_decode($_POST["datos"]);
        try {
            $emprendimiento = $this->grabarEmprendimiento($emprendimiento);
            return General::setRespuesta("1", "Se grabó con éxito", $emprendimiento);
        } catch (Exception $ex) {
            return General::setRespuesta("0", $ex->getMessage(), null);
        }
    }

    public function grabarEmprendimiento($emprendimiento) {

        $emprendimientoDAO = new EmprendimientoDAO();
        $this->conectar();
        try {
            $emprendimientoDAO->setConexion($this->conexion);

            if (!General::tieneValor($emprendimiento, "id_emprendedor")) {
                throw new Exception('Emprededor no asignado');
            }

            if (General::tieneValor($emprendimiento, "id") || General::tieneValor($emprendimiento, "id_emprendimiento")) {
                $emprendimientoDAO->actualizarEmprendimientoEstadoCE($emprendimiento);
            } else {
                $emprendimiento->id = $emprendimientoDAO->insertarEmprendimiento($emprendimiento);
                $emprendimiento->id_emprendimiento = $emprendimiento->id;
                $emprendimientoDAO->asignarEmprendimientoEmprendedor($emprendimiento->id_emprendedor, $emprendimiento->id_emprendimiento);
            }
            
            //return General::setRespuestaOK($emprendedor);
            return $emprendimiento;
        } finally {
            $this->cerrarConexion();
        }
    }

    public function insertarEmprendimientoPorPasos() {
        $respuesta = General::validarParametros($_POST, ["datos", "paso"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $emprendimiento = json_decode($_POST["datos"]);
        $emprendimientoDAO = new EmprendimientoDAO();
        $this->conectar();
        try {
            $emprendimientoDAO->setConexion($this->conexion);

            if (!General::tieneValor($emprendimiento, "id_emprendedor")) {
                return General::setRespuesta("0", "Emprededor no asignado", null);
            }

            switch ($_POST["paso"]) {
                case 1:
                    if (!General::tieneValor($emprendimiento, "id_emprendimiento")) {
                        $emprendimiento->id = $emprendimientoDAO->insertarEmprendimiento($emprendimiento);
                        $emprendimiento->id_emprendimiento = $emprendimiento->id;
                        $emprendimientoDAO->asignarEmprendimientoEmprendedor($emprendimiento->id_emprendedor, $emprendimiento->id_emprendimiento);
                    }
                    if (isset($_FILES['documento'])) {
                        $file = $_FILES['documento'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_nombramiento." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_nombramiento = $nameImage;
                        }
                    }
                    if (isset($_FILES['doc_cuenta_bancaria'])) {
                        $file = $_FILES['doc_cuenta_bancaria'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_certificado_bancario." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_cuenta_bancaria = $nameImage;
                        }
                    }
                    if (isset($_FILES['logo'])) {
                        $file = $_FILES['logo'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_logo." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->logo = $nameImage;
                        }
                    }
                    if (isset($_FILES['banner'])) {
                        $file = $_FILES['banner'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_banner." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->banner = $nameImage;
                        }
                    }

                    if (isset($_FILES['archivo_ci'])) {
                        $file = $_FILES['archivo_ci'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_ci." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_ci = $nameImage;
                        }
                    }
                    if (isset($_FILES['doc_registro_sanitario'])) {
                        $file = $_FILES['doc_registro_sanitario'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_registro_sanitario." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_registro_sanitario = $nameImage;
                        }
                    }
                    if (isset($_FILES['doc_permiso_funcionamiento'])) {
                        $file = $_FILES['doc_permiso_funcionamiento'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_permiso_funcionamiento." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_permiso_funcionamiento = $nameImage;
                        }
                    }
                    if (isset($_FILES['doc_productos'])) {
                        $file = $_FILES['doc_productos'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_productos." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_productos = $nameImage;
                        }
                    }

                    if (isset($_FILES['archivo_ruc_rise'])) {
                        $file = $_FILES['archivo_ruc_rise'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_certificado_rucrise." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_ruc_rise = $nameImage;
                        }
                    }
                    if (isset($_FILES['archivo_iess'])) {
                        $file = $_FILES['archivo_iess'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_iess." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_iess = $nameImage;
                        }
                    }
                    if (isset($_FILES['archivo_impuesto_renta'])) {
                        $file = $_FILES['archivo_impuesto_renta'];
                        if (!is_null($file)) {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nameImage = "emprendimiento_" . $emprendimiento->id_emprendimiento . "_impuesto_renta." . $ext;
                            ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                            $emprendimiento->archivo_impuesto_renta = $nameImage;
                        }
                    }
                    $emprendimientoDAO->actualizarEmprendimientoPaso1($emprendimiento);
                    break;
                case 2:
                    $emprendimientoDAO->actualizarEmprendimientoPaso2($emprendimiento);
                    $emprendimientoDAO->eliminarRedesSocialesEmprendimiento($emprendimiento->id_emprendimiento);
                    foreach ($emprendimiento->listaRedesSociales as &$valor) {
                        if (isset($valor->red)) {
                            if ($valor->red !== "") {
                                $emprendimientoDAO->insertarRedesSociales($emprendimiento->id_emprendimiento, $valor->id, $valor->red);
                            }
                        }
                    }
                    General::setNullSql($emprendimiento, "otro_lugar");
                    $emprendimientoDAO->eliminarLugarComercializacionEmprendimiento($emprendimiento->id_emprendimiento);
                    foreach ($emprendimiento->listaLugarComercializacion as &$valor) {
                        if ($valor->selected) {
                            $valor->id_emprendimiento = $emprendimiento->id_emprendimiento;
                            $emprendimientoDAO->insertarLugarComercializacionEmprendimiento($emprendimiento->id_emprendimiento, $valor->id, $emprendimiento->otro_lugar);
                        }
                    }
                    $emprendimientoDAO->eliminarCanalVentaEmprendimiento($emprendimiento->id_emprendimiento);
                    foreach ($emprendimiento->listaCanalVentas as &$valor) {
                        if ($valor->selected && $valor->id != '1') {
                            $valor->id_emprendimiento = $emprendimiento->id_emprendimiento;
                            $valor->id_canal_venta = $valor->id;
                            $emprendimientoDAO->insertarCanalVentaEmprendimiento($valor);
                        }
                    }

                    if ($emprendimiento->id_canal_venta == "1") {
                        foreach ($emprendimiento->listaEmpresaDelivery as &$valor) {
                            if ($valor->selected) {
                                $valor->id_emprendimiento = $emprendimiento->id_emprendimiento;
                                $valor->id_canal_venta = $emprendimiento->id_canal_venta;
                                $valor->id_empresa_delivery = $valor->id;
                                if ($valor->id == '5' && General::tieneValor($emprendimiento, "otra_empresa")) {
                                    $valor->otra_empresa_delivery = $emprendimiento->otra_empresa;
                                }
                                $emprendimientoDAO->insertarCanalVentaEmprendimiento($valor);
                            }
                        }
                    }
                    break;
                case 3:
                    $emprendimientoDAO->eliminarTipoFinanciamientoEmprendimiento($emprendimiento->id_emprendimiento);
                    foreach ($emprendimiento->listaTipoFinancimientoConvencional as &$valor) {
                        if ($valor->selected) {
                            $emprendimientoDAO->insertarTipoFinanciamientoEmprendimiento($emprendimiento->id_emprendimiento, $valor->id);
                        }
                    }
                    foreach ($emprendimiento->listaTipoFinancimientoNoConvencional as &$valor) {
                        if ($valor->selected) {
                            $emprendimientoDAO->insertarTipoFinanciamientoEmprendimiento($emprendimiento->id_emprendimiento, $valor->id);
                        }
                    }
                    $emprendimientoDAO->actualizarEmprendimientoPaso3($emprendimiento);
                    break;
                    break;
                case 4:
                    $emprendimientoDAO->eliminarActividadComplementariaEmprendimiento($emprendimiento->id_emprendimiento);
                    foreach ($emprendimiento->listaActividadesComplentarias as &$valor) {
                        if ($valor->selected) {
                            $emprendimientoDAO->insertarActividadComplementariaEmprendimiento($emprendimiento->id_emprendimiento, $valor->id);
                        }
                    }
                    break;
                default :
                    break;
            }

            return General::setRespuesta("1", "Se grabó con éxito", $emprendimiento);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

    public function getRedesSocialesEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getRedesSocialesEmprendimiento($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getLugarComercializacionXEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getLugarComercializacionXEmprendimiento($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getCanalesVentasXEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getCanalesVentasXEmprendimiento($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmpresaDeliveryXEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getEmpresaDeliveryXEmprendimiento($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getTipoFinancimientoXEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getTipoFinancimientoXEmprendimiento($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getActividadesComplementariasXEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getActividadesComplementariasXEmprendimiento($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmprendimientoConsulta() {
        $respuesta = General::validarParametros($_POST, ["estado", "id_emprendedor"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getEmprendimientoConsulta($_POST["id_emprendedor"], $_POST["estado"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmprendimientoConsultaXUsuario() {
        $respuesta = General::validarParametros($_POST, ["estado", "nombre", "identificacion", "nombre_emprendimiento", "email"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $perfilDAO = new PerfilDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $perfilDAO->setConexion($this->conexion);

        $id_usuario = $this->user->id;
        $perfil = $perfilDAO->getPerfilxUsuario($this->user->id, "6");
        $perfil2 = $perfilDAO->getPerfilxUsuario($this->user->id, "7");
        if (!is_null($perfil) || !is_null($perfil2)) {
            $id_usuario = "1";
        }

        $data = $emprendimientoDAO->getEmprendimientoConsultaXUsuario($id_usuario, $_POST["estado"], $_POST["nombre"], $_POST["identificacion"], $_POST["nombre_emprendimiento"], $_POST["email"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendimientoDAO = new EmprendimientoDAO();
        $emprendimientoDAO->setConexion($this->conexion);
        $data = $emprendimientoDAO->getEmprendimientoPorId($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

}
