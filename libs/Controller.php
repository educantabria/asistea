<?php

/**
 * Clase base para controladores.
 */
class Controller
{
    /**
     * @var View Instancia de la clase View.
     */
    public $view;

    /**
     * @var Model Instancia del modelo asociado al controlador.
     */
    public $model;

    /**
     * Constructor de la clase Controller.
     */
    function __construct()
    {
        $this->view = new View();
    }

    /**
     * Carga un modelo específico.
     *
     * @param string $model Nombre del modelo.
     * 
     * @return void
     */
    function loadModel($model)
    {
        $url = 'models/' . $model . 'model.php';

        if (file_exists($url)) {
            require_once $url;

            $modelName = $model . 'Model';
            $this->model = new $modelName();
        }
    }

    /**
     * Verifica la existencia de parámetros en la superglobal $_POST.
     *
     * @param array $params Lista de nombres de parámetros.
     * 
     * @return bool true si todos los parámetros existen, false de lo contrario.
     */
    function existPOST($params)
    {
        foreach ($params as $param) {
            if (!isset($_POST[$param])) {
                error_log("ExistPOST: No existe el parámetro $param");
                return false;
            }
        }
        error_log("ExistPOST: Existen parámetros");
        return true;
    }

    /**
     * Verifica la existencia de parámetros en la superglobal $_GET.
     *
     * @param array $params Lista de nombres de parámetros.
     * 
     * @return bool true si todos los parámetros existen, false de lo contrario.
     */
    function existGET($params)
    {
        foreach ($params as $param) {
            if (!isset($_GET[$param])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Obtiene el valor de un parámetro de la superglobal $_GET.
     *
     * @param string $name Nombre del parámetro.
     * 
     * @return mixed Valor del parámetro.
     */
    function getGet($name)
    {
        return $_GET[$name];
    }

    /**
     * Obtiene el valor de un parámetro de la superglobal $_POST.
     *
     * @param string $name Nombre del parámetro.
     * 
     * @return mixed Valor del parámetro.
     */
    function getPost($name)
    {
        return $_POST[$name];
    }

    /**
     * Redirige a una URL específica con mensajes adicionales en la URL.
     *
     * @param string $url      URL a la que se redirigirá.
     * @param array  $mensajes Lista de mensajes para incluir en la URL como parámetros.
     * 
     * @return void
     */
    function redirect($url, $mensajes = [])
    {
        $data = [];
        $params = '';

        foreach ($mensajes as $key => $value) {
            array_push($data, $key . '=' . $value);
        }
        $params = join('&', $data);

        if ($params != '') {
            $params = '?' . $params;
        }
        header('location: ' . constant('URL') . $url . $params);
    }
}
