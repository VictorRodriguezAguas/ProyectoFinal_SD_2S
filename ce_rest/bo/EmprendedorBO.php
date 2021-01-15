<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmprendedorBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/EmprendedorDAO.php';
require_once '../dao/PersonaDAO.php';
require_once '../dao/UsuarioDAO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../util/General.php';
//require_once '../util/Mail.php';
require_once '../bo/ArchivosBO.php';
require_once '../dao/CorreoDAO.php';
require_once '../dao/PerfilDAO.php';
require_once '../dao/SolicitudServicioDAO.php';

class EmprendedorBO extends BO {

    //put your code here
    public function getEmprendedorPorId() {
        $respuesta = General::validarParametros($_POST, ["id_emprendedor"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $emprendedorDAO->setConexion($this->conexion);
        $data = $emprendedorDAO->getEmprendedorPorId($_POST["id_emprendedor"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmprendedorPorIdPersona() {
        $respuesta = General::validarParametros($_POST, ["id_persona"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $emprendedorDAO->setConexion($this->conexion);
        $data = $emprendedorDAO->getEmprendedorPorIdPersona($_POST["id_persona"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function insertarEmprendedor() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $emprendedor = json_decode($_POST["datos"]);
        try {
            $emprendedor = $this->grabarEmprendedor($emprendedor);
            return General::setRespuesta("1", "Se grabó con éxito", $emprendedor);
        } catch (Exception $e) {
            return General::setRespuesta("0", $e->getMessage(), $emprendedor);
        }
    }

    public function grabarEmprendedor($emprendedor) {
        $emprendedorDAO = new EmprendedorDAO();
        $this->conectar();
        try {
            $emprendedorDAO->setConexion($this->conexion);

            if (!General::tieneValor($emprendedor, "id_persona")) {
                throw new Exception('Persona no asignada');
            }

            if (General::tieneValor($emprendedor, "id") || General::tieneValor($emprendedor, "id_emprendedor")) {
                if (!General::tieneValor($emprendedor, "id"))
                    $emprendedor->id = $emprendedor->id_emprendedor;
                if (!General::tieneValor($emprendedor, "estado"))
                    $emprendedor->estado = 'I';
                $emprendedorDAO->actualizarEmprendedor($emprendedor);
            }
            else {
                if (!General::tieneValor($emprendedor, "estado"))
                    $emprendedor->estado = 'I';
                $emp = $emprendedorDAO->getEmprendedorPorIdPersona($emprendedor->id_persona);
                if (!is_null($emp)) {
                    return General::setRespuesta("0", "Ya te encuentras registrado como emprendedor", NULL);
                }
                $emprendedor->id = $emprendedorDAO->insertarEmprendedor($emprendedor);
                $emprendedor->id_emprendedor = $emprendedor->id;
            }

            return $emprendedor;
        } finally {
            $this->cerrarConexion();
        }
    }

    public function actualizarEmprendedor() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $emprendedor = json_decode($_POST["datos"]);
        $emprendedorDAO = new EmprendedorDAO();
        $this->conectar();
        $emprendedorDAO->setConexion($this->conexion);

        $emprendedorDAO->actualizarEmprendedor($emprendedor);

        $personaDAO = new PersonaDAO();
        //$mail = new Mail();
        $personaDAO->setConexion($this->conexion);
        $persona = $personaDAO->getPersonaXId($emprendedor->id_persona);

        $this->cerrarConexion();
        //return General::setRespuestaOK($emprendedor);
        return General::setRespuesta("1", "Se aprobó con éxito", $emprendedor);
    }

    public function getEmprendedoresConsulta() {
        $respuesta = General::validarParametros($_POST, ["estado", "nombre", "identificacion", "nombre_emprendimiento", "email"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $user = Sesion::getUsuarioSesion();
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $perfilDAO = new PerfilDAO();

        $emprendedorDAO->setConexion($this->conexion);
        $perfilDAO->setConexion($this->conexion);

        $id_usuario = $user->id;
        $perfil = $perfilDAO->getPerfilxUsuario($user->id, "6");
        $perfil2 = $perfilDAO->getPerfilxUsuario($user->id, "7");
        if (!is_null($perfil) || !is_null($perfil2)) {
            $id_usuario = "1";
        }

        $data = $emprendedorDAO->getEmprendedorConsulta($_POST["estado"], null, $_POST["nombre"], $id_usuario, $_POST["identificacion"], $_POST["nombre_emprendimiento"], $_POST["email"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function aprobarEmprendedor() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $emprendedor = json_decode($_POST["datos"]);
        //$mail = new Mail();
        $emprendedorDAO = new EmprendedorDAO();
        $usuarioDAO = new UsuarioDAO();
        $consultasDAO = new ConsultasDAO();
        $correoDAO = new CorreoDAO();
        $this->conectar();
        $usuarioDAO->setConexion($this->conexion);
        $emprendedorDAO->setConexion($this->conexion);
        $consultasDAO->setConexion($this->conexion);
        $correoDAO->setConexion($this->conexion);

        $user = Sesion::getUsuarioSesion();

        $usuario = new stdClass();
        $usuario->nombre = $emprendedor->nombre;
        $usuario->apellido = $emprendedor->apellido;
        $usuario->usuario = $emprendedor->email;
        $usuario->correo = $emprendedor->email;
        $usuario->id_institucion = '2';
        $usuario->estado = $emprendedor->estado;
        $usuario->password = $emprendedor->identificacion;

        $emprendedor->id_usuario_aprobacion = $user->id;


        $correo = new stdClass();
        $correo->destinatario = $emprendedor->email;
        $correo->asunto = "Registro Emprendedor";
        $correo->estado = "P";
        if ($emprendedor->estado == 'A') {

            if (!is_null($usuarioDAO->getUsuarioLogin($usuario->usuario, "123"))) {
                return General::setRespuesta('0', 'Ya se encuentra creado el usuario', "");
            }

            $emprendedor->id_usuario = $usuarioDAO->insertarUsuario($usuario);
            $usuarioDAO->insertarUsuarioPerfil($emprendedor->id_usuario, '3');
            //$emprendedor->id_usuario = "5";

            $personaDAO = new PersonaDAO();
            $personaDAO->setConexion($this->conexion);
            $personaDAO->actualizarPersona($emprendedor);
            $emprendedorDAO->actualizarEmprendedor($emprendedor);

            $emprendedor->id_emprendedor = $emprendedor->id;
            $emprendedor->id = $emprendedor->id_emprendimiento;
            $emprendedor->estado = 'I';
            $emprendedorDAO->actualizarEmprendimientoPaso1($emprendedor);
            $emprendedorDAO->eliminarRedesSocialesEmprendimiento($emprendedor->id_emprendimiento);
            foreach ($emprendedor->listaRedesSociales as &$valor) {
                if (isset($valor->red)) {
                    if ($valor->red !== "") {
                        $emprendedorDAO->insertarRedesSociales($emprendedor->id_emprendimiento, $valor->id, $valor->red);
                    }
                }
            }
            $tipoAprobacion = $consultasDAO->getTipoAprobacionXid($emprendedor->id_tipo_aprobacion);

            $correo->tipo = "APROBACION";
            $correo->archivo = "http://epico.gob.ec/archivos/Requisitos_para_inscripcio%CC%81n_Mercado_593_Guayaco.pdf";
            $correo->nombre_archivo = "requerimiento.pdf";
            if (is_null($tipoAprobacion)) {
                $texto_correo = $mail->getCorreoAprobacionEmprendedor($emprendedor->email, $emprendedor->nombre, $emprendedor->apellido, $usuario->usuario, $usuario->password);

                $correo->texto_correo = $texto_correo;
                $correoDAO->insertarCorreo($correo);
            } else {
                $tipoAprobacion->texto_correo = str_replace("<<nombres>>", $emprendedor->nombre, $tipoAprobacion->texto_correo);
                $tipoAprobacion->texto_correo = str_replace("<<apellidos>>", $emprendedor->apellido, $tipoAprobacion->texto_correo);
                $tipoAprobacion->texto_correo = str_replace("<<usuario>>", $usuario->usuario, $tipoAprobacion->texto_correo);
                $tipoAprobacion->texto_correo = str_replace("<<password>>", $usuario->password, $tipoAprobacion->texto_correo);
                $tipoAprobacion->texto_correo = str_replace("<<observacion>>", "", $tipoAprobacion->texto_correo);

                $correo->texto_correo = $tipoAprobacion->texto_correo;
                $correoDAO->insertarCorreo($correo);
                //$mail->getCorreoAprobacionEmprendedorPersonalizado($emprendedor->email, $tipoAprobacion->texto_correo);
                //$mail->getCorreoAprobacionEmprendedor($emprendedor->email, $emprendedor->nombre, $emprendedor->apellido, $usuario->usuario, $usuario->password);
            }
        } else {
            $personaDAO = new PersonaDAO();
            $personaDAO->setConexion($this->conexion);
            $personaDAO->actualizarPersona($emprendedor);
            $emprendedorDAO->actualizarEmprendedor($emprendedor);
            //$texto_correo = $mail->getCorreoRechazoEmprendedor($emprendedor->email, $emprendedor->nombre, $emprendedor->apellido);

            $correo->texto_correo = $texto_correo;
            $correo->tipo = "RECHAZO";
            $correoDAO->insertarCorreo($correo);
        }
        $this->cerrarConexion();
        //return General::setRespuestaOK($emprendedor);
        return General::setRespuesta("1", "Se aprobó con éxito", $emprendedor);
    }

    public function getEmprendimientoConsulta() {
        $respuesta = General::validarParametros($_POST, ["estado", "id_emprendedor"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $emprendedorDAO->setConexion($this->conexion);
        $data = $emprendedorDAO->getEmprendimientoConsulta($_POST["id_emprendedor"], $_POST["estado"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmprendimientoConsultaXUsuario() {
        $user = Sesion::getUsuarioSesion();
        $respuesta = General::validarParametros($_POST, ["estado", "nombre", "identificacion", "nombre_emprendimiento", "email"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $perfilDAO = new PerfilDAO();
        $emprendedorDAO->setConexion($this->conexion);
        $perfilDAO->setConexion($this->conexion);

        $id_usuario = $user->id;
        $perfil = $perfilDAO->getPerfilxUsuario($user->id, "6");
        $perfil2 = $perfilDAO->getPerfilxUsuario($user->id, "7");
        if (!is_null($perfil) || !is_null($perfil2)) {
            $id_usuario = "1";
        }

        $data = $emprendedorDAO->getEmprendimientoConsultaXUsuario($id_usuario, $_POST["estado"], $_POST["nombre"], $_POST["identificacion"], $_POST["nombre_emprendimiento"], $_POST["email"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getHorarioXEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $emprendedorDAO->setConexion($this->conexion);
        $data = $emprendedorDAO->getHorarioXEmprendimiento($_POST["id_emprendimiento"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function actualizarEmprendimiento() {
        $respuesta = General::validarParametros($_POST, ["datos", "paso"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $emprendimiento = json_decode($_POST["datos"]);
        $emprendedorDAO = new EmprendedorDAO();
        $solicitudDAO = new SolicitudServicioDAO();
        $correoDAO = new CorreoDAO();
        //$mail = new Mail();
        $this->conectar();
        $emprendedorDAO->setConexion($this->conexion);
        $solicitudDAO->setConexion($this->conexion);
        $correoDAO->setConexion($this->conexion);

        switch ($_POST["paso"]) {
            case "1":
                $emprendedorDAO->actualizarEmprendimientoPaso1($emprendimiento);
                $emprendedorDAO->eliminarRedesSocialesEmprendimiento($emprendimiento->id);
                foreach ($emprendimiento->listaRedesSociales as &$valor) {
                    if (isset($valor->red)) {
                        if ($valor->red !== "") {
                            $emprendedorDAO->insertarRedesSociales($emprendimiento->id, $valor->id, $valor->red);
                        }
                    }
                }
                break;
            case "2":
                if (isset($_FILES['producto_imagen_1'])) {
                    $file = $_FILES['producto_imagen_1'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id_emprendedor . "_" . $emprendimiento->id . "_producto1." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->foto_producto1 = $nameImage;
                    }
                }
                if (isset($_FILES['producto_imagen_2'])) {
                    $file = $_FILES['producto_imagen_2'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id_emprendedor . "_" . $emprendimiento->id . "_producto2." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->foto_producto2 = $nameImage;
                    }
                }

                $emprendedorDAO->actualizarEmprendimientoPaso2($emprendimiento);

                $emprendedorDAO->eliminarTipoFinanciamientoEmprendimiento($emprendimiento->id);
                foreach ($emprendimiento->listaTipoFinancimientoConvencional as &$valor) {
                    if ($valor->selected && $valor->id != '1') {
                        $emprendedorDAO->insertarTipoFinanciamientoEmprendimiento($emprendimiento->id, $valor->id);
                    }
                }
                foreach ($emprendimiento->listaTipoFinancimientoNoConvencional as &$valor) {
                    if ($valor->selected && $valor->id != '1') {
                        $emprendedorDAO->insertarTipoFinanciamientoEmprendimiento($emprendimiento->id, $valor->id);
                    }
                }

                $emprendedorDAO->eliminarCanalVentaEmprendimiento($emprendimiento->id);
                foreach ($emprendimiento->listaCanalVentas as &$valor) {
                    if ($valor->selected && $valor->id != '1') {
                        $valor->id_emprendimiento = $emprendimiento->id;
                        $valor->id_canal_venta = $valor->id;
                        $emprendedorDAO->insertarCanalVentaEmprendimiento($valor);
                    }
                }

                if ($emprendimiento->id_canal_venta == "1") {
                    foreach ($emprendimiento->listaEmpresaDelivery as &$valor) {
                        if ($valor->selected) {
                            $valor->id_emprendimiento = $emprendimiento->id;
                            $valor->id_canal_venta = $emprendimiento->id_canal_venta;
                            $valor->id_empresa_delivery = $valor->id;
                            if ($valor->id === '5') {
                                $valor->otra_empresa_delivery = $emprendimiento->otra_empresa;
                            }
                            $emprendedorDAO->insertarCanalVentaEmprendimiento($valor);
                        }
                    }
                }

                General::setNullSql($emprendimiento, "otro_lugar");
                $emprendedorDAO->eliminarLugarComercializacionEmprendimiento($emprendimiento->id);
                foreach ($emprendimiento->listaLugarComercializacion as &$valor) {
                    if ($valor->selected) {
                        $valor->id_emprendimiento = $emprendimiento->id;
                        $emprendedorDAO->insertarLugarComercializacionEmprendimiento($emprendimiento->id, $valor->id, $emprendimiento->otro_lugar);
                    }
                }

                $emprendedorDAO->eliminarActividadComplementariaEmprendimiento($emprendimiento->id);
                foreach ($emprendimiento->listaActividadesComplentarias as &$valor) {
                    if ($valor->selected) {
                        $emprendedorDAO->insertarActividadComplementariaEmprendimiento($emprendimiento->id, $valor->id);
                    }
                }
                break;
            case "3":

                if (isset($_FILES['documento'])) {
                    $file = $_FILES['documento'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_nombramiento." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_nombramiento = $nameImage;
                    }
                }
                if (isset($_FILES['doc_cuenta_bancaria'])) {
                    $file = $_FILES['doc_cuenta_bancaria'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_certificado_bancario." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_cuenta_bancaria = $nameImage;
                    }
                }
                if (isset($_FILES['logo'])) {
                    $file = $_FILES['logo'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_logo." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->logo = $nameImage;
                    }
                }
                if (isset($_FILES['banner'])) {
                    $file = $_FILES['banner'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_banner." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->banner = $nameImage;
                    }
                }

                if (isset($_FILES['archivo_ci'])) {
                    $file = $_FILES['archivo_ci'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_ci." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_ci = $nameImage;
                    }
                }
                if (isset($_FILES['doc_registro_sanitario'])) {
                    $file = $_FILES['doc_registro_sanitario'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_registro_sanitario." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_registro_sanitario = $nameImage;
                    }
                }
                if (isset($_FILES['doc_permiso_funcionamiento'])) {
                    $file = $_FILES['doc_permiso_funcionamiento'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_permiso_funcionamiento." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_permiso_funcionamiento = $nameImage;
                    }
                }
                if (isset($_FILES['doc_productos'])) {
                    $file = $_FILES['doc_productos'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_productos." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_productos = $nameImage;
                    }
                }

                if (isset($_FILES['archivo_ruc_rise'])) {
                    $file = $_FILES['archivo_ruc_rise'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_certificado_rucrise." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_ruc_rise = $nameImage;
                    }
                }
                if (isset($_FILES['archivo_iess'])) {
                    $file = $_FILES['archivo_iess'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_iess." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_iess = $nameImage;
                    }
                }
                if (isset($_FILES['archivo_impuesto_renta'])) {
                    $file = $_FILES['archivo_impuesto_renta'];
                    if (!is_null($file)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $nameImage = "emprendimiento_" . $emprendimiento->id . "_impuesto_renta." . $ext;
                        ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                        $emprendimiento->archivo_impuesto_renta = $nameImage;
                    }
                }

                $emprendedorDAO->actualizarEmprendimientoPaso3($emprendimiento);

                $emprendedorDAO->eliminarHorarioEmprendimiento($emprendimiento->id);
                foreach ($emprendimiento->listaHorario as &$valor) {
                    if ($valor->selected) {
                        $valor->id_emprendimiento = $emprendimiento->id;
                        $emprendedorDAO->insertarHorarioEmprendimiento($valor);
                    }
                }

                $solicitud = $solicitudDAO->getSolicitudServicioXidEmprendimiento($emprendimiento->id, 'RM', '1');
                if (!is_null($solicitud)) {
                    $solicitud->estado = 'PM';
                    $solicitud->fecha_aprobacion_rechazo = null;
                    $solicitudDAO->actualizarEstadoSolicitud($solicitud);
                    $correo = new stdClass();
                    $correo->destinatario = 'mesadeservicio@epico.gob.ec';
                    $correo->asunto = "Formulario completado";
                    $correo->estado = "P";
                    $correo->tipo = "FORMULARIO COMPLETADO";

                    $datos = new stdClass();
                    $emprendedor = $emprendedorDAO->getEmprendedorPorId($emprendimiento->id_emprendedor);
                    $datos->nombre = $emprendedor->nombre;
                    $datos->apellido = $emprendedor->apellido;
                    $datos->email = $emprendedor->email;
                    $datos->nombre_emprendimiento = $emprendimiento->nombre_emprendimiento;
                    $datos->identificacion = $emprendedor->identificacion;
                    $datos->ruc = $emprendimiento->ruc_rise;
                    //$correo->texto_correo = $mail->getCorreoAMesaServicio($datos);
                    $correoDAO->insertarCorreo($correo);
                }

                break;
            default:
                break;
        }


        $this->cerrarConexion();
        //return General::setRespuestaOK($emprendimiento);
        return General::setRespuesta("1", "La información ingresada se actualizo con éxito", $emprendimiento);
    }

    public function actualizarEmprendimientoDocumentos() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $emprendimiento = json_decode($_POST["datos"]);
        $emprendedorDAO = new EmprendedorDAO();
        $this->conectar();
        $emprendedorDAO->setConexion($this->conexion);

        if (isset($_FILES['documento'])) {
            $file = $_FILES['documento'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_nombramiento." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_nombramiento = $nameImage;
            }
        }
        if (isset($_FILES['doc_cuenta_bancaria'])) {
            $file = $_FILES['doc_cuenta_bancaria'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_certificado_bancario." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_cuenta_bancaria = $nameImage;
            }
        }
        if (isset($_FILES['archivo_ci'])) {
            $file = $_FILES['archivo_ci'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_ci." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_ci = $nameImage;
            }
        }
        if (isset($_FILES['doc_registro_sanitario'])) {
            $file = $_FILES['doc_registro_sanitario'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_registro_sanitario." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_registro_sanitario = $nameImage;
            }
        }
        if (isset($_FILES['doc_permiso_funcionamiento'])) {
            $file = $_FILES['doc_permiso_funcionamiento'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_permiso_funcionamiento." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_permiso_funcionamiento = $nameImage;
            }
        }
        if (isset($_FILES['doc_productos'])) {
            $file = $_FILES['doc_productos'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_productos." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_productos = $nameImage;
            }
        }
        if (isset($_FILES['archivo_ruc_rise'])) {
            $file = $_FILES['archivo_ruc_rise'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_certificado_rucrise." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_ruc_rise = $nameImage;
            }
        }
        if (isset($_FILES['archivo_iess'])) {
            $file = $_FILES['archivo_iess'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_iess." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_iess = $nameImage;
            }
        }
        if (isset($_FILES['archivo_impuesto_renta'])) {
            $file = $_FILES['archivo_impuesto_renta'];
            if (!is_null($file)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $nameImage = "emprendimiento_" . $emprendimiento->id . "_impuesto_renta." . $ext;
                ArchivosBO::guardarArchivo($file['tmp_name'], $nameImage);
                $emprendimiento->archivo_impuesto_renta = $nameImage;
            }
        }

        $url = ArchivosBO::getURLArchivo();

        $emprendedorDAO->actualizarEmprendimientoDocumentos($emprendimiento);

        $emprendimiento->archivo_nombramiento = str_replace("'", "", $emprendimiento->archivo_nombramiento);
        $emprendimiento->archivo_nombramiento = str_replace("null", "", $emprendimiento->archivo_nombramiento);
        $emprendimiento->archivo_cuenta_bancaria = str_replace("'", "", $emprendimiento->archivo_cuenta_bancaria);
        $emprendimiento->archivo_cuenta_bancaria = str_replace("null", "", $emprendimiento->archivo_cuenta_bancaria);
        $emprendimiento->archivo_ci = str_replace("'", "", $emprendimiento->archivo_ci);
        $emprendimiento->archivo_ci = str_replace("null", "", $emprendimiento->archivo_ci);
        $emprendimiento->archivo_registro_sanitario = str_replace("'", "", $emprendimiento->archivo_registro_sanitario);
        $emprendimiento->archivo_registro_sanitario = str_replace("null", "", $emprendimiento->archivo_registro_sanitario);
        $emprendimiento->archivo_permiso_funcionamiento = str_replace("'", "", $emprendimiento->archivo_permiso_funcionamiento);
        $emprendimiento->archivo_permiso_funcionamiento = str_replace("null", "", $emprendimiento->archivo_permiso_funcionamiento);
        $emprendimiento->archivo_productos = str_replace("'", "", $emprendimiento->archivo_productos);
        $emprendimiento->archivo_productos = str_replace("null", "", $emprendimiento->archivo_productos);
        $emprendimiento->archivo_ruc_rise = str_replace("'", "", $emprendimiento->archivo_ruc_rise);
        $emprendimiento->archivo_ruc_rise = str_replace("null", "", $emprendimiento->archivo_ruc_rise);
        $emprendimiento->archivo_iess = str_replace("'", "", $emprendimiento->archivo_iess);
        $emprendimiento->archivo_iess = str_replace("null", "", $emprendimiento->archivo_iess);
        $emprendimiento->archivo_impuesto_renta = str_replace("'", "", $emprendimiento->archivo_impuesto_renta);
        $emprendimiento->archivo_impuesto_renta = str_replace("null", "", $emprendimiento->archivo_impuesto_renta);

        $emprendimiento->url_archivo_nombramiento = $emprendimiento->archivo_nombramiento != '' ? $url . $emprendimiento->archivo_nombramiento : null;
        $emprendimiento->url_archivo_cuenta_bancaria = $emprendimiento->archivo_cuenta_bancaria != '' ? $url . $emprendimiento->archivo_cuenta_bancaria : null;
        $emprendimiento->url_archivo_ci = $emprendimiento->archivo_ci != '' ? $url . $emprendimiento->archivo_ci : null;
        $emprendimiento->url_archivo_registro_sanitario = $emprendimiento->archivo_registro_sanitario != '' ? $url . $emprendimiento->archivo_registro_sanitario : null;
        $emprendimiento->url_archivo_permiso_funcionamiento = $emprendimiento->archivo_permiso_funcionamiento != '' ? $url . $emprendimiento->archivo_permiso_funcionamiento : null;
        $emprendimiento->url_archivo_productos = $emprendimiento->archivo_productos != '' ? $url . $emprendimiento->archivo_productos : null;
        $emprendimiento->url_archivo_ruc_rise = $emprendimiento->archivo_ruc_rise != '' ? $url . $emprendimiento->archivo_ruc_rise : null;
        $emprendimiento->url_archivo_iess = $emprendimiento->archivo_iess != '' ? $url . $emprendimiento->archivo_iess : null;
        $emprendimiento->url_archivo_impuesto_renta = $emprendimiento->archivo_impuesto_renta != '' ? $url . $emprendimiento->archivo_impuesto_renta : null;


        $this->cerrarConexion();
        return General::setRespuesta("1", "Documentos actualizados con éxito", $emprendimiento);
    }

    public function asignacionEmprededorAutomatico() {
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $usuarioDAO = new UsuarioDAO();
        $emprendedorDAO->setConexion($this->conexion);
        $usuarioDAO->setConexion($this->conexion);
        $emprededores = $emprendedorDAO->getEmprendedorNoAsignado();
        $usuarios = $usuarioDAO->getUsuarioXidPerfil("5");
        $cant = count($usuarios);
        $i = 0;
        foreach ($emprededores as &$valor) {
            if ($i >= $cant) {
                $i = 0;
            }
            $emprendedorDAO->asignarEmprededoraUsuario($valor->id_emprendedor, $usuarios[$i]->id);
            $i++;
        }
        $this->cerrarConexion();
        return General::setRespuestaOK("Asignados correctamente");
    }

    public function generarCorreoEmprendedores() {
        $this->conectar();
        //$mail = new Mail();
        $emprendedorDAO = new EmprendedorDAO();
        $correoDAO = new CorreoDAO();
        $consultasDAO = new ConsultasDAO();
        $emprendedorDAO->setConexion($this->conexion);
        $correoDAO->setConexion($this->conexion);
        $consultasDAO->setConexion($this->conexion);
        $lista = $emprendedorDAO->getEmprendedoresParaCorreo();
        foreach ($lista as &$emprendedor) {
            $correo = new stdClass();
            $correo->destinatario = $emprendedor->email;
            $correo->asunto = "Registro Emprendedor";
            $correo->estado = "P";
            if ($emprendedor->estado == 'A') {
                $correo->tipo = "APROBACION";
                $correo->archivo = "http://epico.gob.ec/archivos/Requisitos_para_inscripcio%CC%81n_Mercado_593_Guayaco.pdf";
                $correo->nombre_archivo = "requerimiento.pdf";
                $tipoAprobacion = null;
                if (!is_null($emprendedor->id_tipo_aprobacion)) {
                    $tipoAprobacion = $consultasDAO->getTipoAprobacionXid($emprendedor->id_tipo_aprobacion);
                }
                if (is_null($tipoAprobacion)) {
                    //$texto_correo = $mail->getCorreoAprobacionEmprendedor($emprendedor->email, $emprendedor->nombre, $emprendedor->apellido, $emprendedor->usuario, $emprendedor->identificacion);

                    $correo->texto_correo = $texto_correo;
                    $correoDAO->insertarCorreo($correo);
                } else {
                    $tipoAprobacion->texto_correo = str_replace("<<nombres>>", $emprendedor->nombre, $tipoAprobacion->texto_correo);
                    $tipoAprobacion->texto_correo = str_replace("<<apellidos>>", $emprendedor->apellido, $tipoAprobacion->texto_correo);
                    $tipoAprobacion->texto_correo = str_replace("<<usuario>>", $emprendedor->usuario, $tipoAprobacion->texto_correo);
                    $tipoAprobacion->texto_correo = str_replace("<<password>>", $emprendedor->identificacion, $tipoAprobacion->texto_correo);
                    $tipoAprobacion->texto_correo = str_replace("<<observacion>>", "", $tipoAprobacion->texto_correo);

                    $correo->texto_correo = $tipoAprobacion->texto_correo;
                    $correoDAO->insertarCorreo($correo);
                }
            } else {
                //$texto_correo = $mail->getCorreoRechazoEmprendedor($emprendedor->email, $emprendedor->nombre, $emprendedor->apellido);
                $correo->texto_correo = $texto_correo;
                $correo->tipo = "RECHAZO";
                $correoDAO->insertarCorreo($correo);
            }
        }
        $this->cerrarConexion();
        return General::setRespuesta("1", "Generado correctamente", "");
    }

    public function generarCorreoEmprendimientoMercado593() {
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $emprendedorDAO->setConexion($this->conexion);

        $lista = $emprendedorDAO->getEmprendimientosAprobadosMercado593();

        foreach ($lista as &$emprendedor) {
            $this->generarCorreoMercado583($emprendedor);
        }
        $this->cerrarConexion();
        return General::setRespuesta("1", "Generado correctamente", "");
    }

    public function generarCorreoMercado583($emprendedor) {
        $correoDAO = new CorreoDAO();
        $correoDAO->setConexion($this->conexion);
        //$mail = new Mail();
        $archivos = array();
        $i = 0;
        if (!is_null($emprendedor->archivo_ci)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_ci;
            $file->nombre = $emprendedor->archivo_ci;
            $archivos[$i] = $file;
            $i++;
        }

        if (!is_null($emprendedor->archivo_cuenta_bancaria)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_cuenta_bancaria;
            $file->nombre = $emprendedor->archivo_cuenta_bancaria;
            $archivos[$i] = $file;
            $i++;
        }

        if (!is_null($emprendedor->archivo_iess)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_iess;
            $file->nombre = $emprendedor->archivo_iess;
            $archivos[$i] = $file;
        }

        if (!is_null($emprendedor->archivo_impuesto_renta)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_impuesto_renta;
            $file->nombre = $emprendedor->archivo_impuesto_renta;
            $archivos[$i] = $file;
            $i++;
        }

        if (!is_null($emprendedor->archivo_nombramiento)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_nombramiento;
            $file->nombre = $emprendedor->archivo_nombramiento;
            $archivos[$i] = $file;
            $i++;
        }

        if (!is_null($emprendedor->archivo_permiso_funcionamiento)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_permiso_funcionamiento;
            $file->nombre = $emprendedor->archivo_permiso_funcionamiento;
            $archivos[$i] = $file;
            $i++;
        }

        if (!is_null($emprendedor->archivo_productos)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_productos;
            $file->nombre = $emprendedor->archivo_productos;
            $archivos[$i] = $file;
            $i++;
        }

        if (!is_null($emprendedor->archivo_registro_sanitario)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_registro_sanitario;
            $file->nombre = $emprendedor->archivo_registro_sanitario;
            $archivos[$i] = $file;
            $i++;
        }

        if (!is_null($emprendedor->archivo_ruc_rise)) {
            $file = new stdClass();
            $file->archivo = $emprendedor->archivo_ruc_rise;
            $file->nombre = $emprendedor->archivo_ruc_rise;
            $archivos[$i] = $file;
            $i++;
        }

        $fileZip = 'Mercado593/' . $emprendedor->ruc_rise . ".zip";
        ArchivosBO::crearZip($fileZip, $archivos);

        $correo = new stdClass();
        //$correo->texto_correo = $mail->getCorreoAprobacionMercado593();
        $correo->texto_correo = str_replace("<<ruc>>", $emprendedor->ruc_rise, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<razon_social>>", $emprendedor->razon_social, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<nombre_comercial>>", $emprendedor->nombre_comercial, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<direccion>>", $emprendedor->direccion, $correo->texto_correo);
        if (is_null($emprendedor->id_rubrica)) {
            $correo->texto_correo = str_replace("<<tipo>>", 'Tienda', $correo->texto_correo);
        } else {
            if ($emprendedor->id_rubrica == '1') {
                $correo->texto_correo = str_replace("<<tipo>>", 'Restaurante', $correo->texto_correo);
            } else {
                $correo->texto_correo = str_replace("<<tipo>>", 'Tienda', $correo->texto_correo);
            }
        }
        $correo->texto_correo = str_replace("<<nombre>>", $emprendedor->nombre, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<apellido>>", $emprendedor->apellido, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<cedula>>", $emprendedor->identificacion, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<email>>", $emprendedor->email, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<celular>>", $emprendedor->telefono, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<boton_pago>>", $emprendedor->desea_boton_pago, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<whatsapp>>", "NO", $correo->texto_correo);
        $correo->texto_correo = str_replace("<<delivery>>", $emprendedor->desea_integrar_delivery, $correo->texto_correo);
        $correo->texto_correo = str_replace("<<link>>", "https://epico.gob.ec/archivos/" . $fileZip, $correo->texto_correo);

        $correo->destinatario = $emprendedor->email . ';soporte@contifico.com;ernesto.ruales@epico.gob.ec;david.ponce@epico.gob.ec';
        $correo->archivo = $fileZip;
        $correo->nombre_archivo = $emprendedor->ruc_rise . ".zip";
        if (is_null($emprendedor->nombre_comercial)) {
            $correo->asunto = "Creación de Cuenta Mercado593 - Épico: " . $emprendedor->razon_social;
        } else {
            $correo->asunto = "Creación de Cuenta Mercado593 - Épico: " . $emprendedor->nombre_comercial;
        }
        $correo->estado = "P";
        $correo->tipo = "SOLICITUD MERCADO 593 APROBADO";

        $correoDAO->insertarCorreo($correo);
    }

    public function faltaInformacionForm() {
        $respuesta = General::validarParametros($_POST, ["id_emprendimiento", "id_solicitud_servicio", "observacion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }

        $emprendedorDAO = new EmprendedorDAO();
        $solicitudDAO = new SolicitudServicioDAO();
        $this->conectar();
        $emprendedorDAO->setConexion($this->conexion);
        $solicitudDAO->setConexion($this->conexion);

        $emprendedorDAO->actualizarEstadoEmprendimiento($_POST["id_emprendimiento"], "P");
        $sol = new stdClass();
        $sol->id = $_POST["id_solicitud_servicio"];
        $sol->estado = 'PM';
        $sol->observacion = $_POST["observacion"];

        $solicitudDAO->actualizarEstadoSolicitud($sol);

        $this->cerrarConexion();
        return General::setRespuesta("1", "El estado fue actualizado con éxito", null);
    }

    public function getEmprendedoresCE() {
        $respuesta = General::validarParametrosOR($_POST, ["nombre", "inicial"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $personaDAO = new PersonaDAO();

        $emprendedorDAO->setConexion($this->conexion);
        $personaDAO->setConexion($this->conexion);

        $data = $emprendedorDAO->getEmprendedoresCE($_POST["nombre"], $_POST["inicial"]);
        foreach ($data as &$per) {
            $per->redes_sociales = $personaDAO->getRedesSocialesPersona($per->id);
        }
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmprendedores() {
        $respuesta = General::validarParametrosOR($_POST, ["parametros"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $emprendedorDAO = new EmprendedorDAO();
        $parametros = json_decode($_POST["parametros"]);
        if (is_null($parametros)) {
            $parametros = new stdClass();
        }

        $emprendedorDAO->setConexion($this->conexion);

        $data = $emprendedorDAO->getEmprendedores($parametros);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getEmprendedorAT($id_emprendedor=null) {
        if (is_null($id_emprendedor)) {
            $respuesta = General::validarParametrosOR($_POST, ["id_persona"]);
            if (!is_null($respuesta)) {
                return $respuesta;
            }
            $id_emprendedor = $_POST["persona"];
        }
        try {
            $this->conectar();
            $emprendedorDAO = new EmprendedorDAO();
            $personaDAO = new PersonaDAO();
            $emprendedorDAO->setConexion($this->conexion);
            $personaDAO->setConexion($this->conexion);

            $data = $emprendedorDAO->getEmprendedorAT($id_emprendedor);
            if(!is_null($data)){
                $data->redes_sociales = $personaDAO->getRedesSocialesPersona($id_emprendedor);
                $data->intereses = $personaDAO->getInteresPersona($id_emprendedor);
            }
            return General::setRespuestaOK($data);
        } 
        finally {
            $this->cerrarConexion();
        }
    }

}
