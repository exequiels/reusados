<?php
check_auth(['usuario', 'admin']);
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-3" colspan="2">
                    <?php
                        $noHayResultados = "No hay resultado para mostrar.";
$pais = "mla";
$categoria = "consolasyvideojuegos";

try {

    // Extraer los clicks por subcategoría
    $querySubcategorias = "SELECT subcategoria, SUM(clicks) as total_clicks 
                                                FROM $pais" . "_" . "$categoria" . "_clicks" . " 
                                                GROUP BY subcategoria 
                                                ORDER BY subcategoria";
    $stmtSubcategorias = $pdo->prepare($querySubcategorias);
    $stmtSubcategorias->execute();
    $dataSubcategorias = $stmtSubcategorias->fetchAll(PDO::FETCH_ASSOC);

    // Extraer los clicks por día del mes
    $queryDias = "SELECT DAY(click_date) as dia, SUM(clicks) as total_clicks 
                                FROM $pais" . "_" . "$categoria" . "_clicks" . "
                                WHERE click_date BETWEEN '2024-01-01' AND '2024-01-31'
                                GROUP BY dia 
                                ORDER BY dia";

    $stmtDias = $pdo->prepare($queryDias);
    $stmtDias->execute();
    $dataDias = $stmtDias->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

try {

    $currentYear = date('Y'); // Obtiene el año actual
    $queryAnual = "SELECT 
                                    YEAR(click_date) as year,
                                    MONTH(click_date) as month,
                                    WEEK(click_date, 1) - WEEK(DATE_SUB(click_date, INTERVAL DAYOFMONTH(click_date)-1 DAY), 1) + 1 as week_of_month,
                                    SUM(clicks) as total_clicks
                                FROM $pais" . "_" . "$categoria" . "_clicks" . "
                                WHERE YEAR(click_date) = $currentYear
                                GROUP BY year, month, week_of_month
                                ORDER BY year, month, week_of_month";

    $stmtAnual = $pdo->prepare($queryAnual);
    $stmtAnual->execute();
    $dataAnual = $stmtAnual->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
            <p>Total de busquedas realizadas { $total_busquedas }.</p>
            <p>La subcategoría mas cautivadora.</p>
                <table class="table table-sm table-bordered border-estilo mt-4">
                    <?php if (!empty($dataSubcategorias)): ?>    
                        <thead>
                            <tr>
                                <td>Subcategoría</td>
                                <td>Interés</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataSubcategorias as $entry): ?>
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