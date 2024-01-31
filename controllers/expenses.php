
<?php

/**
 * Clase Expenses que extiende de SessionController.
 */
class Expenses extends SessionController{


    /**
     * Usuario actual.
     * @var User
     */
    private $user;

    /**
     * Constructor de la clase Expenses.
     */
    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Expenses::constructor() ");
    }

    /**
     * Método para renderizar la vista principal de Expenses.
     */
     function render(){
        error_log("Expenses::RENDER() ");

        $this->view->render('expenses/index', [
            'user' => $this->user,
            'dates' => $this->getDateList(),
            'categories' => $this->getCategoryList()
        ]);
    }

    /**
     * Método para agregar un nueva operación.
     */
    function newExpense(){
        error_log('Expenses::newExpense()');
        if(!$this->existPOST(['title', 'amount', 'category', 'date'])){
            $this->redirect('dashboard', ['error' => Errors::ERROR_EXPENSES_NEWEXPENSE_EMPTY]);
            return;
        }

        if($this->user == NULL){
            $this->redirect('dashboard', ['error' => Errors::ERROR_EXPENSES_NEWEXPENSE]);
            return;
        }

        $expense = new ExpensesModel();

        $expense->setTitle($this->getPost('title'));
        $expense->setAmount((float)$this->getPost('amount'));
        $expense->setCategoryId($this->getPost('category'));
        $expense->setDate($this->getPost('date'));
        $expense->setUserId($this->user->getId());

        $expense->save();
        $this->redirect('dashboard', ['success' => Success::SUCCESS_EXPENSES_NEWEXPENSE]);
    }

    /**
     * Método para renderizar la interfaz de usuario para agregar una nueva operación.
     */
    function create(){
        $categories = new CategoriesModel();
        $this->view->render('expenses/create', [
            "categories" => $categories->getAll(),
            "user" => $this->user
        ]);
    } 

    /**
     * Método para obtener la lista de identificadores de categorías.
     * @return array
     */
    function getCategoryIds(){
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $categories = $joinExpensesCategoriesModel->getAll($this->user->getId());

        $res = [];
        foreach ($categories as $cat) {
            array_push($res, $cat->getCategoryId());
        }
        $res = array_values(array_unique($res));
        return $res;
    }

    /**
     * Método para obtener la lista de fechas.
     * @return array
     */
    private function getDateList(){
        $months = [];
        $res = [];
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategoriesModel->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            array_push($months, substr($expense->getDate(),0, 7 ));
        }
        $months = array_values(array_unique($months));
        foreach($months as $month){
            array_push($res, array_pop($months));
        }
        return $res;
    }

    /**
     * Método para obtener la lista de categorías.
     * @return array
     */
    private function getCategoryList(){
        $res = [];
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategoriesModel->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            array_push($res, $expense->getNameCategory());
        }
        $res = array_values(array_unique($res));

        return $res;
    }

    /**
     * Método para obtener la lista de colores de las categorías.
     * @return array
     */
    private function getCategoryColorList(){
        $res = [];
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategoriesModel->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            array_push($res, $expense->getColor());
        }
        $res = array_unique($res);
        $res = array_values(array_unique($res));

        return $res;
    }

    /**
     * Método para obtener el historial en formato JSON.
     */
    function getHistoryJSON(){
        header('Content-Type: application/json');
        $res = [];
        $joinExpensesCategories = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategories->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            array_push($res, $expense->toArray());
        }
        
        echo json_encode($res);

    }

    /**
     * Método para obtener los datos de operación en formato JSON.
     */
    function getExpensesJSON(){
        header('Content-Type: application/json');

        $res = [];
        $categoryIds     = $this->getCategoryIds();
        $categoryNames  = $this->getCategoryList();
        $categoryColors = $this->getCategoryColorList();

        array_unshift($categoryNames, 'mes');
        array_unshift($categoryColors, 'categorias');
        
        $months = $this->getDateList();

        for($i = 0; $i < count($months); $i++){
            $item = array($months[$i]);
            for($j = 0; $j < count($categoryIds); $j++){
                $total = $this->getTotalByMonthAndCategory( $months[$i], $categoryIds[$j]);
                array_push( $item, $total );
            }   
            array_push($res, $item);
        }

        array_unshift($res, $categoryNames);
        array_unshift($res, $categoryColors);
        
        echo json_encode($res);
    }

    /**
     * Método para obtener el total por mes y categoría.
     * @param string $date
     * @param int $categoryid
     * @return float
     */
    function getTotalByMonthAndCategory($date, $categoryid){
        $iduser = $this->user->getId();
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();

        $total = $joinExpensesCategoriesModel->getTotalByMonthAndCategory($date, $categoryid, $iduser);
        if($total == NULL) $total = 0;
        return $total;
    }

    /**
     * Método para eliminar una operación.
     * @param array|null $params
     */
    function delete($params){
        error_log("Expenses::delete()");
        
        if($params === NULL) $this->redirect('expenses', ['error' => Errors::ERROR_ADMIN_NEWCATEGORY_EXISTS]);
        $id = $params[0];
        error_log("Expenses::delete() id = " . $id);
        $res = $this->model->delete($id);

        if($res){
            $this->redirect('expenses', ['success' => Success::SUCCESS_EXPENSES_DELETE]);
        }else{
            $this->redirect('expenses', ['error' => Errors::ERROR_ADMIN_NEWCATEGORY_EXISTS]);
        }
    }

}