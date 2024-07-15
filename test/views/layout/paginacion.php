<nav aria-label='...'>
    <ul class='pagination pagination-sm paginacion-custom'>
        <?php
            $queryParams = $_GET; // Parámetros de la URL

        if ($manual_page > 1) {
            $queryParams['pagina'] = 1;
            $firstPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$firstPageUrl' class='page-link'>&lt;&lt;</a></li>";

            $queryParams['pagina'] = $manual_page - 1;
            $prevPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$prevPageUrl' class='page-link'><</a></li>";
        }

        $range = 2; // Rango
        $startRange = max(1, $manual_page - $range);
        $endRange = min($manual_page + $range, $total_pages);

        for ($page = $startRange; $page <= $endRange; $page++) {
            $queryParams['pagina'] = $page;
            $pageUrl = http_build_query($queryParams);

            if ($page == $manual_page) {
                echo "<li class='page-item active'><a class='page-link' href='?$pageUrl'>$page</a></li>";
            } else {
                echo "<li class='page-item'><a class='page-link' href='?$pageUrl'>$page</a></li>";
            }
        }

        if ($manual_page < $total_pages) {
            $queryParams['pagina'] = $manual_page + 1;
            $nextPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$nextPageUrl' class='page-link'>></a></li>";

            $queryParams['pagina'] = $total_pages;
            $lastPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$lastPageUrl' class='page-link'>&gt;&gt;</a></li>";
        }
        ?>                        
    </ul>
</nav>