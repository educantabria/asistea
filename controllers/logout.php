<?php

/**
 * Clase Logout que extiende de SessionController.
 */
class Logout extends SessionController
{
    /**
     * Constructor de la clase Logout.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Método para renderizar la vista de logout y cerrar sesión.
     */
    public function render()
    {
        // Cerrar sesión
        $this->logout();

        // Redirigir a la página principal
        $this->redirect('');
    }
}

