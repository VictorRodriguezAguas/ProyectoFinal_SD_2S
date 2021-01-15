<?php

header('Content-type: text/plain; charset=utf-8');
error_reporting(E_ALL ^ E_WARNING);
/* Clase: basedatos
 * Creado por: Guido Benetazzo
 * Fecha creacion: 21-07-2015
 * Ultima modificacion: 21-07-2015 
 * Descripcion: Clase para manejar los eventos de base de datos - conexion, insert, update, delete, selects 
 * Control del cambios:
 * dd/mm/yyyy: descripcion del cambio */

Class basedatos {

    //Variable conexion donde se instancia la clase Mysqli
    private $conexion;
    //Variable publica para manejo de errores y mensajes
    public $error = 0;
    public $mensaje_error = "";
    public $codigo_error = "";

    public function __construct() {
        
    }

    /* Metodos get y set para las propiedades */

    // set $error
    public function setError($valor) {
        $this->error = $valor;
    }

    // get $error
    public function getError() {
        return $this->error;
    }

    // set $codigo_error
    public function setCodigoError($valor) {
        $this->codigo_error = $valor;
    }

    // get $codigo_error
    public function getCodigoError() {
        return $this->codigo_error;
    }

    // set $mensaje_error
    public function setMensajeError($valor) {
        $this->mensaje_error = $valor;
    }

    // get $mensaje_error
    public function getMensajeError() {
        return $this->mensaje_error;
    }

    public function Conectar() {
        $host = "localhost";
        $db = "centro_emprendimiento";
        $user = "root";
        $pass = "";

        $this->conexion = new mysqli($host, $user, $pass, $db);

        if ($this->conexion->connect_error) {

            $this->setError(1);
            $this->setMensajeError("Error de conexion: " . $this->conexion->connect_error);
        } else {
            $this->setError(0);
            $this->setMensajeError("Conexion exitosa");
            mysqli_set_charset($this->conexion, "utf8");
        }
    }

    public function CerrarConexion() {
        $this->conexion->close();
    }

    public function Insertar($tabla, $campos, $tipodatos, $valores) {
        $this->setError(0);
        $listacampos = "";
        $listaparam = "";
        $listatipodato = "";
        //Recorrer el arreglo $campos para formar la lista de campos requerida para el insert
        end($campos);
        $ultimo = key($campos);
        reset($campos);
        foreach ($campos as $key => $campo) {
            if ($key === $ultimo) {
                $listacampos.=$campo;
                $listaparam.="?";
            } else {
                $listacampos.=$campo . ",";
                $listaparam.="?,";
            }
        }
        //Recorrer el arreglo $tipodato para formar la lista de tipos de dato
        foreach ($tipodatos as $tipodato) {
            $listatipodato.=$tipodato;
        }

        // Preparar la sentencia y asignar los parametros
        if (!$sentencia = $this->conexion->prepare("INSERT INTO " . $tabla . " (" . $listacampos . ") VALUES (" . $listaparam . ")")) {
            $this->setError(1);
            $this->setMensajeError("Error Prepare: (" . $this->conexion->errno . ") " . $this->conexion->error);
            $this->setCodigoError($this->conexion->errno);
        } else {
            $parametros = array();
            $parametros[] = $listatipodato;
            $largo = count($valores);
            for ($x = 0; $x < $largo; $x++) {
                $parametros[$x + 1] = & $valores[$x];
            }
            if (!call_user_func_array(array($sentencia, 'bind_param'), $parametros)) {
                $this->setError(1);
                $this->setMensajeError("Error asignar parametro: (" . $sentencia->errno . ") " . $sentencia->error);
            }
            if ($this->getError() == 0) {
                if (!$sentencia->execute()) {
                    $this->setError(1);
                    $this->setMensajeError("Error al ejecutar sentencia: (" . $sentencia->errno . ") " . $sentencia->error);
                    $this->setCodigoError($sentencia->errno);
                } else {
                    $this->setError(0);
                    $this->setMensajeError("Insert exitoso");
                }
            }
        }
    }

// Fin de la funcion Insertar

    public function Actualizar($tabla, $campos, $tipodatos, $valores, $campos_condicion = NULL, $campos_condicion_valor = NULL, $tipodatos_condicion = NULL, $operador_condicion = NULL) {
        $this->setError(0);
        $listatipodato = "";
        //Crea una lista de tipo de datos
        foreach ($tipodatos as $tipodato) {
            $listatipodato.=$tipodato;
        }
        //Construye la primera parte de la sentencia preparada
        $sql = "update " . $tabla . " set";
        $largo = count($campos);
        for ($x = 0; $x < $largo; $x++) {
            if (!($x == $largo - 1))
                $sql = $sql . " " . $campos[$x] . "=?,";
            else
                $sql = $sql . " " . $campos[$x] . "=?";
        }
        //Construye la seccion de condiciones
        if (!($campos_condicion == NULL)) {
            //Adiciona a la lista los tipo de datos de las condiciones
            foreach ($tipodatos_condicion as $tipodato_condicion) {
                $listatipodato.=$tipodato_condicion;
            }
            $largo = count($campos_condicion);
            for ($x = 0; $x < $largo; $x++) {
                if ($operador_condicion == NULL) {
                    if ($x == 0)
                        $sql = $sql . " where " . $campos_condicion[$x] . "=?";
                    else
                        $sql = $sql . " and " . $campos_condicion[$x] . "=?";
                }
                else {
                    if ($x == 0)
                        $sql = $sql . " where " . $campos_condicion[$x] . $operador_condicion[$x] . "?";
                    else
                        $sql = $sql . " and " . $campos_condicion[$x] . $operador_condicion[$x] . "?";
                }
            }
        }
        //Prepara la sentencia
        if (!$sentencia = $this->conexion->prepare($sql)) {
            $this->setError(1);
            $this->setMensajeError("Error Prepare: (" . $this->conexion->errno . ") " . $this->conexion->error);
            $this->setCodigoError($this->conexion->errno);
        }
        //Si no hay error en la setencia preparada se continua con el proceso
        else {
            $parametros = array();
            $parametros[] = $listatipodato;
            $largo = count($valores);
            //Recorre los valores a actualizar para anadirlo a la lista de parametros
            for ($x = 0; $x < $largo; $x++) {
                $parametros[$x + 1] = & $valores[$x];
            }
            //Recorre la lista de valores de condiciones para anadirlo a la lista de parametros, inicia desde el ultimo item del arreglo+1
            $largo = count($campos_condicion_valor);
            $inicio = count($parametros);
            for ($x = 0; $x < $largo; $x++) {
                $parametros[$inicio] = & $campos_condicion_valor[$x];
                $inicio++;
            }
            if (!call_user_func_array(array($sentencia, 'bind_param'), $parametros)) {
                $this->setError(1);
                $this->setMensajeError("Error asignar parametro: (" . $sentencia->errno . ") " . $sentencia->error);
                $this->setCodigoError($sentencia->errno);
            }
            if ($this->getError() == 0) {
                if (!$sentencia->execute()) {
                    $this->setError(1);
                    $this->setMensajeError("Error al ejecutar sentencia: (" . $sentencia->errno . ") " . $sentencia->error);
                    $this->setCodigoError($sentencia->errno);
                } else {
                    $this->setError(0);
                    $this->setMensajeError("Update exitoso");
                }
            }
        }
    }

//Fin de la funcion Actualizar

    public function Eliminar($tabla, $campos_condicion, $campos_condicion_valor, $tipodatos_condicion) {
        $this->setError(0);
        $listatipodato = "";
        //Construye la sentencia
        $sql = "delete from " . $tabla;
        $largo = count($campos_condicion);
        for ($x = 0; $x < $largo; $x++) {
            if ($x == 0)
                $sql = $sql . " where " . $campos_condicion[$x] . "=?";
            else
                $sql = $sql . " and " . $campos_condicion[$x] . "=?";
        }
        //Prepara la sentencia
        if (!$sentencia = $this->conexion->prepare($sql)) {
            $this->setError(1);
            $this->setMensajeError("Error Prepare: (" . $this->conexion->errno . ") " . $this->conexion->error);
        }
        //Si no hay error en la setencia preparada se continua con el proceso
        else {
            //Adiciona a la lista los tipo de datos de las condiciones
            foreach ($tipodatos_condicion as $tipodato_condicion) {
                $listatipodato.=$tipodato_condicion;
            }
            $parametros = array();
            //Se agregan los tipos de datos al arreglo de parametros
            $parametros[] = $listatipodato;

            //Recorre la lista de valores de condiciones para anadirlo a la lista de parametros, inicia desde el ultimo item del arreglo+1
            $largo = count($campos_condicion_valor);
            $inicio = count($parametros);
            for ($x = 0; $x < $largo; $x++) {
                $parametros[$inicio] = & $campos_condicion_valor[$x];
                $inicio++;
            }
            if (!call_user_func_array(array($sentencia, 'bind_param'), $parametros)) {
                $this->setError(1);
                $this->setMensajeError("Error asignar parametro: (" . $sentencia->errno . ") " . $sentencia->error);
            }
            if ($this->getError() == 0) {
                if (!$sentencia->execute()) {
                    $this->setError(1);
                    $this->setMensajeError("Error al ejecutar sentencia: (" . $sentencia->errno . ") " . $sentencia->error);
                } else {
                    $this->setError(0);
                    $this->setMensajeError("Registro eliminado con exito");
                }
            }
        }
    }

//Fin de la funcion Eliminar.	

    /* En el parametro $sql se debe enviar la sentencia sql con el caracter ? en lugar de los valores de los parametros
     * ejemplo: select * from tabla where id=? */

    public function Select($sql, $valores = NULL, $tipodatos = NULL, $tiposentencia = "select") {
        $this->setError(0);
        $listatipodato = "";
        $this->conexion->query("SET lc_time_names = 'es_ES'");
        //Prepara la sentencia
        if (!$sentencia = $this->conexion->prepare($sql)) {
            $this->setError(1);
            $this->setMensajeError("Error Prepare: (" . $this->conexion->errno . ") " . $this->conexion->error);
        } else {
            if (!($valores == NULL)) {
                //Adiciona a la lista los tipo de datos de las condiciones
                foreach ($tipodatos as $tipodato) {
                    $listatipodato = $listatipodato . $tipodato;
                }
                $parametros = array();
                //Se agregan los tipos de datos al arreglo de parametros
                $parametros[] = $listatipodato;
                //Recorrer los valores a actualizar para anadirlo a la lista de parametros
                $largo = count($valores);
                for ($x = 0; $x < $largo; $x++) {
                    //Se agregan los valores en el arreglo parametros desde la posicion x+1 ya que en la posicion 0 se encuentran los tipos de datos
                    $parametros[$x + 1] = & $valores[$x];
                }
                if (!call_user_func_array(array($sentencia, 'bind_param'), $parametros)) {
                    $this->setError(1);
                    $this->setMensajeError("Error asignar parametro: (" . $sentencia->errno . ") " . $sentencia->error);
                }
            }
            if ($this->getError() == 0) {
                if (!$sentencia->execute()) {
                    $this->setError(1);
                    $this->setMensajeError("Error al ejecutar sentencia: (" . $sentencia->errno . ") " . $sentencia->error);
                } else {
                    $resultado = $sentencia->get_result();
                    $x = 0;
                    $query_result = array();
                    if ($tiposentencia == "select") {
                        while ($row = $resultado->fetch_array(MYSQLI_ASSOC)) {
                            //die("entre al while");
                            $query_result[$x] = $row;
                            $x++;
                        }
                        $this->setError(0);
                        $this->setMensajeError("Select exitoso");
                        return $query_result;
                    } else {
                        $this->setError(0);
                        $this->setMensajeError("Query exitoso");
                    }
                }
            }
        }
    }

//Fin de la funcion Select
}

?>