<table class="table table-sm table-bordered border-estilo">
    <tr>
        <td class="py-1 px-3">
            <div class="d-flex flex-wrap align-items-center justify-content-<?= (isset($_SESSION['user_id'])) ? 'between' : 'end'; ?>">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1">
                        <div class="d-flex">
                            <div class="col-6 col-sm-auto me-sm-2 mt-1 mb-1">
                                <button type="button" class="w-100 border border-dark-subtle p-2"><a href="?dir=mi-perfil">Perfil</a></button>
                            </div>
                            <div class="col-6 col-sm-auto me-sm-2 mt-1 mb-1">
                                <button type="button" class="w-100 border border-dark-subtle p-2"><a href="?dir=mi-cpanel">Cpanel</a></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1">
                        <form action="?dir=logout&out=auto" method="post">
                            <button type="submit" class="w-100 border border-dark-subtle p-2">Cerrar sesión</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1 d-none d-md-block">
                        <button class="w-100 border border-dark-subtle p-2">
                            <a href="?dir=registrarse">Registrarse</a>
                        </button>
                    </div>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1 d-none d-md-block">
                        <button class="w-100 border border-dark-subtle p-2"><a href="?dir=login">Login</a></button>
                    </div>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1 d-block d-md-none">
                        <button class="w-100 border border-dark-subtle p-2">
                            <a href="?dir=registrarse">Registrarse</a>
                            /
                            <a href="?dir=login"> Login</a>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</table>