<?php
check_auth(['usuario', 'admin']);
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-3" colspan="2">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Archivo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Gamehunts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3-tab" data-bs-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Juegos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-secondary" id="tab4-tab" data-bs-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false" disabled>Consolas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-secondary" id="tab5-tab" data-bs-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false"  disabled>Arcades</a>
                </li>
            </ul>
            <div class="tab-content mt-4 p-3" id="myTabContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    El juego mas buscado en el Archivo de datos de reUsados.
                </div>
                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
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
            <span class="fw-bold">Lo mas buscado en gamehunt de video juegos.</span>
                <table class="table table-sm table-bordered border-estilo mt-4">
                    <?php if (!empty($dataSubcategorias)): ?>    
                        <thead>
                            <tr>
                                <th>Subcategoría</th>
                                <th>Búsquedas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataSubcategorias as $entry): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entry['subcategoria']); ?></td>
                                    <td><?php echo htmlspecialchars($entry['total_clicks']); ?></td>
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

                    <table class="table table-sm table-bordered border-estilo">
                    <thead>
                        <tr>
                            <th colspan="7">Mapa de Calor - <?php echo $currentYear; ?></th>
                        </tr>
                    </thead>
                        <tr>
                            <td>
                                <span></span>
                                <div class="heatmap">
                                    <?php
                                // Inicializar un array para todos los días del mes
                                $diasDelMes = array_fill(1, 31, 0);
// Llenar el array con los datos de la base de datos
foreach ($dataDias as $data) {
    $diasDelMes[$data['dia']] = $data['total_clicks'];
}
// Crear los días del mes
for ($dia = 1; $dia <= 31; $dia++) {
    $clicks = $diasDelMes[$dia];
    $fecha = "2024-01-" . str_pad($dia, 2, "0", STR_PAD_LEFT);
    ?>
                                        <div class="day" data-clicks="<?php echo $clicks; ?>" data-date="<?php echo $fecha; ?>">
                                            <?php echo $dia; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="table table-sm table-bordered border-estilo">
    <thead>
        <tr>
            <th colspan="6">Mapa de Calor Anual - <?php echo $currentYear; ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

// Inicializar array para todos los meses y semanas
$datosAnuales = array_fill(1, 12, array_fill(1, 5, 0));

// Llenar el array con los datos de la base de datos
foreach ($dataAnual as $data) {
    $datosAnuales[$data['month']][$data['week_of_month']] = $data['total_clicks'];
}

// Crear las filas de la tabla
foreach ($meses as $indice => $mes) {
    $mesNumero = $indice + 1;
    echo "<tr>";
    echo "<td class='month-name'>$mes</td>";

    for ($semana = 1; $semana <= 5; $semana++) {
        $clicks = $datosAnuales[$mesNumero][$semana] ?? 0;
        $clase = ($mesNumero > date('n') || ($mesNumero == date('n') && $semana > ceil(date('j') / 7))) ? 'future' : '';
        echo "<td class='week $clase' data-clicks='$clicks' title='$mes, Semana $semana: $clicks busquedas'></td>";
    }

    echo "</tr>";
}
?>
    </tbody>
</table>


                </div>
                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                    Los juegos que mas eligieron los usuarios de reUsados.
                </div>
            </div>
        </td>
    </tr>
</table>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const days = document.querySelectorAll('.day');
    let maxClicks = 0;

    // Encontrar el máximo número de clics
    days.forEach(day => {
        const clicks = parseInt(day.dataset.clicks);
        if (clicks > maxClicks) maxClicks = clicks;
    });

    // Colorear los días
    days.forEach(day => {
        const clicks = parseInt(day.dataset.clicks);
        const intensity = clicks / maxClicks;
        const hue = 120 - (120 * intensity); // 120 es verde, 0 es rojo
        day.style.backgroundColor = `hsl(${hue}, 80%, 80%)`;
        
        // Agregar tooltip
        day.title = `${day.dataset.date}: ${clicks} busquedas`;
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weeks = document.querySelectorAll('.week:not(.future)');
    let maxClicks = 0;

    // Encontrar el máximo número de clics
    weeks.forEach(week => {
        const clicks = parseInt(week.dataset.clicks);
        if (clicks > maxClicks) maxClicks = clicks;
    });

    // Colorear las semanas
    weeks.forEach(week => {
        const clicks = parseInt(week.dataset.clicks);
        const intensity = maxClicks > 0 ? clicks / maxClicks : 0;
        const hue = 120 - (120 * intensity); // 120 es verde, 0 es rojo
        week.style.backgroundColor = `hsl(${hue}, 80%, 80%)`;
    });
});
</script>
