<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonaDAO
 *
 * @author ernesto.ruales
 */
class PersonaDAO {

    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function actualizarCV($persona) {
        $sql = "UPDATE `persona`
                    SET
                    `cv` = '$persona->cv'";
        if (is_null($persona->id_persona))
            $sql .= "    WHERE `id` = '$persona->id' ";
        else
            $sql .= "    WHERE `id` = '$persona->id_persona' ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function actualizarPersona($persona) {
        $sql = "UPDATE `persona`
                    SET
                    `nombre` = '$persona->nombre',
                    `apellido` = '$persona->apellido',
                    `fecha_nacimiento` = '$persona->fecha_nacimiento',
                    `id_genero` = '$persona->id_genero',
                    `id_ciudad` = '$persona->id_ciudad',
                    `email` = '$persona->email',
                    `telefono` = '$persona->telefono',
                    `id_situacion_laboral` = '$persona->id_situacion_laboral',
                    `tipo_identificacion` = '$persona->tipo_identificacion',
                    `identificacion` = '$persona->identificacion',
                    `id_nivel_academico` = '$persona->id_nivel_academico'";
        if (General::tieneValor($persona, "direccion")) {
            $sql .= "    ,`direccion` = '$persona->direccion' ";
        }
        if (General::tieneValor($persona, "id_interes_centro_emprendimiento")) {
            $sql .= "    ,`id_interes_centro_emprendimiento` = '$persona->id_interes_centro_emprendimiento' ";
        }
        if (General::tieneValor($persona, "telefono_fijo")) {
            $sql .= "    ,`telefono_fijo` = '$persona->telefono_fijo' ";
        }
        if (General::tieneValor($persona, "uso_datos")) {
            $sql .= "    ,`uso_datos` = '$persona->uso_datos' ";
        }
        if (General::tieneValor($persona, "id_usuario")) {
            $sql .= "    ,`id_usuario` = '$persona->id_usuario' ";
        }
        if (General::tieneValor($persona, "frase_perfil")) {
            $sql .= "    ,`frase_perfil` = '$persona->frase_perfil' ";
        }
        if (General::tieneValor($persona, "perfil")) {
            $sql .= "    ,`perfil` = '$persona->perfil' ";
        }
        if (General::tieneValor($persona, "estado")) {
            $sql .= "    ,`estado` = '$persona->estado' ";
        }
        if (General::tieneValor($persona, "latitud")) {
            $sql .= "    ,`latitud` = '$persona->latitud' ";
        }
        if (General::tieneValor($persona, "longitud")) {
            $sql .= "    ,`longitud` = '$persona->longitud' ";
        }
        if (General::tieneValor($persona, "id_persona"))
            $sql .= "    WHERE `id` = '$persona->id_persona' ";
        else
            $sql .= "    WHERE `id` = '$persona->id' ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarPersona($persona) {
        $sql = "INSERT INTO `persona`
        (`nombre`,`apellido`,`fecha_nacimiento`,`id_genero`,`id_ciudad`,
        `email`,`telefono`,`id_situacion_laboral`,`tipo_identificacion`,
        `identificacion`,`id_nivel_academico`
        ";
        if (General::tieneValor($persona, "direccion")) {
            $sql .= ", direccion";
        }
        if (General::tieneValor($persona, "id_interes_centro_emprendimiento")) {
            $sql .= ", id_interes_centro_emprendimiento";
        }
        if (General::tieneValor($persona, "telefono_fijo")) {
            $sql .= ", telefono_fijo";
        }
        if (General::tieneValor($persona, "uso_datos")) {
            $sql .= ", uso_datos";
        }
        if (General::tieneValor($persona, "id_usuario")) {
            $sql .= ", id_usuario";
        }
        if (General::tieneValor($persona, "frase_perfil")) {
            $sql .= ", frase_perfil";
        }
        if (General::tieneValor($persona, "perfil")) {
            $sql .= ", perfil";
        }
        if (General::tieneValor($persona, "latitud")) {
            $sql .= ", latitud";
        }
        if (General::tieneValor($persona, "longitud")) {
            $sql .= ", longitud";
        }
        $sql .= ") VALUES
        ('$persona->nombre','$persona->apellido','$persona->fecha_nacimiento','$persona->id_genero','$persona->id_ciudad',
        '$persona->email','$persona->telefono','$persona->id_situacion_laboral','$persona->tipo_identificacion',
        '$persona->identificacion','$persona->id_nivel_academico' ";
        if (General::tieneValor($persona, "direccion")) {
            $sql .= ", '$persona->direccion'";
        }
        if (General::tieneValor($persona, "id_interes_centro_emprendimiento")) {
            $sql .= ", '$persona->id_interes_centro_emprendimiento'";
        }
        if (General::tieneValor($persona, "telefono_fijo")) {
            $sql .= ", '$persona->telefono_fijo'";
        }
        if (General::tieneValor($persona, "uso_datos")) {
            $sql .= ", '$persona->uso_datos'";
        }
        if (General::tieneValor($persona, "id_usuario")) {
            $sql .= ", $persona->id_usuario";
        }
        if (General::tieneValor($persona, "frase_perfil")) {
            $sql .= ", '$persona->frase_perfil'";
        }
        if (General::tieneValor($persona, "perfil")) {
            $sql .= ", '$persona->perfil'";
        }
        if (General::tieneValor($persona, "latitud")) {
            $sql .= ", '$persona->latitud'";
        }
        if (General::tieneValor($persona, "longitud")) {
            $sql .= ", '$persona->longitud'";
        }
        $sql .= ");";
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }

    function getPersonaXIdent($identificacion) {
        $sql = "select * from persona b where b.identificacion = '$identificacion' ";
        return $this->con->getEntidad($sql);
    }

    function getPersonaXCorreo($email) {
        $sql = "select * from persona b where b.email like '$email' ";
        return $this->con->getEntidad($sql);
    }

    function getPersonaXId($id) {
        $sql = "select a.*, a.id as id_persona, b.nombre as nivel_academico, c.nombre as genero, d.nombre as situacion_laboral, 
                        e.nombre as ciudad,
                    prov.id as id_provincia, prov.nombre as provincia
                  from persona a
               left outer join nivel_academico b on b.id = a.id_nivel_academico
               left outer join genero c on c.id = a.id_genero
               left outer join situacion_laboral d on d.id = a.id_situacion_laboral
               left outer join ubicacion e on e.id = a.id_ciudad
               left outer join ubicacion prov on prov.codigo = substr(e.codigo, 1, 2)
                where a.id = '$id' ";
        return $this->con->getEntidad($sql);
    }
    
    function getPersonaXEmail($email) {
        $sql = "select a.*, a.id as id_persona, b.nombre as nivel_academico, c.nombre as genero, d.nombre as situacion_laboral, 
                        e.nombre as ciudad
                  from persona a
               left outer join nivel_academico b on b.id = a.id_nivel_academico
               left outer join genero c on c.id = a.id_genero
               left outer join situacion_laboral d on d.id = a.id_situacion_laboral
               left outer join ubicacion e on e.id = a.id_ciudad
                where a.email = '$email' ";
        return $this->con->getEntidad($sql);
    }
    
    function getPersonaXIdEmprendedor($id) {
        $sql = "select a.* 
                  from persona a 
                inner join emprendedor b on b.id_persona = a.id
                where b.id = '$id' ";
        return $this->con->getEntidad($sql);
    }
    
    function getPersonaXIdMentor($id) {
        $sql = "select a.* 
                  from persona a 
                 inner join mentor b on b.id_persona = a.id
                 where b.id = '$id' ";
        return $this->con->getEntidad($sql);
    }

    function getPersonaXIdUsuario($id_usuario) {
        $sql = "select a.*, b.nombre as genero, c.nombre as ciudad, d.nombre as ciudad_domicilio,
                    e.nombre as nivel_academico, f.nombre as situacion_laboral, a.id as id_persona,
                    prov.id as id_provincia, prov.nombre as provincia
                  from persona a 
                 inner join genero b on b.id = a.id_genero
                 left outer join ubicacion c on c.id = a.id_ciudad
                 left outer join ubicacion d on d.id = a.id_ciudad_domicilio
                 left outer join ubicacion prov on prov.codigo = substr(c.codigo, 1, 2)
                 inner join nivel_academico e on e.id = a.id_nivel_academico
                 inner join situacion_laboral f on f.id = a.id_situacion_laboral
                 where a.id_usuario = '$id_usuario' ";
        return $this->con->getEntidad($sql);
    }
    
    function getPersonaxIdInscripcion($id_inscripcion) {
        $sql = "select a.*, a.id as id_persona from persona a
            inner join inscripcion_edicion b on b.id_persona = a.id
            where b.id = $id_inscripcion";
        return $this->con->getEntidad($sql);
    }

    function getRedesSocialesPersona($id_persona) {
        $sql = "SELECT * FROM red_social a
            left outer join persona_red_social b on b.id_red_social = a.id and b.id_persona = '$id_persona';";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }
    
    function getInteresPersona($id_persona) {
        $sql = "select b.*, b.etiqueta as nombre, b.id as value, b.etiqueta as label
                from persona_interes a
                inner join etiqueta b on b.id = a.id_etiqueta
                where a.id_persona = $id_persona";
        //print $sql;
        return $this->con->getArraySQL($sql);
    }

    function getPersonas() {
        $sql = "select p.*,
                    prov.id as id_provincia, 
                    prov.nombre as provincia
                from persona p
                left outer join ubicacion ubi on ubi.id = p.id_ciudad
                left outer join ubicacion prov on prov.codigo = substr(ubi.codigo, 1, 2)
                where p.estado = 'A'";
        return $this->con->getArraySQL($sql);
    }

    function eliminarRedesSocialesPersona($id_persona) {
        $sql = "DELETE FROM `persona_red_social` WHERE id_persona = $id_persona";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarRedesSocialesPersona($id_persona, $id_red_social, $red) {
        $sql = "INSERT INTO `persona_red_social`
                (`id_persona`,`id_red_social`,`red`)
                VALUES
                ($id_persona,$id_red_social,'$red');";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }
    
    function eliminarInteresPersona($id_persona) {
        $sql = "DELETE FROM `persona_interes` WHERE id_persona = $id_persona";
        //print  $sql;
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function insertarInteresPersona($id_persona, $id_etiqueta) {
        $sql = "INSERT INTO `persona_interes`
                (`id_persona`,`id_etiqueta`)
                VALUES
                ($id_persona,$id_etiqueta);";
        $result = mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function getPersona($id) {
        $sql = "select a.*
                  from persona a
                where a.id = '$id' ";
        return $this->con->getEntidad($sql);
    }
}
