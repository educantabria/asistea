<?php

/**
 * Clase LoginModel que extiende de la clase Model.
 */
class LoginModel extends Model {

    /**
     * Constructor de la clase LoginModel.
     *
     * Llama al constructor de la clase base (Model).
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Realiza el inicio de sesión.
     *
     * @param string $username Nombre de usuario.
     * @param string $password Contraseña.
     * @return UserModel|null Objeto UserModel si la autenticación es exitosa, de lo contrario, NULL.
     * @throws PDOException Si ocurre un error al interactuar con la base de datos.
     */
    public function login($username, $password) {
        
        error_log("login: inicio");
        try {
            
            $query = $this->prepare('SELECT * FROM users WHERE username = :username');
            $query->execute(['username' => $username]);
            
            if ($query->rowCount() == 1) {
                $item = $query->fetch(PDO::FETCH_ASSOC);

                $user = new UserModel();
                $user->from($item);

                error_log('login: user id '.$user->getId());

                if (password_verify($password, $user->getPassword())) {
                    error_log('login: success');
                    return $user;
                } else {
                    return NULL;
                }
            }
        } catch (PDOException $e) {
            return NULL;
        }
    }

}
