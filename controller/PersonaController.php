<?php
namespace App\Controller;

use App\Helper\ViewHelper;
use App\Helper\DbHelper;
use App\Model\Persona;


class PersonaController
{
    var $db;
    var $view;

    function __construct()
    {
        //Conexión a la BBDD
        $dbHelper = new DbHelper();
        $this->db = $dbHelper->db;

        //Instancio el ViewHelper
        $viewHelper = new ViewHelper();
        $this->view = $viewHelper;
    }

    public function admin(){

        echo "eoooeo";
        //Compruebo permisos
        $this->view->permisos();

        //LLamo a la vista
        $this->view->vista("admin","index");

    }

    public function entrar(){

        //Si ya está autenticado, le llevo a la página de inicio del panel
        if (isset($_SESSION['persona'])){

            echo "erwrwr";
            $this->admin();

        }
        //Si ha pulsado el botón de acceder, tramito el formulario
        else if (isset($_POST["acceder"])){

            //Recupero los datos del formulario
            $campo_usuario = filter_input(INPUT_POST, "persona", FILTER_SANITIZE_STRING);
            $campo_clave = filter_input(INPUT_POST, "clave", FILTER_SANITIZE_STRING);

            //Busco al persona en la base de datos
            $rowset = $this->db->query("SELECT * FROM personas WHERE persona='$campo_usuario' AND activo=1 LIMIT 1");

            //Asigno resultado a una instancia del modelo
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $persona = new Persona($row);

            //Si existe el persona
            if ($persona){
                //Compruebo la clave
                if (password_verify($campo_clave,$persona->clave)) {

                    //Asigno el persona y los permisos la sesión
                    $_SESSION["persona"] = $persona->persona;
                    $_SESSION["personas"] = $persona->personas;
                    $_SESSION["juegos"] = $persona->juegos;

                    //Guardo la fecha de último acceso
                    $ahora = new \DateTime("now", new \DateTimeZone("Europe/Madrid"));
                    $fecha = $ahora->format("Y-m-d H:i:s");
                    $this->db->exec("UPDATE personas SET fecha_acceso='$fecha' WHERE persona='$campo_usuario'");

                    echo "persona:".$_SESSION["persona"]."; personas:". $_SESSION["personas"]."; juegos:".$_SESSION["juegos"];
                    //Redirección con mensaje
                    $this->view->redireccionConMensaje("admin","green","Bienvenido al panel de administración.");


                }
                else{
                    //Redirección con mensaje
                    $this->view->redireccionConMensaje("admin","red","Contraseña incorrecta.");
                }
            }
            else{
                //Redirección con mensaje
                $this->view->redireccionConMensaje("admin","red","No existe ninguna persona con ese nombre.");
            }
        }
        //Le llevo a la página de acceso
        else{
            $this->view->vista("admin","personas/entrar");
        }

    }

    public function salir(){

        //Borro al persona de la sesión
        unset($_SESSION['persona']);

        //Redirección con mensaje
        $this->view->redireccionConMensaje("admin","green","Te has desconectado con éxito.");

    }

    //Listado de personas
    public function index(){

        //Permisos
        $this->view->permisos("personas");

        //Recojo los personas de la base de datos
        $rowset = $this->db->query("SELECT * FROM personas ORDER BY persona ASC");

        //Asigno resultados a un array de instancias del modelo
        $personas = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($personas,new Persona($row));
        }

        $this->view->vista("admin","personas/index", $personas);

    }

    //Para activar o desactivar
    public function activar($id){

        //Permisos
        $this->view->permisos("personas");

        //Obtengo el persona
        $rowset = $this->db->query("SELECT * FROM personas WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $persona = new Persona($row);

        if ($persona->activo == 1){

            //Desactivo el persona
            $consulta = $this->db->exec("UPDATE personas SET activo=0 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/personas","green","La persona <strong>$persona->persona</strong> se ha desactivado correctamente.") :
                $this->view->redireccionConMensaje("admin/personas","red","Hubo un error al guardar en la base de datos.");
        }

        else{

            //Activo el persona
            $consulta = $this->db->exec("UPDATE personas SET activo=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/personas","green","La persona <strong>$persona->persona</strong> se ha activado correctamente.") :
                $this->view->redireccionConMensaje("admin/personas","red","Hubo un error al guardar en la base de datos.");
        }

    }

    public function borrar($id){

        //Permisos
        $this->view->permisos("personas");

        //Borro el persona
        $consulta = $this->db->exec("DELETE FROM personas WHERE id='$id'");

        //Mensaje y redirección
        ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
            $this->view->redireccionConMensaje("admin/personas","green","La persona se ha borrado correctamente.") :
            $this->view->redireccionConMensaje("admin/personas","red","Hubo un error al guardar en la base de datos.");

    }

    public function crear(){

        //Permisos
        $this->view->permisos("personas");

        //Creo un nuevo persona vacío
        $persona = new Persona();

        //Llamo a la ventana de edición
        $this->view->vista("admin","personas/editar", $persona);

    }

    public function editar($id){

        //Permisos
        $this->view->permisos("personas");

        //Si ha pulsado el botón de guardar
        if (isset($_POST["guardar"])){

            //Recupero los datos del formulario
            $persona = filter_input(INPUT_POST, "persona", FILTER_SANITIZE_STRING);
            $clave = filter_input(INPUT_POST, "clave", FILTER_SANITIZE_STRING);
            $personas = (filter_input(INPUT_POST, 'personas', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;
            $juegos = (filter_input(INPUT_POST, 'juegos', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;
            $cambiar_clave = (filter_input(INPUT_POST, 'cambiar_clave', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;

            //Encripto la clave
            $clave_encriptada = ($clave) ? password_hash($clave,  PASSWORD_BCRYPT, ['cost'=>12]) : "";

            if ($id == "nuevo"){

                //Creo un nuevo persona
                $this->db->exec("INSERT INTO personas (persona, clave, juegos, personas) VALUES ('$persona','$clave_encriptada',$juegos,$personas)");

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/personas","green","La persona <strong>$persona</strong> se creado correctamente.");
            }
            else{

                //Actualizo el persona
                ($cambiar_clave) ?
                    $this->db->exec("UPDATE personas SET persona='$persona',clave='$clave_encriptada',juegos=$juegos,personas=$personas WHERE id='$id'") :
                    $this->db->exec("UPDATE personas SET persona='$persona',juegos=$juegos,personas=$personas WHERE id='$id'");

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/personas","green","La persona <strong>$persona</strong> se actualizado correctamente.");
            }
        }

        //Si no, obtengo persona y muestro la ventana de edición
        else{

            //Obtengo la persona
            $rowset = $this->db->query("SELECT * FROM personas WHERE id='$id' LIMIT 1");
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $persona = new Persona($row);

            //Llamo a la ventana de edición
            $this->view->vista("admin","personas/editar", $persona);
        }

    }


}