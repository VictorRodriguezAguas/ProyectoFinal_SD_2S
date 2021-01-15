<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArchivosBO
 *
 * @author ernesto.ruales
 */
include_once '../util/basedatos.php';
include_once '../dao/ConsultasDAO.php';

class ArchivosBO {

    //put your code here
    public static $target_path = "archivos/";

    public static function guardarArchivo($fileName, $nombre) {

        $url = ArchivosBO::getUrl();
        if (move_uploaded_file($fileName, $url . $nombre)) {
            return "OK";
        } 
        else {
            return "Ha ocurrido un error, trate de nuevo!" . $url;
        }
    }

    public static function guardarArchivoBase64($baseImagen, $nameImage) {
        //$url = URL::getUrlLibreria();
        $url = ArchivosBO::getUrl();
        $baseImagen = str_replace("data:image/png;base64,", "", $baseImagen);
        $bin = base64_decode($baseImagen);
        $im = imageCreateFromString($bin);
        imagepng($im, $url . $nameImage, 0);
        if (!$im) {
            return false;
        }
        return true;
    }

    public static function getImagenBase64($fileName) {
        $im = file_get_contents($fileName);
        $imdata = base64_encode($im);
        print $imdata;
    }

    public static function getURLArchivo() {
        $newUrl = URL::getUrlLibreria();
        $url = $newUrl . '../' . ArchivosBO::$target_path;
        return $url;
    }

    public static function crearZip($filename, $archivos) {
        $zip = new ZipArchive();
        if ($zip->open(ArchivosBO::getURLArchivo() . $filename, ZipArchive::CREATE) !== TRUE) {
            return null;
        }
        forEach ($archivos as &$file) {
            $zip->addFile(ArchivosBO::getURLArchivo() . $file->archivo, $file->nombre);
        }
        return ArchivosBO::getURLArchivo() . $filename;
    }

    public static function getUrl() {
        $conexion = new basedatos();
        try {
            $consultasDAO = new ConsultasDAO();
            $conexion->Conectar();
            $consultasDAO->setConexion($conexion);
            $url = $consultasDAO->getParamSystem("RUTA_ARCHIVO");
            if (is_null($url)) {
                $url = URL::getUrlLibreria() . ArchivosBO::$target_path;
            }
            return $url;
        } finally {
            $conexion->CerrarConexion();
        }
    }

}
