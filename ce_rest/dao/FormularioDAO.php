<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormularioDAO
 *
 * @author ernesto.ruales
 */
class FormularioDAO {
    //put your code here
    
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }
    
    function insertar($evento){        
        $campos=array("nombre","estado", "fecha", "hora_inicio",
            "hora_fin", "url", "id_tipo_evento", "codigo", "id_evento_mec", "color",
            "cupo", "id_tipo_asistencia");
        
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$evento->{$campo};
            $tipodatos[]="s";
        }
        return $this->con->Insertar("evento",$campos,$tipodatos,$valores);
    }
    
    function getFormulario($id) {
        $sql = " select a.*, a.id as id_formulario from formulario a where id = '$id'";
        return $this->con->getEntidad($sql);
    }
    
    function getFormularioxCodigo($codigo) {
        $sql = " select a.*, a.id as id_formulario from formulario a where codigo = '$codigo'";
        return $this->con->getEntidad($sql);
    }
    
    function getCampos($id_formulario) {
        $sql = "SELECT a.*, a.id as id_formulario_campo,
		b.*
            FROM formulario_campo a
            inner join campo b on b.id = a.id_campo
            where a.id_formulario = $id_formulario
              and a.estado = 'A'
            order by a.orden;";
        return $this->con->getArraySQL($sql);
    }
    
    function getRegistro($id_registro){
        $sql = "select a.*,
		b.*,
                b.id as id_registro_formulario
         from formulario a 
         inner join registro_formulario b on b.id_formulario = a.id
        where b.id = '$id_registro'";
        return $this->con->getEntidad($sql);
    }
    
    function getCamposRegistro($id_registro) {
        $sql = "SELECT a.*, 
		b.*,
                c.*,
                c.valor as valorAux
            FROM registro_campo c 
            inner join formulario_campo a on a.id = c.id_formulario_campo
            inner join campo b on b.id = a.id_campo
            where c.id_registro = '$id_registro'
            order by a.orden;";
        return $this->con->getArraySQL($sql);
    }
    
     function getCampoRegistro($id_registro, $id_formulario_campo) {
        $sql = "SELECT a.*, 
		b.*,
                c.*
            FROM registro_campo c 
            inner join formulario_campo a on a.id = c.id_formulario_campo
            inner join campo b on b.id = a.id_campo
            where c.id_registro = '$id_registro'
              and c.id_formulario_campo = '$id_formulario_campo'";
        return $this->con->getEntidad($sql);
    }
    
    function insertRegistro($registro){        
        $campos=array("id_formulario","id_usuario_registro");
        
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$registro->{$campo};
            $tipodatos[]="s";
        }
        return $this->con->Insertar("registro_formulario",$campos,$tipodatos,$valores);
    }
    
    function insertCampoRegistro($campoRegistro){        
        $campos=array("id_registro","id_formulario_campo", "valor", "otro_valor");
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$campoRegistro->{$campo};
            $tipodatos[]="s";
        }
        $this->con->Insertar("registro_campo",$campos,$tipodatos,$valores);
        if($this->con->getError() == '1'){
            throw new Exception($this->con->getMensajeError());
        }
    }
    
    function actualizarCampoRegistro($campoRegistro) {
        $campos=array("valor", "otro_valor");
        $tabla="registro_campo";
        $valores = array();
        $tipodatos = array();
        foreach ($campos as &$campo){
            $valores[]=$campoRegistro->{$campo};
            $tipodatos[]="s";
        }
        $campos_condicion=array("id_registro", "id_formulario_campo");
        $campos_condicion_valor=array($campoRegistro->id_registro, $campoRegistro->id_formulario_campo);
        $tipodatos_condicion=array("i", "i");
        $this->con->Actualizar($tabla,$campos,$tipodatos,$valores,$campos_condicion,$campos_condicion_valor,$tipodatos_condicion);
        if($this->con->getError() == '1'){
            throw new Exception($this->con->getMensajeError());
        }
    }
}
