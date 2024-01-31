<?php

/**
 * Clase JoinExpensesCategoriesModel que extiende de la clase Model.
 */
class JoinExpensesCategoriesModel extends Model{

    /** @var int Identificador del gasto. */
    private $expenseId;
    /** @var string Título del gasto. */
    private $title;
    /** @var float Cantidad del gasto. */
    private $amount;
    /** @var int Identificador de la categoría del gasto. */
    private $categoryId;
    /** @var string Fecha del gasto. */
    private $date;
    /** @var int Identificador del usuario del gasto. */
    private $userId;
    /** @var string Nombre de la categoría del gasto. */
    private $nameCategory;
    /** @var string Color de la categoría del gasto. */
    private $color;

    /**
     * Constructor de la clase JoinExpensesCategoriesModel.
     *
     * Llama al constructor de la clase base (Model).
     */    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Obtiene todos los gastos para un usuario dado.
     *
     * @param int $userid Identificador del usuario.
     * @return JoinExpensesCategoriesModel[] Arreglo de objetos JoinExpensesCategoriesModel.
     * @throws PDOException Si ocurre un error al ejecutar la consulta.
     */
    public function getAll($userid){
        $items = [];
        try{
            $query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color  FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userid ORDER BY date');
            $query->execute(["userid" => $userid]);


            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinExpensesCategoriesModel();
                $item->from($p);
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            echo $e;
        }
    }
    
    /**
     * Obtiene el total de gastos para un mes y categoría dados.
     *
     * @param string $date Fecha en formato "YYYY-MM".
     * @param int $categoryid Identificador de la categoría.
     * @param int $userid Identificador del usuario.
     * @return float Total de gastos.
     * @throws PDOException Si ocurre un error al ejecutar la consulta.
     */
    function getTotalByMonthAndCategory($date, $categoryid, $userid){
        try{
            $total = 0;
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 7);
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total from expenses WHERE category_id = :val AND id_user = :user AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['val' => $categoryid, 'user' => $userid, 'year' => $year, 'month' => $month]);

            if($query->rowCount() > 0){
                $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            }else{
                return 0;
            }
            
            return $total;

        }catch(PDOException $e){
            throw $e;
        }
    }

    /**
     * Llena los atributos de la instancia con los valores de un array.
     *
     * @param array $array Arreglo con los valores.
     * @return void
     */
    public function from($array){
        $this->expenseId = $array['expense_id'];
        $this->title = $array['title'];
        $this->categoryId = $array['category_id'];
        $this->amount = $array['amount'];
        $this->date = $array['date'];
        $this->userId = $array['id_user'];
        $this->nameCategory = $array['name'];
        $this->color = $array['color'];
    }

    /**
     * Convierte la instancia a un array.
     *
     * @return array Arreglo con los valores de la instancia.
     */
    public function toArray(){
        $array = [];
        $array['id'] = $this->expenseId;
        $array['title'] = $this->title;
        $array['category_id'] = $this->categoryId;
        $array['amount'] = $this->amount;
        $array['date'] = $this->date;
        $array['id_user'] = $this->userId;
        $array['name'] = $this->nameCategory;
        $array['color'] = $this->color;

        return $array;
    }
    /** @return int Identificador del gasto. */
    public function getExpenseId(){return $this->expenseId;}
    /** @return string Título del gasto. */
    public function getTitle(){return $this->title;}
    /** @return int Identificador de la categoría del gasto. */
    public function getCategoryId(){return $this->categoryId;}
    /** @return float Cantidad del gasto. */
    public function getAmount(){return $this->amount;}
    /** @return string Fecha del gasto. */
    public function getDate(){return $this->date;}
    /** @return int Identificador del usuario del gasto. */
    public function getUserId(){return $this->userId;}
    /** @return string Nombre de la categoría del gasto. */
    public function getNameCategory(){return $this->nameCategory;}
    /** @return string Color de la categoría del gasto. */
    public function getColor(){return $this->color;}
}