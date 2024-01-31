<?php

/**
 * Clase Dashboard que extiende de SessionController.
 */
class Dashboard extends SessionController
{

    /**
     * Usuario asociado al panel de dashboard.
     *
     * @var UserModel Usuario asociado al panel.
     */
    private $user;

    /**
     * Constructor de la clase Dashboard.
     */
    function __construct()
    {
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Dashboard::constructor() ");
    }

    /**
     * Renderiza la vista principal del panel de dashboard con información del usuario y estadísticas.
     */
    function render()
    {
        error_log("Dashboard::RENDER() ");
        $expensesModel          = new ExpensesModel();
        $expenses               = $this->getExpenses(5);
        $totalThisMonth         = $expensesModel->getTotalAmountThisMonth($this->user->getId());
        $maxExpensesThisMonth   = $expensesModel->getMaxExpensesThisMonth($this->user->getId());
        $categories             = $this->getCategories();

        $this->view->render('dashboard/index', [
            'user'                 => $this->user,
            'expenses'             => $expenses,
            'totalAmountThisMonth' => $totalThisMonth,
            'maxExpensesThisMonth' => $maxExpensesThisMonth,
            'categories'           => $categories
        ]);
    }

    /**
     * Obtiene la lista de gastos de un usuario con un límite especificado.
     *
     * @param int $n Número máximo de gastos a obtener.
     * @return array|null Arreglo de gastos o NULL si $n es menor que 0.
     */
    private function getExpenses($n = 0)
    {
        if ($n < 0) {
            return NULL;
        }
        error_log("Dashboard::getExpenses() id = " . $this->user->getId());
        $expenses = new ExpensesModel();
        return $expenses->getByUserIdAndLimit($this->user->getId(), $n);
    }

    /**
     * Obtiene las categorías con estadísticas de gastos para el usuario actual.
     *
     * @return array Arreglo de categorías con estadísticas.
     */
    function getCategories()
    {
        $res = [];
        $categoriesModel = new CategoriesModel();
        $expensesModel = new ExpensesModel();

        $categories = $categoriesModel->getAll();

        foreach ($categories as $category) {
            $categoryArray = [];
            // Obtenemos la suma de los montos de gastos por categoría.
            $total = $expensesModel->getTotalByCategoryThisMonth($category->getId(), $this->user->getId());
            // Obtenemos el número de gastos por categoría por mes.
            $numberOfExpenses = $expensesModel->getNumberOfExpensesByCategoryThisMonth($category->getId(), $this->user->getId());

            if ($numberOfExpenses > 0) {
                $categoryArray['total'] = $total;
                $categoryArray['count'] = $numberOfExpenses;
                $categoryArray['category'] = $category;
                array_push($res, $categoryArray);
            }
        }
        return $res;
    }
}
