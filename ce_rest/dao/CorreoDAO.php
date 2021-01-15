<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CorreoDAO
 *
 * @author ernesto.ruales
 */
class CorreoDAO {
    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getColaCorreos($estado, $intentos, $max_registros) {
        $lista = array();
        $sql = "select * from cola_correos where estado = '$estado' ";
        if(!is_null($intentos)){
            $sql .= " and numero_intento < $intentos";
        }
        if(!is_null($max_registros)){
            $sql .= " limit $max_registros";
        }
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $i=0;
        while ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->id = $row["id"];
            $respuesta->destinatario = $row["destinatario"];
            $respuesta->asunto = $row["asunto"];
            $respuesta->texto_correo = $row["texto_correo"];
            $respuesta->estado = $row["estado"];
            $respuesta->error = $row["error"];
            $respuesta->fecha_registro = $row["fecha_registro"];
            $respuesta->fecha_envio = $row["fecha_envio"];
            $respuesta->fecha_ultimo_intento = $row["fecha_ultimo_intento"];
            $respuesta->numero_intento = $row["numero_intento"];
            $respuesta->tipo = $row["tipo"];
            $respuesta->archivo = $row["archivo"];
            $respuesta->nombre_archivo = $row["nombre_archivo"];
            $lista[$i] = $respuesta;
            $i++;
        }
        return $lista;
    }
    
    function getColaCorreosConsulta($estado) {
        $lista = array();
        $sql = "select * from cola_correos where estado = '$estado' ";
        $resultado_consulta = $this->con->getConexion()->query($sql);
        $i=0;
        while ($row = $resultado_consulta->fetch_array()) {
            $respuesta = new stdClass();
            $respuesta->id = $row["id"];
            $respuesta->destinatario = $row["destinatario"];
            $respuesta->asunto = $row["asunto"];
            $respuesta->estado = $row["estado"];
            $respuesta->error = $row["error"];
            $respuesta->fecha_registro = $row["fecha_registro"];
            $respuesta->fecha_envio = $row["fecha_envio"];
            $respuesta->fecha_ultimo_intento = $row["fecha_ultimo_intento"];
            $respuesta->numero_intento = $row["numero_intento"];
            $respuesta->tipo = $row["tipo"];
            $respuesta->archivo = $row["archivo"];
            $respuesta->nombre_archivo = $row["nombre_archivo"];
            $lista[$i] = $respuesta;
            $i++;
        }
        return $lista;
    }
    
    function insertarCorreo($correo){
        if(General::tieneValor($correo, "tipo")){
            $correo->tipo = "Sin tipo";
        }
        General::setNullSql($correo, "archivo");
        General::setNullSql($correo, "nombre_archivo");
        $correo->texto_correo = str_replace('"',"'", $correo->texto_correo);
        $sql = '
                INSERT INTO `cola_correos`
                (`destinatario`,`asunto`,`texto_correo`,`estado`,
                `tipo`,`archivo`, nombre_archivo)
                VALUES
                ("'.$correo->destinatario.'","'.$correo->asunto.'","'.$correo->texto_correo.'","'.$correo->estado.'",
                 "'.$correo->tipo.'",'.$correo->archivo.','.$correo->nombre_archivo.');
            '
                ;
        //print $sql;
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }
    
    function actualizarCorreo($correo){
        General::setNullSql($correo, "error");
        $sql = "UPDATE cola_correos SET 
                    estado = '$correo->estado',
                    error = $correo->error,
                    fecha_envio = $correo->fecha_envio,
                    numero_intento = numero_intento + 1
                WHERE id = $correo->id
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
}
