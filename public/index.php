<?php
namespace App;

//Inicializo sesión para poder traspasar variables entre páginas
session_start();

//Incluyo los controladores que voy a utilizar para que seran cargados por Autoload
use App\Controller\AppController;
use App\Controller\JuegoController;
use App\Controller\PersonaController;

/*
 * Asigno a sesión las rutas de las carpetas public y home, necesarias tanto para las rutas como para
 * poder enlazar imágenes y archivos css, js
 */
$_SESSION['public'] = '/mi-cms/public/';
$_SESSION['home'] = $_SESSION['public'].'index.php/';

//Defino y llamo a la función que autocargará las clases cuando se instancien
spl_autoload_register('App\autoload');

function autoload($clase,$dir=null){

    //Directorio raíz de mi proyecto
    if (is_null($dir)){
        $dirname = str_replace('/public', '', dirname(__FILE__));
        $dir = realpath($dirname);
    }

    //Escaneo en busca de la clase de forma recursiva
    foreach (scandir($dir) as $file){
        //Si es un directorio (y no es de sistema) accedo y
        //busco la clase dentro de él
        if (is_dir($dir."/".$file) AND substr($file, 0, 1) !== '.'){
            autoload($clase, $dir."/".$file);
        }
        //Si es un fichero y el nombr conicide con el de la clase
        else if (is_file($dir."/".$file) AND $file == substr(strrchr($clase, "\\"), 1).".php"){
            require($dir."/".$file);
        }
    }

}

//Para invocar al controlador en cada ruta
function controlador($nombre=null){

    switch($nombre){
        default: return new AppController;
        case "juegos": return new JuegoController;
        case "personas": return new PersonaController;
    }

}

//Quito la ruta de la home a la que me están pidiendo
$ruta = str_replace($_SESSION['home'], '', $_SERVER['REQUEST_URI']);

//Encamino cada ruta al controlador y acción correspondientes
switch ($ruta){

    //Front-end
    case "":
    case "/":
        controlador()->index();
        break;
    case "acerca-de":
        controlador()->acercade();
        break;
    case "juegos":
        controlador()->juegos();
        break;
    case (strpos($ruta,"juego/") === 0):
        controlador()->juego(str_replace("juego/","",$ruta));
        break;

    //Back-end
    case "admin":
    case "admin/entrar":
        controlador("personas")->entrar();
        break;
    case "admin/salir":
        controlador("personas")->salir();
        break;
    case "admin/personas":
        controlador("personas")->index();
        break;
    case "admin/personas/crear":
        controlador("personas")->crear();
        break;
    case (strpos($ruta,"admin/personas/editar/") === 0):
        controlador("personas")->editar(str_replace("admin/personas/editar/","",$ruta));
        break;
    case (strpos($ruta,"admin/personas/activar/") === 0):
        controlador("personas")->activar(str_replace("admin/personas/activar/","",$ruta));
        break;
    case (strpos($ruta,"admin/personas/borrar/") === 0):
        controlador("personas")->borrar(str_replace("admin/personas/borrar/","",$ruta));
        break;
    case "admin/juegos":
        controlador("juegos")->index();
        break;
    case "admin/juegos/crear":
        controlador("juegos")->crear();
        break;
    case (strpos($ruta,"admin/juegos/editar/") === 0):
        controlador("juegos")->editar(str_replace("admin/juegos/editar/","",$ruta));
        break;
    case (strpos($ruta,"admin/juegos/activar/") === 0):
        controlador("juegos")->activar(str_replace("admin/juegos/activar/","",$ruta));
        break;
    case (strpos($ruta,"admin/juegos/home/") === 0):
        controlador("juegos")->home(str_replace("admin/juegos/home/","",$ruta));
        break;
    case (strpos($ruta,"admin/juegos/borrar/") === 0):
        controlador("juegos")->borrar(str_replace("admin/juegos/borrar/","",$ruta));
        break;
    case (strpos($ruta,"admin/") === 0):
        controlador("juegos")->entrar();
        break;

    //Resto de rutas
    default:
        controlador()->index();

}
