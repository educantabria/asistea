<?php

/**
 * Clase Login que extiende de SessionController.
 */
class Login extends SessionController
{
    /**
     * Constructor de la clase Login.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Método para renderizar la vista de login.
     */
    function render()
    {
        $actual_link = trim("$_SERVER[REQUEST_URI]");
        $url = explode('/', $actual_link);
        $this->view->errorMessage = '';
        $this->view->render('login/index');
    }

    /**
     * Método para autenticar el usuario.
     */
    function authenticate()
    {
        if ($this->existPOST(['username', 'password'])) {
            $username = $this->getPost('username');
            $password = $this->getPost('password');

            // Validar datos
            if ($username == '' || empty($username) || $password == '' || empty($password)) {
                error_log('Login::authenticate() empty');
                $this->redirect('', ['error' => Errors::ERROR_LOGIN_AUTHENTICATE_EMPTY]);
                return;
            }

            // Si el inicio de sesión es exitoso, devuelve solo el ID del usuario
            $user = $this->model->login($username, $password);

            if ($user != NULL) {
                // Inicializa el proceso de las sesiones
                error_log('Login::authenticate() passed');
                $this->initialize($user);
            } else {
                // Error al autenticar, intentar de nuevo
                error_log('Login::authenticate() username and/or password wrong');
                $this->redirect('', ['error' => Errors::ERROR_LOGIN_AUTHENTICATE_DATA]);
                return;
            }
        } else {
            // Error, cargar vista con errores
            error_log('Login::authenticate() error with params');
            $this->redirect('', ['error' => Errors::ERROR_LOGIN_AUTHENTICATE]);
        }
    }
   
}

