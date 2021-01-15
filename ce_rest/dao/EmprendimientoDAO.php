<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmprendimientoDAO
 *
 * @author ernesto.ruales
 */
class EmprendimientoDAO {

    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function actualizarEmprendimientoEstadoCE($emprendimiento) {
        General::setNullSql($emprendimiento, "estado_ce");
        General::setNullSql($emprendimiento, "is_ce");
        $sql = "UPDATE emprendimiento set is_ce = $emprendimiento->is_ce, estado_ce = $emprendimiento->estado_ce 
                where id = '$emprendimiento->id_emprendimiento'";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function insertarEmprendimiento($emprendimiento) {
        if(General::tieneValor($emprendimiento, "nombre_emprendimiento"))
            $emprendimiento->nombre = $emprendimiento->nombre_emprendimiento;
        
        $campos=array("id_etapa_emprendimiento","emprendimiento_formalizado", "nombre", "cant_socios", "ruc_rise",
            "razon_social", "id_tipo_emprendimiento", "venta_mensual", "ganancia_anual", "cant_tipo_producto", 
            "utiliza_plataforma_electronica", "posee_cuenta_bancaria", "realizado_prestado", "persona", 
            "id_tipo_persona_societaria", "otra_persona_societaria", "producto", "descripcion_emprendimiento", 
            "foto_producto1", "foto_producto2", "opera_ruc_rise", "dispuesto_obtener_ruc_rise", 
            "numero_labora", "direccion", "direccion_url", "estado_ce", "is_ce", "is_mercado", "completado", 
            "estado", "telefono_whatsapp", "email", "nombre_comercial", "archivo_ci", 
            "archivo_permiso_funcionamiento", "archivo_registro_sanitario", "archivo_productos", 
            "archivo_ruc_rise", "archivo_impuesto_renta", "archivo_iess", "desea_formalizarse", "etapa");
        
        /*$sql = General::insertSQL("emprendimiento", $emprendimiento, $campos);
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;*/
        
        //$campos = array("id_inscripcion", "id_etapa", "ver_mensaje", "estado", "id_etapa_anterior");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo) {
            $valores[] = $emprendimiento->{$campo};
            $tipodatos[] = "s";
        }
        return $this->con->Insertar("emprendimiento", $campos, $tipodatos, $valores);
    }
    
    function asignarEmprendimientoEmprendedor($id_emprendedor, $id_emprendimiento) {
        $sql = "INSERT INTO `emprendedor_emprendimiento`
            (`id_emprendedor`,`id_emprendimiento`)
            VALUES
            ($id_emprendedor,$id_emprendimiento);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function getUltimoEmprendimiento($id_emprededor) {
        $respuesta = new stdClass();
        $sql = "select a.*, b.id_emprendedor, a.id as id_emprendimiento
                FROM emprendimiento a
               INNER JOIN emprendedor_emprendimiento b on b.id_emprendimiento = a.id 
               where b.id_emprendedor = '$id_emprededor' and a.estado <> 'E' 
                   order by a.fecha_registro desc
               ";
        return $this->con->getEntidad($sql);
    }

    function getEmprendimientoPorId($id) {
        $sql = "SELECT a.*, a.id as id_emprendimiento, c.nombre as etapa_emprendimiento, d.nombre as tipo_emprendimiento,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto1) as url_foto_producto1, 
                                concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto2) as url_foto_producto2,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.logo) as url_logo, 
                                concat('" . ArchivosBO::getURLArchivo() . "',a.banner) as url_banner,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_nombramiento) as url_archivo_nombramiento,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_cuenta_bancaria) as url_archivo_cuenta_bancaria,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_ci) as url_archivo_ci,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_permiso_funcionamiento) as url_archivo_permiso_funcionamiento,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_registro_sanitario) as url_archivo_registro_sanitario,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_productos) as url_archivo_productos,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_ruc_rise) as url_archivo_ruc_rise,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_impuesto_renta) as url_archivo_impuesto_renta,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_iess) as url_archivo_iess
                FROM emprendimiento a
                LEFT OUTER JOIN etapa_emprendimiento c on c.id = a.id_etapa_emprendimiento
                LEFT OUTER JOIN tipo_emprendimiento d on d.id = a.id_tipo_emprendimiento
               WHERE a.id = '$id' ";
        return $this->con->getEntidad($sql);
    }

    function getRedesSocialesEmprendimiento($id_emprendimiento) {
        $sql = "SELECT * FROM red_social a
            left outer join emprendimiento_red_social b on b.id_red_social = a.id and b.id_emprendimiento = $id_emprendimiento;";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    function getEmprendimientoConsulta($id_emprendedor, $estado) {
        $sql = "SELECT a.*, c.nombre as etapa_emprendimiento, d.nombre as tipo_emprendimiento,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto1) as url_foto_producto1, 
                                concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto2) as url_foto_producto2,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.logo) as url_logo, 
                                concat('" . ArchivosBO::getURLArchivo() . "',a.banner) as url_banner,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_nombramiento) as url_archivo_nombramiento,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_cuenta_bancaria) as url_archivo_cuenta_bancaria,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_ci) as url_archivo_ci,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_permiso_funcionamiento) as url_archivo_permiso_funcionamiento,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_registro_sanitario) as url_archivo_registro_sanitario,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_productos) as url_archivo_productos,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_ruc_rise) as url_archivo_ruc_rise,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_impuesto_renta) as url_archivo_impuesto_renta,
                                concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_iess) as url_archivo_iess,
                                b.id_emprendedor
                FROM emprendimiento a
               INNER JOIN emprendedor_emprendimiento b on b.id_emprendimiento = a.id
               LEFT OUTER JOIN etapa_emprendimiento c on c.id = a.id_etapa_emprendimiento
               LEFT OUTER JOIN tipo_emprendimiento d on d.id = a.id_tipo_emprendimiento
                   WHERE b.id_emprendedor = $id_emprendedor
                     AND a.estado = (case '$estado' when 'T' then a.estado else '$estado' end)
                     AND a.estado <> 'E'
                order by a.fecha_registro desc";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    function getEmprendimientoConsultaXUsuario($id_usuario, $estado, $nombre, $identificacion, $nombre_emprendimiento, $email) {
        $sql = "SELECT 
                    /*emprendimiento*/
                    a.*, c.nombre as etapa_emprendimiento, d.nombre as tipo_emprendimiento,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto1) as url_foto_producto1, 
                    concat('" . ArchivosBO::getURLArchivo() . "',a.foto_producto2) as url_foto_producto2,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.logo) as url_logo, 
                    concat('" . ArchivosBO::getURLArchivo() . "',a.banner) as url_banner,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_nombramiento) as url_archivo_nombramiento,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_cuenta_bancaria) as url_archivo_cuenta_bancaria,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_ci) as url_archivo_ci,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_permiso_funcionamiento) as url_archivo_permiso_funcionamiento,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_registro_sanitario) as url_archivo_registro_sanitario,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_productos) as url_archivo_productos,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_ruc_rise) as url_archivo_ruc_rise,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_impuesto_renta) as url_archivo_impuesto_renta,
                    concat('" . ArchivosBO::getURLArchivo() . "',a.archivo_iess) as url_archivo_iess,
                    b.id_emprendedor, 

                    /*Solicitud*/
                    e.estado as estado_solicitud, e.id as id_solicitud_servicio, e.id_servicio, f.nombre as servicio,

                    /*emprendedor*/
                    per.nombre as nombre_emprendedor, per.apellido, per.email, emp.descripcion_rechazo, emp.estado as estado_emprendedor,
                    emp.fecha_aprobacion, per.fecha_nacimiento, per.id_ciudad, per.id_genero, per.id_nivel_academico,
                    per.id_situacion_laboral, emp.id_tipo_aprobacion, per.id_usuario, per.identificacion, per.telefono, 
                    per.tipo_identificacion
                FROM emprendimiento a
               INNER JOIN emprendedor_emprendimiento b on b.id_emprendimiento = a.id
               INNER JOIN etapa_emprendimiento c on c.id = a.id_etapa_emprendimiento
               INNER JOIN tipo_emprendimiento d on d.id = a.id_tipo_emprendimiento
               INNER JOIN emprendedor emp on emp.id = b.id_emprendedor
                left outer join persona per on per.id = emp.id_persona
               INNER JOIN emprendedor_solicitud_servicio e on e.id_emprendedor = b.id_emprendedor and e.id_emprendimiento = a.id
               INNER JOIN servicio f on f.id = e.id_servicio
                ";
        if ($id_usuario !== '1') {
            $sql .=" inner join usuario_asignacion_emprendimientos usu on usu.id_usuario = $id_usuario and usu.id_emprendimiento = a.id";
        }
        $sql .=" 
                WHERE e.estado = (case '$estado' when 'T' then e.estado else '$estado' end)
                ";

        if ($identificacion != '') {
            $sql .=" and (per.identificacion = '$identificacion' or a.ruc_rise = '$identificacion')";
        }
        if ($email != '') {
            $sql .=" and per.email = '$email'";
        }

        $sql .=" and per.nombre like '%$nombre%'";
        $sql .=" and a.nombre like '%$nombre_emprendimiento%'";

        $sql .=" order by e.fecha_registro";
        return $this->con->getArraySQL($sql);
    }

    function getEmpresaDeliveryXEmprendimiento($id_emprendimiento) {
        $sql = "SELECT distinct a.*, (case when b.id_empresa_delivery is null then false else true end) as selected, b.otra_empresa_delivery
                FROM empresa_delivery a
                left OUTER JOIN emprendimiento_canal_venta b on b.id_empresa_delivery = a.id and b.id_emprendimiento = $id_emprendimiento";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    function getLugarComercializacionXEmprendimiento($id_emprendimiento) {
        $sql = "SELECT distinct a.*, (case when b.id_lugar_emprendimiento is null then false else true end) as selected, b.otro_lugar
                FROM lugar_comercializacion a
                left OUTER JOIN emprendimiento_lugar_comercializacion b on b.id_lugar_emprendimiento = a.id and b.id_emprendimiento = $id_emprendimiento";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    function getActividadesComplementariasXEmprendimiento($id_emprendimiento) {
        $sql = "SELECT a.*, (case when b.id_actividad_complementaria is null then false else true end) as selected
                FROM actividad_complementaria a
                left OUTER JOIN emprendimiento_actividad_complementaria b on b.id_actividad_complementaria = a.id and b.id_emprendimiento = $id_emprendimiento";
        return $this->con->getArraySQL($sql);
    }

    function getCanalesVentasXEmprendimiento($id_emprendimiento) {
        $sql = "SELECT distinct a.*, (case when b.id_canal_venta is null then false else true end) as selected
                FROM canal_venta a
                left OUTER JOIN emprendimiento_canal_venta b on b.id_canal_venta = a.id and b.id_emprendimiento = $id_emprendimiento";
        return $this->con->getArraySQL($sql);
    }

    function getTipoFinancimientoXEmprendimiento($id_emprendimiento) {
        $sql = "SELECT distinct a.*, (case when b.id_tipo_financiamiento is null then false else true end) as selected
	FROM tipo_financiamiento a
	left OUTER JOIN emprendimiento_tipo_financiamiento b on b.id_tipo_financiamiento = a.id and b.id_emprendimiento =$id_emprendimiento";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    function getHorarioXEmprendimiento($id_emprendimiento) {
        $sql = "SELECT distinct a.*
                  FROM emprendimiento_horario a
                 WHERE a.id_emprendimiento =$id_emprendimiento";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    /*function actualizarEmprendimientoPaso1($emprendimiento) {
        General::setNullSql($emprendimiento, "ruc_rise");
        General::setNullSql($emprendimiento, "razon_social");
        General::setNullSql($emprendimiento, "direccion");

        General::setNullSql($emprendimiento, "persona");
        General::setNullSql($emprendimiento, "id_tipo_persona_societaria");
        General::setNullSql($emprendimiento, "otra_persona_societaria");
        General::setNullSql($emprendimiento, "producto");
        General::setNullSql($emprendimiento, "descripcion_emprendimiento");
        General::setNullSql($emprendimiento, "foto_producto1");
        General::setNullSql($emprendimiento, "foto_producto2");
        General::setNullSql($emprendimiento, "opera_ruc_rise");
        General::setNullSql($emprendimiento, "dispuesto_obtener_ruc_rise");
        General::setNullSql($emprendimiento, "nombre_comercial");
        $sql = "UPDATE emprendimiento SET
                    nombre = '$emprendimiento->nombre_emprendimiento',
                    id_etapa_emprendimiento = '$emprendimiento->id_etapa_emprendimiento',
                    id_tipo_emprendimiento = '$emprendimiento->id_tipo_emprendimiento',
                    cant_socios = '$emprendimiento->cant_socios',
                    emprendimiento_formalizado = '$emprendimiento->emprendimiento_formalizado',
                    ruc_rise = $emprendimiento->ruc_rise,
                    razon_social = $emprendimiento->razon_social,
                    nombre_comercial = $emprendimiento->nombre_comercial,
                    direccion = $emprendimiento->direccion,
                    estado = '$emprendimiento->estado',
                    
                    persona = $emprendimiento->persona,
                    id_tipo_persona_societaria = $emprendimiento->id_tipo_persona_societaria,
                    otra_persona_societaria = $emprendimiento->otra_persona_societaria,
                    producto = $emprendimiento->producto,
                    descripcion_emprendimiento = $emprendimiento->descripcion_emprendimiento,
                    foto_producto1 = $emprendimiento->foto_producto1,
                    foto_producto2 = $emprendimiento->foto_producto2,
                    opera_ruc_rise = $emprendimiento->opera_ruc_rise,
                    dispuesto_obtener_ruc_rise = $emprendimiento->dispuesto_obtener_ruc_rise
                WHERE id = $emprendimiento->id
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function actualizarEmprendimientoPaso2($emprendimiento) {
        General::setNullSql($emprendimiento, "foto_producto1");
        General::setNullSql($emprendimiento, "foto_producto2");
        General::setNullSql($emprendimiento, "producto");
        General::setNullSql($emprendimiento, "id_forma_capital");
        General::setNullSql($emprendimiento, "id_sector_crediticio");
        $sql = "UPDATE emprendimiento SET
                    id_tipo_emprendimiento = '$emprendimiento->id_tipo_emprendimiento',
                    numero_labora = '$emprendimiento->numero_labora',
                    venta_mensual = '$emprendimiento->venta_mensual',
                    ganancia_anual = '$emprendimiento->ganancia_anual',
                    id_lugar_comercializacion = '$emprendimiento->id_lugar_comercializacion',
                    cant_tipo_producto = '$emprendimiento->cant_tipo_producto',
                    utiliza_plataforma_electronica = '$emprendimiento->utiliza_plataforma_electronica',
                    posee_cuenta_bancaria = '$emprendimiento->posee_cuenta_bancaria',
                    realizado_prestado = '$emprendimiento->realizado_prestado',
                    id_forma_capital = $emprendimiento->id_forma_capital,
                    id_sector_crediticio = $emprendimiento->id_sector_crediticio,
                    estado = '$emprendimiento->estado',
                    producto = $emprendimiento->producto,
                    foto_producto1 = $emprendimiento->foto_producto1,
                    foto_producto2 = $emprendimiento->foto_producto2
                WHERE id = $emprendimiento->id
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function actualizarEmprendimientoPaso3($emprendimiento) {
        General::setNullSql($emprendimiento, "cuenta_bancaria");
        General::setNullSql($emprendimiento, "logo");
        General::setNullSql($emprendimiento, "banner");
        General::setNullSql($emprendimiento, "archivo_cuenta_bancaria");
        General::setNullSql($emprendimiento, "direccion_url");
        General::setNullSql($emprendimiento, "tiene_facturacion_electronica");
        General::setNullSql($emprendimiento, "telefono_whatsapp");
        General::setNullSql($emprendimiento, "posee_tarjeta_credito");
        General::setNullSql($emprendimiento, "desea_boton_pago");
        General::setNullSql($emprendimiento, "desea_integrar_delivery");
        General::setNullSql($emprendimiento, "archivo_ci");
        General::setNullSql($emprendimiento, "archivo_permiso_funcionamiento");
        General::setNullSql($emprendimiento, "archivo_registro_sanitario");
        General::setNullSql($emprendimiento, "archivo_productos");
        General::setNullSql($emprendimiento, "archivo_ruc_rise");
        General::setNullSql($emprendimiento, "archivo_impuesto_renta");
        General::setNullSql($emprendimiento, "archivo_iess");
        $sql = "UPDATE emprendimiento SET
                    cuenta_bancaria = $emprendimiento->cuenta_bancaria,
                    direccion = '$emprendimiento->direccion',
                    logo = $emprendimiento->logo,
                    banner = $emprendimiento->banner,
                    estado = '$emprendimiento->estado',
                    direccion_url = $emprendimiento->direccion_url,
                    tiene_facturacion_electronica = $emprendimiento->tiene_facturacion_electronica,
                    telefono_whatsapp = $emprendimiento->telefono_whatsapp,
                    posee_tarjeta_credito = $emprendimiento->posee_tarjeta_credito,
                    desea_boton_pago = $emprendimiento->desea_boton_pago,
                    desea_integrar_delivery = $emprendimiento->desea_integrar_delivery,
                    archivo_nombramiento = '$emprendimiento->archivo_nombramiento',
                    archivo_cuenta_bancaria = $emprendimiento->archivo_cuenta_bancaria,
                    archivo_ci = $emprendimiento->archivo_ci,
                    archivo_permiso_funcionamiento = $emprendimiento->archivo_permiso_funcionamiento,
                    archivo_registro_sanitario = $emprendimiento->archivo_registro_sanitario,
                    archivo_productos = $emprendimiento->archivo_productos,
                    archivo_ruc_rise = $emprendimiento->archivo_ruc_rise,
                    archivo_impuesto_renta = $emprendimiento->archivo_impuesto_renta,
                    archivo_iess = $emprendimiento->archivo_iess
                WHERE id = $emprendimiento->id
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    */
    function actualizarEmprendimientoDocumentos($emprendimiento) {
        General::setNullSql($emprendimiento, "archivo_cuenta_bancaria");
        General::setNullSql($emprendimiento, "archivo_ci");
        General::setNullSql($emprendimiento, "archivo_permiso_funcionamiento");
        General::setNullSql($emprendimiento, "archivo_registro_sanitario");
        General::setNullSql($emprendimiento, "archivo_productos");
        General::setNullSql($emprendimiento, "archivo_ruc_rise");
        General::setNullSql($emprendimiento, "archivo_impuesto_renta");
        General::setNullSql($emprendimiento, "archivo_iess");
        $sql = "UPDATE emprendimiento SET
                    archivo_nombramiento = '$emprendimiento->archivo_nombramiento',
                    archivo_cuenta_bancaria = $emprendimiento->archivo_cuenta_bancaria,
                    archivo_ci = $emprendimiento->archivo_ci,
                    archivo_permiso_funcionamiento = $emprendimiento->archivo_permiso_funcionamiento,
                    archivo_registro_sanitario = $emprendimiento->archivo_registro_sanitario,
                    archivo_productos = $emprendimiento->archivo_productos,
                    archivo_ruc_rise = $emprendimiento->archivo_ruc_rise,
                    archivo_impuesto_renta = $emprendimiento->archivo_impuesto_renta
                WHERE id = '$emprendimiento->id'
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        return $emprendimiento;
    }

    function eliminarRedesSocialesEmprendimiento($id_emprendimiento) {
        $sql = "DELETE FROM `emprendimiento_red_social` WHERE id_emprendimiento = $id_emprendimiento";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarRedesSociales($id_emprendimiento, $id_red_social, $red) {
        $sql = "INSERT INTO `emprendimiento_red_social`
                (`id_emprendimiento`,`id_red_social`,`red`)
                VALUES
                ($id_emprendimiento,$id_red_social,'$red');";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function eliminarCanalVentaEmprendimiento($id_emprendimiento) {
        $sql = "DELETE FROM `emprendimiento_canal_venta` WHERE id_emprendimiento = $id_emprendimiento";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarCanalVentaEmprendimiento($canal_venta) {
        General::setNullSql($canal_venta, "id_empresa_delivery");
        General::setNullSql($canal_venta, "otra_empresa_delivery");

        $sql = "INSERT INTO `emprendimiento_canal_venta`
                (`id_emprendimiento`,`id_canal_venta`,
                `id_empresa_delivery`,`otra_empresa_delivery`)
                VALUES
                ($canal_venta->id_emprendimiento,$canal_venta->id_canal_venta,
                 $canal_venta->id_empresa_delivery,$canal_venta->otra_empresa_delivery);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function eliminarActividadComplementariaEmprendimiento($id_emprendimiento) {
        $sql = "DELETE FROM `emprendimiento_actividad_complementaria` WHERE id_emprendimiento = $id_emprendimiento";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarActividadComplementariaEmprendimiento($id_emprendimiento, $id_actividad_complementaria) {
        $sql = "INSERT INTO `emprendimiento_actividad_complementaria`
                (`id_emprendimiento`,`id_actividad_complementaria`)
                VALUES
                ($id_emprendimiento,$id_actividad_complementaria);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function eliminarTipoFinanciamientoEmprendimiento($id_emprendimiento) {
        $sql = "DELETE FROM `emprendimiento_tipo_financiamiento` WHERE id_emprendimiento = $id_emprendimiento";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarTipoFinanciamientoEmprendimiento($id_emprendimiento, $id_tipo_financiamiento) {
        $sql = "INSERT INTO `emprendimiento_tipo_financiamiento`
                (`id_emprendimiento`,`id_tipo_financiamiento`)
                VALUES
                ($id_emprendimiento,$id_tipo_financiamiento);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function eliminarLugarComercializacionEmprendimiento($id_emprendimiento) {
        $sql = "DELETE FROM `emprendimiento_lugar_comercializacion` WHERE id_emprendimiento = $id_emprendimiento";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarLugarComercializacionEmprendimiento($id_emprendimiento, $id_lugar_comercializacion, $otro_lugar) {
        $sql = "INSERT INTO `emprendimiento_lugar_comercializacion`
                (`id_emprendimiento`,`id_lugar_emprendimiento`, otro_lugar)
                VALUES
                ($id_emprendimiento,$id_lugar_comercializacion, $otro_lugar);";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function eliminarHorarioEmprendimiento($id_emprendimiento) {
        $sql = "DELETE FROM `emprendimiento_horario` WHERE id_emprendimiento = $id_emprendimiento";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarHorarioEmprendimiento($horario) {
        $sql = "INSERT INTO `emprendimiento_horario`
                (`id_emprendimiento`,`dia`, hora_inicio, hora_fin)
                VALUES
                ($horario->id_emprendimiento,'$horario->dia', '$horario->hora_inicio', '$horario->hora_fin');";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function actualizarEstadoEmprendimiento($id_emprendimiento, $estado) {
        $sql = "UPDATE emprendimiento SET
                    estado = '$estado'
                WHERE id = '$id_emprendimiento'
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function getEmprendimientosAprobadosMercado593() {
        $lista = array();
        $sql = "select per.nombre, per.apellido, per.email, per.identificacion, 
            c.nombre as nombre_emprendimiento,
		d.estado, per.telefono,
        
                        c.ruc_rise, c.razon_social, c.direccion, c.telefono_whatsapp, c.desea_integrar_delivery, c.desea_boton_pago,
                        c.archivo_ci, c.archivo_cuenta_bancaria, c.archivo_iess, c.archivo_impuesto_renta,
                        c.archivo_nombramiento, c.archivo_permiso_funcionamiento, c.archivo_productos,
                        c.archivo_registro_sanitario, c.archivo_ruc_rise, c.nombre_comercial, c.direccion,
                        (select distinct rce.id_rubrica from emprendimiento_evaluacion ee inner join rubrica_criterio_evaluacion rce on rce.id = ee.id_rubrica_criterio_evaluacion where ee.id_emprendimiento = a.id) id_rubrica
                from emprendedor a
                left outer join persona per on per.id = emp.id_persona
                inner join emprendedor_emprendimiento b on b.id_emprendedor = a.id
                inner join emprendimiento c on c.id = b.id_emprendimiento
                inner join emprendedor_solicitud_servicio d on d.id_emprendimiento = c.id and d.id_emprendedor = a.id
                where not exists (select 1 from cola_correos cc where cc.destinatario like concat('%', a.email, '%') and cc.tipo = 'SOLICITUD MERCADO 593 APROBADO')
                  and d.estado = 'AS' ";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $i = 0;
        while ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->nombre = $row["nombre"];
            $respuesta->apellido = $row["apellido"];
            $respuesta->email = $row["email"];
            $respuesta->identificacion = $row["identificacion"];
            $respuesta->nombre_emprendimiento = $row["nombre_emprendimiento"];
            $respuesta->estado = $row["estado"];
            $respuesta->telefono = $row["telefono"];
            $respuesta->ruc_rise = $row["ruc_rise"];
            $respuesta->razon_social = $row["razon_social"];
            $respuesta->direccion = $row["direccion"];
            $respuesta->telefono_whatsapp = $row["telefono_whatsapp"];
            $respuesta->desea_integrar_delivery = $row["desea_integrar_delivery"];
            $respuesta->desea_boton_pago = $row["desea_boton_pago"];
            $respuesta->nombre_comercial = $row["nombre_comercial"];
            $respuesta->direccion = $row["direccion"];
            $respuesta->archivo_ci = $row["archivo_ci"];
            $respuesta->archivo_cuenta_bancaria = $row["archivo_cuenta_bancaria"];
            $respuesta->archivo_iess = $row["archivo_iess"];
            $respuesta->archivo_impuesto_renta = $row["archivo_impuesto_renta"];
            $respuesta->archivo_nombramiento = $row["archivo_nombramiento"];
            $respuesta->archivo_permiso_funcionamiento = $row["archivo_permiso_funcionamiento"];
            $respuesta->archivo_productos = $row["archivo_productos"];
            $respuesta->archivo_registro_sanitario = $row["archivo_registro_sanitario"];
            $respuesta->archivo_ruc_rise = $row["archivo_ruc_rise"];
            $respuesta->id_rubrica = $row["id_rubrica"];
            $lista[$i] = $respuesta;
            $i++;
        }
        return $lista;
    }
    
    function actualizarEmprendimiento($emprendimiento) {
        $campos=array("id_etapa_emprendimiento","emprendimiento_formalizado", "nombre", "cant_socios", "ruc_rise",
            "razon_social", "id_tipo_emprendimiento", "venta_mensual", "ganancia_anual", "cant_tipo_producto", 
            "utiliza_plataforma_electronica", "posee_cuenta_bancaria", "realizado_prestado", "persona", "id_tipo_persona_societaria", 
            "otra_persona_societaria", "producto", "descripcion_emprendimiento", "foto_producto1", "foto_producto2", 
            "opera_ruc_rise", "dispuesto_obtener_ruc_rise", "numero_labora", "direccion", "direccion_url",
            "estado_ce", "is_ce", "is_mercado", "completado", "estado", 
            "telefono_whatsapp", "email", "nombre_comercial", "archivo_ci", "archivo_permiso_funcionamiento", 
            "archivo_registro_sanitario", "archivo_productos", "archivo_ruc_rise", "archivo_impuesto_renta", "archivo_iess", 
            "desea_formalizarse", "etapa");
        $tabla="emprendimiento";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$emprendimiento->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($emprendimiento->id_emprendimiento);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
    }
    
    function actualizarEmprendimientoPaso1($emprendimiento) {
        $campos=array("id_etapa_emprendimiento", "nombre", "cant_socios",
            "id_tipo_emprendimiento", "venta_mensual", "cant_tipo_producto", 
            "numero_labora", "estado_ce", "is_ce", "is_mercado", 
            "completado", "estado", "id_tipo_persona_societaria", "otra_persona_societaria",
            "ruc_rise", "persona", "opera_ruc_rise", "dispuesto_obtener_ruc_rise",
            "razon_social", "nombre_comercial", "desea_formalizarse", "emprendimiento_formalizado", "archivo_ci",
            "archivo_permiso_funcionamiento","archivo_registro_sanitario", "archivo_productos",
            "archivo_ruc_rise", "archivo_impuesto_renta", "archivo_iess");
        $tabla="emprendimiento";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$emprendimiento->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($emprendimiento->id_emprendimiento);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
    }
    
    function actualizarEmprendimientoPaso2($emprendimiento) {
        $campos=array("telefono_whatsapp","email", "nombre", "cant_tipo_producto","utiliza_plataforma_electronica", 
            "completado", "direccion", "direccion_url");
        $tabla="emprendimiento";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$emprendimiento->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($emprendimiento->id_emprendimiento);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
    }
    
    function actualizarEmprendimientoPaso3($emprendimiento) {
        $campos=array("realizado_prestado", "otro_tipo_financiamiento","etapa", "completado");
        $tabla="emprendimiento";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$emprendimiento->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id");
        $campos_condicion_valor=array($emprendimiento->id_emprendimiento);
        $tipodatos_condicion=array("i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
    }

}
