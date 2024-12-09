<table class="table table-sm table-bordered border-estilo">
    <tr>
        <td class="py-1 px-3">
            <div class="d-flex flex-wrap align-items-center justify-content-<?= (isset($_SESSION['user_id'])) ? 'between' : 'end'; ?>">
                <?php if (has_role($userRole, 'usuario')): ?>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1">
                        <div class="d-flex">
                            <div class="col-6 col-sm-auto me-sm-2 mt-1 mb-1">
                                <button type="button" class="w-100 border border-dark-subtle p-2"><a href="?dir=mi-perfil">Perfil</a></button>
                            </div>
                            <?php if (has_role($userRole, 'admin')): ?>
                                <div class="col-6 col-sm-auto me-sm-2 mt-1 mb-1 d-none d-sm-block">
                                    <button type="button" class="w-100 border border-dark-subtle p-2"><a href="?dir=cpanel">Cpanel</a></button>
                                </div>
                            <?php endif; ?>
                            <div class="col-6 col-sm-auto me-sm-2 mt-1 mb-1 d-block d-sm-none">
                                <form action="?dir=logout&out=auto" method="post">
                                    <button type="submit" class="w-100 border border-dark-subtle p-2">
                                        Cerrar
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1 d-block d-sm-none">
                            <button type="button" class="w-100 border border-dark-subtle p-2"><a href="?dir=cpanel">Cpanel</a></button>
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1 d-none d-sm-block">
                        <form action="?dir=logout&out=auto" method="post">
                            <button type="submit" class="w-100 border border-dark-subtle p-2">
                            Cerrar sesión</button>
                        </form>
                    </div>         
                <?php else: ?>
                    <div class="col-12 col-sm-auto me-sm-2 mt-1 mb-1">
                        <div class="d-flex">
                            <div class="col-7 col-sm-auto me-sm-2 mt-1 mb-1">
                                <button class="w-100 border border-dark-subtle p-2">
                                    <a href="?dir=registrarse">Register</a>
                                </button>
                            </div>
                            <div class="col-5 col-sm-auto me-sm-2 mt-1 mb-1">
                                <button class="w-100 border border-dark-subtle p-2"><a href="?dir=login">Login</a></button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</table>