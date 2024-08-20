<table class="table table-sm table-bordered border-estilo">
    <tr class="p-3 menu-header">
        <th class="py-3 px-3">
            <h6 class="d-flex justify-content-between align-items-center">
                <span>Menu</span>
                <button id="miniMenu" type="button" title="Minimizar" class="btn border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-caret-down-square text-secondary" viewBox="0 0 16 16" id="iconToRotate">
                        <path d="M3.626 6.832A.5.5 0 0 1 4 6h8a.5.5 0 0 1 .374.832l-4 4.5a.5.5 0 0 1-.748 0l-4-4.5z"/>
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2z"/>
                    </svg>
                </button>
            </h6>
        </th>
    </tr>
        <!-- <tr class="p-3 menu-item">
            <td class="py-3 px-3"><a href="?dir=archivo">Archivo</a></td>
        </tr>
        <tr class="p-3 menu-item">
            <td class="py-3 px-3"><a href="?dir=rankings">Rankings</a></td>
        </tr> -->
        <tr class="p-3 menu-item">
            <td class="py-3 px-3"><a href="?dir=gamehunt">Gamehunt</a></td>
        </tr>
        <?php if (is_admin($userRole)): ?>
            <tr class="p-3 menu-item">
                <td class="py-3 px-3"><a href="?dir=usuarios">Usuarios</a></td>
            </tr>
        <?php endif; ?>
</table>