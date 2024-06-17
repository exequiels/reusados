<?php if (!isset($_GET['pais']) || !isset($_GET['categoria'])) {
    $onGoingSearch = FALSE;
    ?>
    <table class="table table-sm table-bordered border-estilo">
        <?php include_once "globales/encabezado_de_tabla.php"; ?>
        <tr class="p-3">
            <td class="p-3" colspan="2">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <?php require_once "layout/filtros_tendencias.php"; ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
<?php } else {
$onGoingSearch = TRUE;
// Pagina default cuando las busquedas se salen de las validaciones
$pagina_buscador = $url_base . "?dir=buscador";
$noHayResultados = "No hay resultado para mostrar.";

// Validar los datos obtenidos de los selects
require_once "globales/validar_paises.php"; // paises
require_once "globales/validar_categorias.php"; // categorias

// Datos obtenidos de los selects
$pais = isset($_GET['pais']) ? urldecode($_GET['pais']) : '';
$categoria = isset($_GET['categoria']) ? urldecode($_GET['categoria']) : '';

// Hacer el chequeo de datos
if (!in_array($pais, $paises) || !in_array($categoria, $categorias)) {
    header("Location: $pagina_tendencias");
    exit;
}

require_once "globales/funciones_paises_y_categorias.php";
$pais = convertirPais($pais);
$categoria = convertirCategoria($categoria);

try {
    // Extrar los clicks
    $query = "SELECT categoria, subcategoria, SUM(clicks) as total_clicks FROM $pais" . "_" . "$categoria" . "_clicks" . " GROUP BY categoria, subcategoria ORDER BY click_date";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
    $json_data = json_encode($data);
    //var_dump($json_data);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
    <table class="table table-sm table-bordered border-estilo">
        <?php include_once "globales/encabezado_de_tabla.php"; ?>
        <tr class="p-3">
            <td class="p-3" colspan="2">
                <?php require_once "layout/filtros_tendencias.php"; ?>
            </td>
        </tr>
        <?php if (!empty($data)): ?>    
        <tr class="p-3">
            <td class="p-3" colspan="2">
                <div class="p-2 flex-item">
                    Historial de interés reUsados:
                </div>
                <div>
                    <canvas id="myChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

                <script>
                    var jsonData = <?php echo $json_data; ?>;
                    console.log(jsonData);
                    var totalClicks = jsonData.reduce((sum, entry) => sum + parseInt(entry.total_clicks), 0);

                    jsonData.forEach(entry => {
                        entry.porcentaje = (parseInt(entry.total_clicks) / totalClicks) * 100; 
                    });

                        console.log(jsonData);
                        var chartConfig = {
                            type: 'pie',
                            data: {
                                labels: jsonData.map(entry => entry.subcategoria),
                                datasets: [
                                    {
                                        label: 'Total ',
                                        data: jsonData.map(entry => entry.porcentaje),
                                        backgroundColor: [
                                            'rgb(255, 0, 0)',    // Rojo
                                            'rgb(0, 128, 0)',    // Verde
                                            'rgb(0, 0, 255)',    // Azul
                                            'rgb(128, 0, 128)',  // Púrpura
                                            'rgb(255, 165, 0)',  // Naranja
                                            'rgb(128, 128, 128)', // Gris
                                            'rgb(255, 255, 0)',  // Amarillo
                                            'rgb(0, 255, 255)',  // Cian
                                            'rgb(255, 0, 255)',  // Magenta
                                            'rgb(0, 255, 0)',    // Verde lima
                                            'rgb(0, 0, 128)',    // Azul marino
                                            'rgb(128, 0, 0)',    // Borgoña
                                            'rgb(0, 255, 128)',  // Turquesa
                                            'rgb(255, 192, 203)', // Rosa claro
                                            'rgb(0, 128, 128)',  // Verde azulado
                                            'rgb(128, 0, 0)',    // Granate
                                            'rgb(255, 69, 0)',   // Rojo coral
                                            'rgb(173, 216, 230)', // Azul celeste
                                            'rgb(184, 134, 11)',  // Marrón
                                            'rgb(255, 99, 71)'   // Rojo tomate                                       
                                        ],
                                        borderColor: [
                                            'rgb(255, 0, 0)',    // Rojo
                                            'rgb(0, 128, 0)',    // Verde
                                            'rgb(0, 0, 255)',    // Azul
                                            'rgb(128, 0, 128)',  // Púrpura
                                            'rgb(255, 165, 0)',  // Naranja
                                            'rgb(128, 128, 128)', // Gris
                                            'rgb(255, 255, 0)',  // Amarillo
                                            'rgb(0, 255, 255)',  // Cian
                                            'rgb(255, 0, 255)',  // Magenta
                                            'rgb(0, 255, 0)',    // Verde lima
                                            'rgb(0, 0, 128)',    // Azul marino
                                            'rgb(128, 0, 0)',    // Borgoña
                                            'rgb(0, 255, 128)',  // Turquesa
                                            'rgb(255, 192, 203)', // Rosa claro
                                            'rgb(0, 128, 128)',  // Verde azulado
                                            'rgb(128, 0, 0)',    // Granate
                                            'rgb(255, 69, 0)',   // Rojo coral
                                            'rgb(173, 216, 230)', // Azul celeste
                                            'rgb(184, 134, 11)',  // Marrón
                                            'rgb(255, 99, 71)'   // Rojo tomate
                                        ],
                                        borderWidth: 3,
                                        borderColor: "#fff"
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Clicks por Subcategoria'
                                },
                                animation: {
                                    animateScale: true,
                                    animateRotate: true
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                var label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += parseFloat(context.parsed.toFixed(2)) + '%';
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        };
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, chartConfig);
                </script>
                </td>
            </tr>   
        <?php else: ?>
            <tr class="p-3"> 
                <td class="p-3" colspan="2">
                    <article>
                        <p><img src="imgs/manual.png" width="150px" height="150px" class="shadow rounded" alt="Libro antiguo" id="floatleft"></p>
                        <p><?php echo $noHayResultados;?></p>
                    </article>
                </td>
            </tr>
        <?php endif; ?>
    </table>
<?php } ?>