<?php

require_once 'controllers/errores.php';

/**
 * Clase principal de la aplicación.
 */
class App
{
    /**
     * Constructor de la clase App.
     */
    function __construct()
    {
        // Obtener la URL proporcionada.
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        // Cuando se ingresa sin definir controlador.
        if (empty($url[0])) {
            $archivoController = 'controllers/login.php';
            require_once $archivoController;
            $controller = new Login();
            $controller->loadModel('login');
            $controller->render();
            return false;
        }

        $archivoController = 'controllers/' . $url[0] . '.php';

        if (file_exists($archivoController)) {
            require_once $archivoController;

            // Inicializar controlador.
            $controller = new $url[0];
            $controller->loadModel($url[0]);

            // Si hay un método que se requiere cargar.
            if (isset($url[1])) {
                if (method_exists($controller, $url[1])) {
                    if (isset($url[2])) {
                        // El método tiene parámetros.
                        // Sacamos el número de parámetros.
                        $nparam = sizeof($url) - 2;
                        // Crear un arreglo con los parámetros.
                        $params = [];
                        // Iterar.
                        for ($i = 0; $i < $nparam; $i++) {
                            array_push($params, $url[$i + 2]);
                        }
                        // Pasarlos al método.
                        $controller->{$url[1]}($params);
                    } else {
                        $controller->{$url[1]}();
                    }
                } else {
                    $controller = new Errores();
                }
            } else {
                $controller->render();
            }
        } else {
            $controller = new Errores();
        }
    }
}
