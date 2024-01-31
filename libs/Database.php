<?php

/**
 * Clase para gestionar la conexión a la base de datos.
 */
class Database
{
    /**
     * @var string Dirección del host de la base de datos.
     */
    private $host;

    /**
     * @var string Nombre de la base de datos.
     */
    private $db;

    /**
     * @var string Nombre de usuario para la conexión a la base de datos.
     */
    private $user;

    /**
     * @var string Contraseña para la conexión a la base de datos.
     */
    private $password;

    /**
     * @var string Conjunto de caracteres para la conexión a la base de datos.
     */
    private $charset;

    /**
     * Constructor de la clase Database.
     */
    public function __construct()
    {
        $this->host = constant('HOST');
        $this->db = constant('DB');
        $this->user = constant('USER');
        $this->password = constant('PASSWORD');
        $this->charset = constant('CHARSET');
    }

    /**
     * Establece la conexión a la base de datos.
     *
     * @return PDO|false Retorna una instancia de PDO si la conexión es exitosa, false en caso de error.
     */
    function connect()
    {
        try {
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new PDO($connection, $this->user, $this->password, $options);
            return $pdo;
        } catch (PDOException $e) {
            print_r('Error de conexión: ' . $e->getMessage());
            return false;
        }
    }
}
