<?php

/**
 * Clase View para manejar la presentación y mensajes en la aplicación.
 */
class View {

    /**
     * Datos que se pasan a la vista.
     *
     * @var array
     */
    private $d;

    /**
     * Constructor de la clase View.
     */
    function __construct(){
    }

    /**
     * Renderiza la vista con los datos proporcionados.
     *
     * @param string $nombre Nombre de la vista.
     * @param array $data Datos a pasar a la vista.
     * @return void
     */
    function render($nombre, $data = []){
        $this->d = $data;
        
        $this->handleMessages();
        
        require 'views/' . $nombre . '.php';
    }
    
    /**
     * Maneja los mensajes de éxito y error.
     *
     * @return void
     */
    private function handleMessages(){
        if(isset($_GET['success']) && isset($_GET['error'])){
            // no se muestra nada porque no puede haber un error y success al mismo tiempo
        } else if(isset($_GET['success'])){
            $this->handleSuccess();
        } else if(isset($_GET['error'])){
            $this->handleError();
        }
    }

    /**
     * Maneja los mensajes de error.
     *
     * @return void
     */
    private function handleError(){
        if(isset($_GET['error'])){
            $hash = $_GET['error'];
            $errors = new Errors();

            if($errors->existsKey($hash)){
                error_log('View::handleError() existsKey =>' . $errors->get($hash));
                $this->d['error'] = $errors->get($hash);
            } else {
                $this->d['error'] = NULL;
            }
        }
    }

    /**
     * Maneja los mensajes de éxito.
     *
     * @return void
     */
    private function handleSuccess(){
        if(isset($_GET['success'])){
            $hash = $_GET['success'];
            $success = new Success();

            if($success->existsKey($hash)){
                error_log('View::handleError() existsKey =>' . $success->existsKey($hash));
                $this->d['success'] = $success->get($hash);
            } else {
                $this->d['success'] = NULL;
            }
        }
    }

    /**
     * Muestra los mensajes de éxito y error.
     *
     * @return void
     */
    public function showMessages(){
        $this->showError();
        $this->showSuccess();
    }

    /**
     * Muestra el mensaje de error.
     *
     * @return void
     */
    public function showError(){
        if(array_key_exists('error', $this->d)){
            echo '<div class="error">'.$this->d['error'].'</div>';
        }
    }

    /**
     * Muestra el mensaje de éxito.
     *
     * @return void
     */
    public function showSuccess(){
        if(array_key_exists('success', $this->d)){
            echo '<div class="success">'.$this->d['success'].'</div>';
        }
    }
}
