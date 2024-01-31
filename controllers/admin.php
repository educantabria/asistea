<?php
/**
 * Clase Admin que extiende de SessionController.
 */
class Admin extends SessionController{


    /**
     * Constructor de la clase Admin.
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * Renderiza la vista principal del panel de administración con estadísticas.
     */
    function render(){
        $stats = $this->getStatistics();

        $this->view->render('admin/index', [
            "stats" => $stats
        ]);
    }

    /**
     * Renderiza la vista para la creación de una nueva categoría.
     */
    function createCategory(){
        $this->view->render('admin/create-category');
    }

    /**
     * Maneja la creación de una nueva categoría.
     */
    function newCategory(){
        error_log('Admin::newCategory()');
        if($this->existPOST(['name', 'color'])){
            $name = $this->getPost('name');
            $color = $this->getPost('color');

            $categoriesModel = new CategoriesModel();

            if(!$categoriesModel->exists($name)){
                $categoriesModel->setName($name);
                $categoriesModel->setColor($color);
                $categoriesModel->save();
                error_log('Admin::newCategory() => new category created');
                $this->redirect('admin', ['success' => Success::SUCCESS_ADMIN_NEWCATEGORY]);
            }else{
                $this->redirect('admin', ['error' => Errors::ERROR_ADMIN_NEWCATEGORY_EXISTS]);
            }
        }
    }

    /**
     * Obtiene estadísticas para el panel de administración.
     *
     * @return array Arreglo con estadísticas.
     */
    private function getStatistics(){
        $res = [];

        $userModel = new UserModel();
        $users = $userModel->getAll();
        
        $expenseModel = new ExpensesModel();
        $expenses = $expenseModel->getAll();

        $categoriesModel = new CategoriesModel();
        $categories = $categoriesModel->getAll();

        $res['count-users'] = count($users);
        $res['count-expenses'] = count($expenses);
        $res['max-expenses'] = $this->getMaxAmount($expenses);
        $res['min-expenses'] = $this->getMinAmount($expenses);
        $res['avg-expenses'] = $this->getAverageAmount($expenses);
        $res['count-categories'] = count($categories);
        $res['mostused-category'] = $this->getCategoryMostUsed($expenses);
        $res['lessused-category'] = $this->getCategoryLessUsed($expenses);
        return $res;
    }

    /**
     * Obtiene el máximo de un conjunto s.
     *
     * @param array $expenses Arreglo de gastos.
     * @return float cantidad  máxima.
     */    
     private function getMaxAmount($expenses){
        $max = 0;
        foreach ($expenses as $expense) {
            $max = max($max, $expense->getAmount());
        }

        return $max;
    }
    /**
     * Obtiene el mínimo de un conjunto .
     *
     * @param array $expenses .
     * @return float mínimo.
     */
    private function getMinAmount($expenses){
        $min = $this->getMaxAmount($expenses);
        foreach ($expenses as $expense) {
            $min = min($min, $expense->getAmount());
        }

        return $min;
    }

     /**
     * Obtiene el monto promedio de un conjunto.
     *
     * @param array $expenses Arreglo de gastos.
     * @return float promedio.
     */   
    private function getAverageAmount($expenses){
        $sum = 0;
        foreach ($expenses as $expense) {
            $sum += $expense->getAmount();
        }

        return ($sum / count($expenses));
    }

    /**
     * Obtiene la categoría más utilizada en un conjunto.
     *
     * @param array $expenses Arreglo de gastos.
     * @return string Nombre de la categoría más utilizada.
     */
    private function getCategoryMostUsed($expenses){
        $repeat = [];

        foreach ($expenses as $expense) {
            if(!array_key_exists($expense->getCategoryId(), $repeat)){
                $repeat[$expense->getCategoryId()] = 0;    
            }
            $repeat[$expense->getCategoryId()]++;
        }

        $categoryMostUsed = array_keys($repeat, max($repeat))[0];
        $categoryModel = new CategoriesModel();
        $categoryModel->get($categoryMostUsed);

        $category = $categoryModel->getName();

        return $category;
    }

    /**
     * Obtiene la categoría menos utilizada en un conjunto.
     *
     * @param array $expenses Arreglo de gastos.
     * @return string Nombre de la categoría menos utilizada.
     */
    private function getCategoryLessUsed($expenses){
        $repeat = [];

        foreach ($expenses as $expense) {
            if(!array_key_exists($expense->getCategoryId(), $repeat)){
                $repeat[$expense->getCategoryId()] = 0;    
            }
            $repeat[$expense->getCategoryId()]++;
        }

        $categoryLessUsed = array_keys($repeat, min($repeat))[0];
        $categoryModel = new CategoriesModel();
        $categoryModel->get($categoryLessUsed);

        $category = $categoryModel->getName();

        return $category;
    }
}