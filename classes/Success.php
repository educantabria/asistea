<?php

/**
 * Clase que gestiona los mensajes de éxito del sistema.
 */
class Success{
   
   /**
     * @var string Código de éxito para la creación de una nueva categoría por parte del administrador.
     */ 
    const SUCCESS_ADMIN_NEWCATEGORY     = "f52228665c4f14c8695b194f670b0ef1";
    /**
     * @var string Código de éxito para la eliminación de gastos.
     */
    const SUCCESS_EXPENSES_DELETE       = "fcd919285d5759328b143801573ec47d";
    /**
     * @var string Código de éxito para el registro de nuevos gastos.
     */
    const SUCCESS_EXPENSES_NEWEXPENSE   = "fbbd0f23184e820e1df466abe6102955";
    /**
     * @var string Código de éxito para la actualización del presupuesto del usuario.
     */
    const SUCCESS_USER_UPDATEBUDGET     = "2ee085ac8828407f4908e4d134195e5c";
    /**
     * @var string Código de éxito para la actualización del nombre del usuario.
     */
    const SUCCESS_USER_UPDATENAME       = "6fb34a5e4118fb823636ca24a1d21669";
    /**
     * @var string Código de éxito para la actualización de la contraseña del usuario.
     */
    const SUCCESS_USER_UPDATEPASSWORD       = "6fb34a5e4118fb823636ca24a1d21669";
    /**
     * @var string Código de éxito para la actualización de la foto del usuario.
     */
    const SUCCESS_USER_UPDATEPHOTO       = "edabc9e4581fee3f0056fff4685ee9a8";
    /**
     * @var string Código de éxito para el registro de nuevos usuarios.
     */
    const SUCCESS_SIGNUP_NEWUSER       = "8281e04ed52ccfc13820d0f6acb0985a";
    
    /**
     * @var array Lista de mensajes de éxito.
     */
    private $successList = [];

    public function __construct()
    {
        $this->successList = [
            Success::SUCCESS_ADMIN_NEWCATEGORY => "Nueva categoría creada correctamente",
            Success::SUCCESS_EXPENSES_DELETE => "Gasto eliminado correctamente",
            Success::SUCCESS_EXPENSES_NEWEXPENSE => "Nuevo gasto registrado correctamente",
            Success::SUCCESS_USER_UPDATEBUDGET => "Presupuesto actualizado correctamente",
            Success::SUCCESS_USER_UPDATENAME => "Nombre actualizado correctamente",
            Success::SUCCESS_USER_UPDATEPASSWORD => "Contraseña actualizado correctamente",
            Success::SUCCESS_USER_UPDATEPHOTO => "Imagen de usuario actualizada correctamente",
            Success::SUCCESS_SIGNUP_NEWUSER => "Usuario registrado correctamente"
        ];
    }

    /**
     * Obtiene el mensaje de éxito asociado al código hash.
     *
     * @param string $hash Código hash del mensaje de éxito.
     * @return string Mensaje de éxito correspondiente al código hash.
     */
    function get($hash){
        return $this->successList[$hash];
    }

    /**
     * Verifica si existe una clave en la lista de mensajes de éxito.
     *
     * @param string $key Clave a verificar.
     * @return bool Retorna true si la clave existe, false en caso contrario.
     */
    function existsKey($key){
        if(array_key_exists($key, $this->successList)){
            return true;
        }else{
            return false;
        }
    }
}