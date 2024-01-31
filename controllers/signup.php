<?php

/**
 * Clase Signup que extiende de SessionController.
 */
class Signup extends SessionController
{
    /**
     * Constructor de la clase Signup.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Método para renderizar la vista de registro.
     */
    function render()
    {
        $this->view->errorMessage = '';
        $this->view->render('login/signup');
    }

    /**
     * Método para registrar un nuevo usuario.
     */
    function newUser()
    {
        if ($this->existPOST(['username', 'password'])) {
            
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            
            // Validar datos
            if ($username == '' || empty($username) || $password == '' || empty($password)) {
                // Campos vacíos, redirigir con error
                $this->redirect('signup', ['error' => Errors::ERROR_SIGNUP_NEWUSER_EMPTY]);
            }

            $user = new UserModel();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setRole("user");
            
            if ($user->exists($username)) {
                // Usuario ya existe, redirigir con error
                $this->redirect('signup', ['error' => Errors::ERROR_SIGNUP_NEWUSER_EXISTS]);
            } else if ($user->save()) {
                // Usuario registrado con éxito, redirigir con mensaje de éxito
                $this->redirect('', ['success' => Success::SUCCESS_SIGNUP_NEWUSER]);
            } else {
                // Error al registrar usuario, redirigir con error
                $this->redirect('signup', ['error' => Errors::ERROR_SIGNUP_NEWUSER]);
            }
        } else {
            // Error, cargar vista con errores
            $this->redirect('signup', ['error' => Errors::ERROR_SIGNUP_NEWUSER_EXISTS]);
        }
    }
}
