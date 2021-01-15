<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuBO
 *
 * @author ernesto.ruales
 */
//require_once '../dao/MenuDAO.php';
//require_once '../util/General.php';
//require_once '../bo/BO.php';
require_once '../util/General.php';
require_once '../dao/MenuDAO.php';
require_once '../bo/BO.php';

class MenuBO extends BO {

    //put your code here
    public function getMenuPorUsuario($idUsuario, $id_aplicacion, $id_peril=null) {
        $this->conectar();
        $menuDAO = new MenuDAO();
        $menuDAO->setConexion($this->conexion);
        $lista = $menuDAO->getMenuPorUsuario($idUsuario, $id_aplicacion, $id_peril);
        $lista = $this->getMenuP($lista);
        $this->cerrarConexion();
        return General::setRespuestaOK($lista);
    }

    private function getMenuP($lista) {
        $listaMenu = array();
        $i = 0;
        foreach ($lista as &$menu) {
            if (is_null($menu->id_menu_padre)) {
                if ($menu->agregado == 'NO') {
                    $menu->menus = $this->armarMenu($lista, $menu);
                    $menu->children = $menu->menus;
                    $listaMenu[$i] = $menu;
                    $menu->agregado = 'SI';
                    $i++;
                }
            }
        }
        return $listaMenu;
    }

    private function armarMenu($lista, $menuPadre) {
        $listaMenu = array();
        $i = 0;
        foreach ($lista as &$menu) {
            if ($menu->id_menu_padre == $menuPadre->id) {
                if ($menu->agregado == 'NO') {
                    $menu->menus = $this->armarMenu($lista, $menu);
                    $menu->children = $menu->menus;
                    $listaMenu[$i] = $menu;
                    $menu->agregado = 'SI';
                    $i++;
                }
            }
        }
        return $listaMenu;
    }

    public function imprimirMenu($listaMenu) {
        $menu = "";
        foreach ($listaMenu as &$menuItem) {
            $ocultar = "";
            $icono = "";
            if (!is_null($menuItem->icono))
                $icono = "<span class='pcoded-micon'><i class='$menuItem->icono'></i></span>";
            /* if($menuItem->id=='5' || $menuItem->id_menu_padre == '5')
              $ocultar=" style='display:none'"; */
            if (is_null($menuItem->id_menu_padre)) {
                $menu .= "<li class='nav-item pcoded-menu-caption'$ocultar>";
                $menu .= "<label>$menuItem->menu</label>";
                $menu .= "</li>";
                $menu .= $this->imprimirMenu($menuItem->menus);
                continue;
            }

            if (count($menuItem->menus) > 0) {
                $menu .= "<li class='nav-item pcoded-hasmenu'$ocultar>";
                if (is_null($menuItem->url))
                    $menu .= "<a class='nav-link'><span class='pcoded-micon'>$icono</span><span class='pcoded-mtext'>$menuItem->menu</span></a>";
                else {
                    $menu .= "<a onclick=\"menu_page('$menuItem->url')\" class='nav-link'>$icono<span class='pcoded-mtext'>$menuItem->menu</span></a>";
                }
                //$menu .= "<a ><i class='fa fa-sitemap'></i>$menuItem->menu <span class='fa fa-chevron-down'></span></a>";
                $menu .= "<ul class='pcoded-submenu'>";
                $menu .= $this->imprimirMenu($menuItem->menus);
                $menu .= "</ul>";
            } else {
                $menu .= "<li class='nav-item'$ocultar>";
                $menu .= "<a onclick=\"menu_page('$menuItem->url')\" class='nav-link'>$icono<span class='pcoded-mtext'>$menuItem->menu</span></a>";
            }
            $menu .= "</li>";
        }
        return $menu;
    }

    public function grabarMenu() {
        $respuesta = General::validarParametros($_POST, ["datos"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $menu = json_decode($_POST["datos"]);
        if (General::tieneValor($menu, "id_menu")) {
            return $this->actualizarMenu($menu);
        } else {
            return $this->crearMenu($menu);
        }
    }

    public function crearMenu($menu) {
        try {
            $this->conectar();
            $menuDAO = new MenuDAO();
            $menuDAO->setConexion($this->conexion);

            General::setNullSql($menu, "id_menu_padre");
            General::setNullSql($menu, "url");
            General::setNullSql($menu, "icono");
            General::setNullSql($menu, "id_aplicacion");
            $menuDAO->insertarMenu($menu);
            $this->cerrarConexion();
            return General::setRespuestaOK("");
        } catch (Exception $e) {
            return General::setRespuestaError($e);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function actualizarMenu($menu) {
        try {
            $this->conectar();
            $menuDAO = new MenuDAO();
            $menuDAO->setConexion($this->conexion);

            General::setNullSql($menu, "id_menu_padre");
            General::setNullSql($menu, "url");
            General::setNullSql($menu, "icono");
            General::setNullSql($menu, "id_aplicacion");
            $menuDAO->actualizarMenu($menu);
            $this->cerrarConexion();
            return General::setRespuestaOK("");
        } catch (Exception $e) {
            return General::setRespuestaError($e);
        } finally {
            $this->cerrarConexion();
        }
    }

    public function getListaMenu() {
        $respuesta = General::validarParametros($_POST, ["estado"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $menuDAO = new MenuDAO();
        $menuDAO->setConexion($this->conexion);
        $data = $menuDAO->getListaMenu($_POST["estado"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function consultarMenu() {
        $respuesta = General::validarParametros($_POST, ["estado", "nombre"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $menuDAO = new MenuDAO();
        $menuDAO->setConexion($this->conexion);
        $data = $menuDAO->consultaMenu($_POST["nombre"], $_POST["estado"]);
        $this->cerrarConexion();
        return General::setRespuestaOK($data);
    }

    public function getMenuxPerfilSelected() {
        $respuesta = General::validarParametros($_POST, ["id_perfil", "id_aplicacion"]);
        if (!is_null($respuesta)) {
            return $respuesta;
        }
        $this->conectar();
        $menuDAO = new MenuDAO();
        $menuDAO->setConexion($this->conexion);
        $lista = $menuDAO->getMenuxPerfilSelected($_POST["id_perfil"], $_POST["id_aplicacion"]);
        $lista = $this->getMenuP($lista);
        $this->cerrarConexion();
        return General::setRespuestaOK($lista);
    }
}
