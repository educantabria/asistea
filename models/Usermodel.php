<?php

/**
 * Clase UserModel que extiende de la clase Model e implementa la interfaz IModel.
 */
class UserModel extends Model implements IModel{

     /**
     * @var int Identificador del usuario.
     */
    private $id;
    /**
     * @var string Nombre de usuario.
     */
    private $username;
     /**
     * @var string Contraseña del usuario.
     */
    private $password;
    /**
     * @var string Rol del usuario.
     */
    private $role;
     /**
     * @var float Presupuesto del usuario.
     */
    private $budget;
    /**
     * @var string Ruta de la foto del usuario.
     */
    private $photo;
     /**
     * @var string Nombre del usuario.
     */
    private $name;

     /**
     * Constructor de la clase UserModel.
     *
     * Llama al constructor de la clase base (Model) e inicializa algunas propiedades.
     */
    public function __construct(){
        parent::__construct();

        $this->username = '';
        $this->password = '';
        $this->role = '';
        $this->budget = 0.0;
        $this->photo = '';
        $this->name = '';
    }

     /**
     * Actualiza el presupuesto del usuario.
     *
     * @param float $budget Nuevo presupuesto.
     * @param int $iduser Identificador del usuario.
     * @return bool Devuelve true si la actualización fue exitosa, false en caso contrario.
     */
    function updateBudget($budget, $iduser){
        try{
            $query = $this->db->connect()->prepare('UPDATE users SET budget = :val WHERE id = :id');
            $query->execute(['val' => $budget, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

     /**
     * Actualiza el nombre del usuario.
     *
     * @param string $name Nuevo nombre.
     * @param int $iduser Identificador del usuario.
     * @return bool Devuelve true si la actualización fue exitosa, false en caso contrario.
     */
    function updateName($name, $iduser){
        try{
            $query = $this->db->connect()->prepare('UPDATE users SET name = :val WHERE id = :id');
            $query->execute(['val' => $name, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

    function updatePhoto($name, $iduser){
        try{
            $query = $this->db->connect()->prepare('UPDATE users SET photo = :val WHERE id = :id');
            $query->execute(['val' => $name, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

    function updatePassword($new, $iduser){
        try{
            $hash = password_hash($new, PASSWORD_DEFAULT, ['cost' => 10]);
            $query = $this->db->connect()->prepare('UPDATE users SET password = :val WHERE id = :id');
            $query->execute(['val' => $hash, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

    /**
 * Compara la contraseña actual con la almacenada en la base de datos para un usuario dado.
 *
 * @param string $current Contraseña actual que se quiere comparar.
 * @param int $userid Identificador único del usuario.
 * @return bool|null Devuelve true si las contraseñas coinciden, false si no coinciden, y null en caso de error.
 */
    function comparePasswords($current, $userid){
        try{
            // Prepara y ejecuta la consulta para obtener la contraseña almacenada del usuario.       
            $query = $this->db->connect()->prepare('SELECT id, password FROM USERS WHERE id = :id');
             // Obtiene la fila asociada al usuario.
            $query->execute(['id' => $userid]);
            // Compara la contraseña actual con la almacenada y devuelve el resultado.            
            if($row = $query->fetch(PDO::FETCH_ASSOC)) return password_verify($current, $row['password']);
            // Si no se encuentra el usuario, devuelve NULL.
            return NULL;
        }catch(PDOException $e){
            // En caso de error, devuelve NULL.
            return NULL;
        }
    }

     /**
     * Guarda un nuevo usuario en la base de datos.
     *
     * @return bool Devuelve true si la operación fue exitosa, false en caso contrario.
     */
    public function save(){
        try{
            $query = $this->prepare('INSERT INTO users (username, password, role, budget, photo, name) VALUES(:username, :password, :role, :budget, :photo, :name )');
            $query->execute([
                'username'  => $this->username, 
                'password'  => $this->password,
                'role'      => $this->role,
                'budget'    => $this->budget,
                'photo'     => $this->photo,
                'name'      => $this->name
                ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    } 

     /**
     * Obtiene todos los usuarios.
     *
     * @return array Arreglo de objetos UserModel.
     */
    public function getAll(){
        $items = [];

        try{
            $query = $this->query('SELECT * FROM users');

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new UserModel();
                $item->setId($p['id']);
                $item->setUsername($p['username']);
                $item->setPassword($p['password'], false);
                $item->setRole($p['role']);
                $item->setBudget($p['budget']);
                $item->setPhoto($p['photo']);
                $item->setName($p['name']);
                

                array_push($items, $item);
            }
            return $items;


        }catch(PDOException $e){
            echo $e;
        }
    }

    /**
     *  Gets an item
     */
    public function get($id){
        try{
            $query = $this->prepare('SELECT * FROM users WHERE id = :id');
            $query->execute([ 'id' => $id]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->password = $user['password'];
            $this->role = $user['role'];
            $this->budget = $user['budget'];
            $this->photo = $user['photo'];
            $this->name = $user['name'];

            return $this;
        }catch(PDOException $e){
            return false;
        }
    }

    /**
    * Elimina un usuario de la base de datos según su identificador único.
    *
    * @param int $id Identificador único del usuario que se desea eliminar.
    * @return bool Devuelve true si la eliminación fue exitosa, false si falla.
    */
    public function delete($id){
        try{
            // Prepara y ejecuta la consulta para eliminar el usuario por su identificador único.       
            $query = $this->prepare('DELETE FROM users WHERE id = :id');
            $query->execute([ 'id' => $id]);
            // Devuelve true si la eliminación fue exitosa.
            return true;
        }catch(PDOException $e){
            // En caso de error, muestra el mensaje de error y devuelve false.        
            echo $e;
            return false;
        }
    }

    /**
 * Actualiza la información de un usuario en la base de datos.
 *
 * @return bool Devuelve true si la actualización fue exitosa, false si falla.
 */
public function update() {
    try {
        // Prepara y ejecuta la consulta para actualizar la información del usuario.
        $query = $this->prepare('UPDATE users SET username = :username, password = :password, budget = :budget, photo = :photo, name = :name WHERE id = :id');
        $query->execute([
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'budget' => $this->budget,
            'photo' => $this->photo,
            'name' => $this->name
        ]);

        // Devuelve true si la actualización fue exitosa.
        return true;
    } catch (PDOException $e) {
        // En caso de error, muestra el mensaje de error y devuelve false.
        echo $e;
        return false;
    }
}

/**
 * Verifica si un nombre de usuario ya existe en la base de datos.
 *
 * @param string $username Nombre de usuario que se desea verificar.
 * @return bool Devuelve true si el nombre de usuario existe, false si no existe o hay un error.
 */
public function exists($username) {
    try {
        // Prepara y ejecuta la consulta para verificar la existencia del nombre de usuario.
        $query = $this->prepare('SELECT username FROM users WHERE username = :username');
        $query->execute(['username' => $username]);

        // Devuelve true si el nombre de usuario existe.
        return $query->rowCount() > 0;
    } catch (PDOException $e) {
        // En caso de error, muestra el mensaje de error y devuelve false.
        echo $e;
        return false;
    }
}

     /**
     * Establece las propiedades de la instancia a partir de un array.
     *
     * @param array $array Arreglo con los datos del usuario.
     */
    public function from($array){
        $this->id = $array['id'];
        $this->username = $array['username'];
        $this->password = $array['password'];
        $this->role = $array['role'];
        $this->budget = $array['budget'];
        $this->photo = $array['photo'];
        $this->name = $array['name'];
    }

    /**
 * Genera el hash de una contraseña utilizando el algoritmo de hash por defecto.
 *
 * @param string $password Contraseña a ser hasheada.
 * @return string Devuelve la contraseña hasheada.
 */
    private function getHashedPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
}


    public function setUsername($username){ $this->username = $username;}
    //FIXME: validar si se requiere el parametro de hash
    public function setPassword($password, $hash = true){ 
        if($hash){
            $this->password = $this->getHashedPassword($password);
        }else{
            $this->password = $password;
        }
    }
    public function setId($id){             $this->id = $id;}
    public function setRole($role){         $this->role = $role;}
    public function setBudget($budget){     $this->budget = $budget;}
    public function setPhoto($photo){       $this->photo = $photo;}
    public function setName($name){         $this->name = $name;}

    public function getId(){        return $this->id;}
    public function getUsername(){  return $this->username;}
    public function getPassword(){  return $this->password;}
    public function getRole(){      return $this->role;}
    public function getBudget(){    return $this->budget;}
    public function getPhoto(){     return $this->photo;}
    public function getName(){      return $this->name;}
}