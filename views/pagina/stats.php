<?php
denied_permissions_functions(3);
require_once './models/StatsModel.php';

$pais = "mla";
$statsModel = new StatsModel($pdo);
$totalClicks = $statsModel->getTotalClicks($pais) ?? 0;
$clicksSubcategorias = $statsModel->getClicksSubcategorias($pais);
$noHayResultados = "No hay resultado para mostrar.";
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-3" colspan="2">
        <p>Total de busquedas realizadas <?php echo $totalClicks ?>.</p>
        <p>La subcategoría mas cautivadora del bazar.</p>
            <table class="table table-sm table-bordered border-estilo mt-4">
                <?php if (!empty($clicksSubcategorias)): ?>    
                    <thead>
                        <tr>
                            <td>Subcategoría</td>
                            <td>Interés</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clicksSubcategorias as $entry): ?>
                            <tr>
                                <td><?php echo escape($entry['subcategoria']); ?></td>
                                <td><?php echo escape($entry['total_clicks']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php else: ?>
                    <tr> 
                        <td class="p-3" colspan="2">
                            <article>
                                <p><img src="assets/imgs/manual.png" width="150px" height="150px" class="shadow rounded" alt="Libro antiguo" id="floatleft"></p>
                                <p><?php echo $noHayResultados;?></p>
                            </article>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </td>
    </tr>
</table>