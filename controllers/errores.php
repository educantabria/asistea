<?php

/**
 * Clase Errores que extiende de Controller.
 */
class Errores extends Controller
{
    /**
     * Constructor de la clase Errores.
     */
    function __construct()
    {
        parent::__construct();
        $this->view->render('errores/index');
    }
}
