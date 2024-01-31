<?php

/**
 * Interfaz para modelos.
 */
interface IModel
{
    /**
     * Guarda los datos del modelo en la base de datos.
     *
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function save();

    /**
     * Obtiene todos los registros del modelo desde la base de datos.
     *
     * @return array Retorna un array con los registros del modelo.
     */
    public function getAll();

    /**
     * Obtiene un registro del modelo según su identificador.
     *
     * @param int $id Identificador del registro.
     * @return mixed Retorna el registro del modelo.
     */
    public function get($id);

    /**
     * Elimina un registro del modelo según su identificador.
     *
     * @param int $id Identificador del registro a eliminar.
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function delete($id);

    /**
     * Actualiza los datos del modelo en la base de datos.
     *
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function update();

    /**
     * Llena el modelo con datos provenientes de un array.
     *
     * @param array $array Array con los datos a asignar al modelo.
     * @return void
     */
    public function from($array);
}
