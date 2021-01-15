<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuDAO
 *
 * @author ernesto.ruales
 */
class MenuDAO {

    //put your code here
    private $con;

    function setConexion($con) {
        $this->con = $con;
    }

    function getMenuPorUsuario($idUsuario, $id_aplicacion, $id_perfil = null) {
        $sql = "select distinct c.id, c.nombre as menu, c.icono, c.url, c.estado, c.id_menu_padre, 'NO' as agregado
                from " . basedatos::$baseSeguridad . ".usuario_perfil a 
                inner join " . basedatos::$baseSeguridad . ".perfil_menu b 
                   on b.id_perfil = a.id_perfil 
                inner join " . basedatos::$baseSeguridad . ".menu c 
                   on c.id = b.id_menu 
                  and c.estado = 'A' 
                  and id_aplicacion =  $id_aplicacion
                where a.id_usuario = $idUsuario ";
        if (!is_null($id_perfil)) {
            $sql .="  and b.id_perfil = '$id_perfil'  ";
        }
        $sql .="  order by c.id_menu_padre, c.orden";
        return $this->con->getArraySQL($sql);
    }

    function getMenuxPerfilSelected($id_perfil, $id_aplicacion) {
        $sql = "SELECT a.id, a.nombre as item, true as expandable, (b.id_perfil is not null) as selected, id_menu_padre, 'NO' as agregado
            FROM " . basedatos::$baseSeguridad . ".menu a
           left outer join " . basedatos::$baseSeguridad . ".perfil_menu b on b.id_perfil = $id_perfil and b.id_menu = a.id
           where a.id_aplicacion = $id_aplicacion
           order by id_menu_padre, orden";
        return $this->con->getArraySQL($sql);
    }

    function insertarMenu($menu) {
        $sql = "INSERT INTO " . basedatos::$baseSeguridad . ".`menu`
            (`nombre`,`id_menu_padre`,`icono`,`url`,`id_aplicacion`, orden, estado)
            VALUES('$menu->nombre',$menu->id_menu_padre,$menu->icono,
                $menu->url,$menu->id_aplicacion,'$menu->orden', '$menu->estado');
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
        $id = mysqli_insert_id($this->con->getConexion());
        return $id;
    }

    function actualizarMenu($menu) {
        $sql = "UPDATE " . basedatos::$baseSeguridad . ".`menu` SET nombre = '$menu->nombre',
            id_menu_padre = $menu->id_menu_padre,
            icono = $menu->icono,
            url = $menu->url,
            id_aplicacion = $menu->id_aplicacion,
            orden = $menu->orden,
            estado = '$menu->estado'
            where id = $menu->id_menu
                ";
        mysqli_query($this->con->getConexion(), $sql) or die(mysqli_error($this->con->getConexion()));
    }

    function getListaMenu($estado) {
        $sql = "select a.*, a.menu as nombre, b.menu as menu_padre 
                from " . basedatos::$baseSeguridad . ".menu a 
                left outer join " . basedatos::$baseSeguridad . ".menu b on b.id = a.id_menu_padre
                where a.estado = '$estado' order by a.id_menu_padre, a.id";
        return $this->con->getArraySQL($sql);
    }

    function consultaMenu($nombre, $estado) {
        $sql = "select a.*, b.nombre as menu_padre, a.id as id_menu, c.nombre as aplicacion
                    from " . basedatos::$baseSeguridad . ".menu a 
                   left outer join " . basedatos::$baseSeguridad . ".menu b on b.id = a.id_menu_padre
                   left outer join " . basedatos::$baseSeguridad . ".aplicacion c on c.id = a.id_aplicacion
                  where a.estado = (case '$estado' when 'T' then a.estado else '$estado' end)
                    and a.nombre like '%$nombre%'
                  order by a.id_menu_padre, a.id";
        return $this->con->getArraySQL($sql);
    }

}
