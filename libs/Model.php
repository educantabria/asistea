<?php

include_once 'libs/imodel.php';

/**
 * Clase base para modelos.
 */
class Model {

    /**
     * Instancia de la base de datos.
     *
     * @var Database
     */
    protected $db;

    /**
     * Constructor de la clase Model.
     */
    function __construct(){
        $this->db = new Database();
    }

    /**
     * Ejecuta una consulta en la base de datos.
     *
     * @param string $query Consulta SQL.
     * @return PDOStatement|false Objeto PDOStatement o false si ocurre un error.
     */
    function query($query){
        return $this->db->connect()->query($query);
    }

    /**
     * Prepara una consulta para su ejecución y devuelve un objeto statement.
     *
     * @param string $query Consulta SQL.
     * @return PDOStatement|false Objeto PDOStatement o false si ocurre un error.
     */
    function prepare($query){
        return $this->db->connect()->prepare($query);
    }
}
