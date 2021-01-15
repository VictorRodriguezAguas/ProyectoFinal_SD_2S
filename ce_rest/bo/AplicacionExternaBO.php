<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AplicacionExternaBO
 *
 * @author ernesto.ruales
 */
require_once '../bo/BO.php';
require_once '../dao/PersonaDAO.php';
require_once '../dao/ConsultasDAO.php';
require_once '../util/General.php';

class AplicacionExternaBO extends BO {

    //put your code here

    function ejecutarAplicacionExterna($datos, $codigo, $metodo) {
        try {
            $this->conectar();
            $consultasDAO = new ConsultasDAO();
            $consultasDAO->setConexion($this->conexion);
            $aplicacion = $consultasDAO->getAplicacionExterna($codigo);
            if (is_null($aplicacion)) {
                return false;
            }
            $headers = null;
            if ($aplicacion->esAutenticable == 'SI') {
                $respuesta = $this->getAutenticar($aplicacion);
                if (is_null($respuesta)) {
                    return false;
                }
                if (!is_null($aplicacion->tramaHederToken)) {
                    $headers = $this->getHeaderAutenticar($aplicacion, $respuesta->data->token);
                }
            }
            if ($aplicacion->esRegistrable == 'SI') {
                $respuesta = $this->registrar($aplicacion, $headers, $datos);
                if (!$this->validarRespuesta($aplicacion, $respuesta)) {
                    return false;
                }
            }

            $mailBO = new MailBO();
            $mailBO->setConexion($this->conexion);
            $data = $mailBO->getTramaJSON($datos->cod_trama, $datos);
            $ch = curl_init($aplicacion->api . "/$metodo");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //establecemos el verbo http que queremos utilizar para la petición
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

            if (!is_null($headers))
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //enviamos el array data
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //obtenemos la respuesta
            $response = curl_exec($ch);
            // Se cierra el recurso CURL y se liberan los recursos del sistema
            curl_close($ch);
            if (!$response) {
                return false;
            } else {
                return $this->validarRespuesta($aplicacion, json_decode($response));
            }
        } catch (Exception $ex) {
            return false;
        } finally {
            $this->cerrarConexion();
        }
    }

    function validarRespuesta($aplicacion, $respuesta) {
        switch ($aplicacion->codigo) {
            case '001':
                return $this->validarRespuestaAtrevete($respuesta);
            default:
                return true;
        }
    }

    function validarRespuestaAtrevete($respuesta) {
        if ($respuesta instanceof stdClass) {
            if (!$respuesta->success) {
                if ($respuesta->message == 'Estudiante ya cuenta con registro del curso') {
                    return true;
                }
                if ($respuesta->message == 'Verifique los campos') {
                    if ($respuesta->errors instanceof stdClass) {
                        if ($respuesta->errors->email == 'Correo electrónico ya se encuentra registrado') {
                            return true;
                        }
                    }
                    return false;
                }
                return false;
            }
            return true;
        }
        return false;
    }

    function getHeaderAutenticar($aplicacion, $token) {
        $aplicacion->tramaHederToken = str_replace("<<token>>", $token, $aplicacion->tramaHederToken);
        $headers = [
            "$aplicacion->headerTokenName: $aplicacion->tramaHederToken"
        ];
        return $headers;
    }

    function getAutenticar($aplicacion) {
        $trama = str_replace("<<usuario>>", $aplicacion->usuario, $aplicacion->trama_auth);
        $trama = str_replace("<<password>>", $aplicacion->password, $trama);
        $data = json_decode($trama);
        $data = get_object_vars($data);
        $ch = curl_init($aplicacion->link_auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //enviamos el array data
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        //obtenemos la respuesta
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);
        if (!$response) {
            return null;
        } else {
            return json_decode($response);
        }
    }

    private function registrar($aplicacion, $headers, $values) {
        $mailBO = new MailBO();
        $mailBO->setConexion($this->conexion);
        $data = $mailBO->getTramaJSON($aplicacion->cod_trama_registro, $values);
        //$data = $this->getDatosTrama($aplicacion->cod_trama_registro, $values);

        $ch = curl_init($aplicacion->link_registro);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        if (!is_null($headers))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //enviamos el array data
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        //obtenemos la respuesta
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);
        if (!$response) {
            return null;
        } else {
            return json_decode($response);
        }
    }

}

/*$aplicacionExternaBO = new AplicacionExternaBO();
$datos = new stdClass();
$datos->id_persona = "1";
$datos->curso = '97';
$datos->cod_trama = 'APEX2';
$repuesta = $aplicacionExternaBO->ejecutarAplicacionExterna($datos, '001', 'student_course_add');
if($repuesta){
    echo "Se registro con éxito";
}
*/