<nav aria-label='...'>
    <ul class='pagination pagination-sm paginacion-custom'>
        <?php
        $queryParams = $_GET; // Parámetros de la URL

        if ($page > 1) {
            // Link to first page
            $queryParams['pagina'] = 1;
            $firstPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$firstPageUrl' class='page-link'>&lt;&lt;</a></li>";

            // Link to previous page
            $queryParams['pagina'] = $page - 1;
            $prevPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$prevPageUrl' class='page-link'><</a></li>";
        }

        // Define range for page numbers to be displayed
        $range = 2;
        $startRange = max(1, $page - $range);
        $endRange = min($page + $range, $totalPages);

        for ($pageIndex = $startRange; $pageIndex <= $endRange; $pageIndex++) {
            $queryParams['pagina'] = $pageIndex;
            $pageUrl = http_build_query($queryParams);

            if ($pageIndex == $page) {
                echo "<li class='page-item active'><a class='page-link' href='?$pageUrl'>$pageIndex</a></li>";
            } else {
                echo "<li class='page-item'><a class='page-link' href='?$pageUrl'>$pageIndex</a></li>";
            }
        }

        if ($page < $totalPages) {
            // Link to next page
            $queryParams['pagina'] = $page + 1;
            $nextPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$nextPageUrl' class='page-link'>></a></li>";

            // Link to last page
            $queryParams['pagina'] = $totalPages;
            $lastPageUrl = http_build_query($queryParams);
            echo "<li class='page-item'><a href='?$lastPageUrl' class='page-link'>&gt;&gt;</a></li>";
        }
        ?>                        
    </ul>
</nav>