<?php

/**
 * Clase CategoriesModel que extiende de la clase Model e implementa la interfaz IModel.
 */
class CategoriesModel extends Model implements IModel {

    /** @var int $id Identificador de la categoría. */
    private $id;

    /** @var string $name Nombre de la categoría. */
    private $name;

    /** @var string $color Color de la categoría. */
    private $color;

    /**
     * Constructor de la clase CategoriesModel.
     *
     * Llama al constructor de la clase base (Model).
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     *
     * @return bool Devuelve true si la operación fue exitosa, de lo contrario, false.
     */
    public function save(){
        try{
            $query = $this->prepare('INSERT INTO categories (name, color) VALUES(:name, :color)');
            $query->execute([
                'name' => $this->name, 
                'color' => $this->color
            ]);
            if($query->rowCount()) return true;

            return false;
        }catch(PDOException $e){
            return false;
        }
    }

    /**
     * Obtiene todas las categorías de la base de datos.
     *
     * @return array Arreglo de objetos CategoriesModel.
     */
    public function getAll(){
        $items = [];

        try{
            $query = $this->query('SELECT * FROM categories');

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new CategoriesModel();
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            echo $e;
        }
    }
    
    /**
     * Obtiene una categoría específica por su ID.
     *
     * @param int $id ID de la categoría.
     * @return CategoriesModel|bool Retorna un objeto CategoriesModel si la operación fue exitosa, de lo contrario, false.
     */
    public function get($id){
        try{
            $query = $this->prepare('SELECT * FROM categories WHERE id = :id');
            $query->execute([ 'id' => $id]);
            $category = $query->fetch(PDO::FETCH_ASSOC);

            $this->from($category);

            return $this;
        }catch(PDOException $e){
            return false;
        }
    }

    /**
     * Elimina una categoría de la base de datos por su ID.
     *
     * @param int $id ID de la categoría a eliminar.
     * @return bool Retorna true si la operación fue exitosa, de lo contrario, false.
     */
    public function delete($id){
        try{
            $query = $this->db->connect()->prepare('DELETE FROM categories WHERE id = :id');
            $query->execute([ 'id' => $id]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    /**
     * Actualiza la información de una categoría en la base de datos.
     *
     * @return bool Retorna true si la operación fue exitosa, de lo contrario, false.
     */
    public function update(){
        try{
            $query = $this->db->connect()->prepare('UPDATE categories SET name = :name, color = :color WHERE id = :id');
            $query->execute([
                'name' => $this->name, 
                'color' => $this->color
            ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    /**
     * Verifica si una categoría con el mismo nombre ya existe en la base de datos.
     *
     * @param string $name Nombre de la categoría a verificar.
     * @return bool Retorna true si la categoría ya existe, de lo contrario, false.
     */
    public function exists($name){
        try{
            $query = $this->prepare('SELECT name FROM categories WHERE name = :name');
            $query->execute( ['name' => $name]);
            
            if($query->rowCount() > 0){
                error_log('CategoriesModel::exists() => true');
                return true;
            }else{
                error_log('CategoriesModel::exists() => false');
                return false;
            }
        }catch(PDOException $e){
            error_log($e);
            return false;
        }
    }

    /**
     * Convierte un arreglo de datos en propiedades de la categoría.
     *
     * @param array $array Arreglo de datos de la categoría.
     */
    public function from($array){
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->color = $array['color'];
    }

    /**
     * Establece el ID de la categoría.
     *
     * @param int $id ID de la categoría.
     */
    public function setId($id){$this->id = $id;}

    /**
     * Establece el nombre de la categoría.
     *
     * @param string $name Nombre de la categoría.
     */
    public function setName($name){$this->name = $name;}

    /**
     * Establece el color de la categoría.
     *
     * @param string $color Color de la categoría.
     */
    public function setColor($color){$this->color = $color;}

    /**
     * Obtiene el ID de la categoría.
     *
     * @return int ID de la categoría.
     */
    public function getId(){return $this->id;}

    /**
     * Obtiene el nombre de la categoría.
     *
     * @return string Nombre de la categoría.
     */
    public function getName(){ return $this->name;}

    /**
     * Obtiene el color de la categoría.
     *
     * @return string Color de la categoría.
     */
    public function getColor(){ return $this->color;}
}
