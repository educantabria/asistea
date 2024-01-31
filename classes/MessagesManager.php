<?php

/**
 * Clase MessagesManager para gestionar mensajes en la aplicación.
 */
class MessagesManager{

    /**
     * @var array Lista de mensajes asociados a códigos.
     */
    private $messagesList = [];

    /**
     * Constructor de la clase. Inicializa la lista de mensajes.
     */
    public function __construct()
    {

    }

    /**
     * Obtiene un mensaje asociado a un código.
     *
     * @param string $hash Código del mensaje.
     * @return string Mensaje asociado al código.
     */
    function get($hash){
        return $this->messagesList[$hash];
    }

    /**
     * Verifica si un código de mensaje existe en la lista.
     *
     * @param string $key Código del mensaje.
     * @return bool true si el código de mensaje existe, false en caso contrario.
     */
    function existsKey($key){
        if(array_key_exists($key, $this->messagesList)){
            return true;
        }else{
            return false;
        }
    }
}