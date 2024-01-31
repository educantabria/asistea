<?php

/**
 * Función para mostrar mensajes de error.
 *
 * @param string $message Mensaje de error a mostrar.
 * @return void
 */
function showError($message){
    echo "<span class='error'>$message</span>";
}

/**
 * Función para mostrar mensajes de información.
 *
 * @param string $message Mensaje de información a mostrar.
 * @return void
 */
function showInfo($message){
    echo "<span class='info'>$message</span>";
}

/**
 * Función para mostrar mensajes de éxito.
 *
 * @param string $message Mensaje de éxito a mostrar.
 * @return void
 */
function showSuccess($message){
    echo "<span class='success'>$message</span>";
}
