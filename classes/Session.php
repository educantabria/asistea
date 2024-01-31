<?php

/**
 * Clase Session para gestionar sesiones de usuario.
 */
class Session {

    /**
     * @var string Nombre de la variable de sesión para el usuario.
     */
    private $sessionName = 'user';

    /**
     * Constructor de la clase. Inicia la sesión si aún no ha sido iniciada.
     */
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Establece el usuario actual en la sesión.
     *
     * @param mixed $user Datos del usuario a almacenar en la sesión.
     */
    public function setCurrentUser($user) {
        $_SESSION[$this->sessionName] = $user;
    }

    /**
     * Obtiene el usuario actual almacenado en la sesión.
     *
     * @return mixed Datos del usuario almacenado en la sesión.
     */
    public function getCurrentUser() {
        return $_SESSION[$this->sessionName];
    }

    /**
     * Cierra la sesión, eliminando todas las variables de sesión.
     */
    public function closeSession() {
        session_unset();
        session_destroy();
    }

    /**
     * Verifica si existe un usuario en la sesión actual.
     *
     * @return bool true si existe un usuario en la sesión, false en caso contrario.
     */
    public function exists() {
        return isset($_SESSION[$this->sessionName]);
    }
}
