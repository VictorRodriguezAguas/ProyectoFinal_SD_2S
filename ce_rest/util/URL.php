<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of URL
 *
 * @author ernesto.ruales
 */
class URL {

//put your code here

    public static function getUrlLibreria() {
        $url = $_SERVER['REQUEST_URI'];
        $u = explode('/', $url);
        $newUrl = "";
        $i = 0;
        foreach ($u as &$valor) {
            if ($i >= 3)
                $newUrl .= "../";
            $i++;
        }
        if ($i <= 2) {
            $newUrl .= "./";
        }
        return $newUrl;
    }

}
