
<?php
    $user = $this->d['user'];
    $dates = $this->d['dates'];
    $categories = $this->d['categories'];
?>

<link rel="stylesheet" href="<?php echo constant('URL') ?>public/css/history.css">
    <?php require_once 'views/dashboard/header.php'; ?>

    <div id="main-container">
    <?php $this->showMessages();?>
        <div id="history-container" class="container">
            <?php
                if(isset($_GET['message'])){
                    if($_GET['message'] === 'success'){
                        showSuccess('Gasto eliminado con éxito');
                    }else{
                        showError('Hubo un error en la operación. Inténtalo más tarde');
                    }
                }
             ?>
            <div id="history-options">
                <h2>Historial de gastos</h2>
                <div id="filters-container">
                    <div class="filter-container">
                        <select id="sdate" class="custom-select">
                            <option value="">Ver todas las fechas</option>
                            <?php
                                $options = $dates;
                                foreach($options as $option){
                                    echo "<option value=$option >".$option."</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="filter-container">
                        <select id="scategory" class="custom-select">
                            <option value="">Ver todas las categorias</option>
                            <?php
                                $options = $categories;
                                foreach($options as $option){
                                    echo "<option value=$option >".$option."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>   
            </div>
            
            <div id="table-container">
                <table width="100%" cellpadding="0">
                    <thead>
                        <tr>
                        <th data-sort="title" width="35%">Título</th>
                        <th data-sort="category">Categoría</th>
                        <th data-sort="date">Fecha</th>
                        <th data-sort="amount">Cantidad</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="databody">
                        
                    </tbody>
                </table>
            </div>
            
        </div>

    </div>
<script src="public/js/expenses.js"></script>

    