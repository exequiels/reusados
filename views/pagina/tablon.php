<?php
denied_permissions_functions(33);
?>
<table class="table table-sm table-bordered border-estilo mb-1">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr>
        <td class="p-3">
        <div class="accordion accordion-flush" id="accordionFlushFilters">
            <div class="accordion-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>Filtros</div>
                    <button class="p-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFilters" aria-expanded="false" aria-controls="flush-collapseFilters">
                        <i class="bi bi-card-list" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div id="flush-collapseFilters" class="accordion-collapse collapse border border-dark mt-3" data-bs-parent="#accordionFlushFilters">
                    <div class="accordion-body">
                        Filtros para la tabla.
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr> 
        <td class="text-white bg-insidetabs" colspan="2">            
            <article>
                <div id="screen">
                    <img src="assets/imgs/tablon.webp" id="tablon">
            </article>
            </div>
        </td>
    </tr>
    <tr>
        <td class="p-3">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>Crear nota</div>
                    <button class="p-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        <i class="bi bi-card-list" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div id="flush-collapseOne" class="accordion-collapse collapse border border-dark mt-3" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <form action="" method="post" id="form-anuncio">
                            <div class="form-group mt-3">
                                <label for="tipo">Anuncio:</label>
                                <select name="tipo" id="tipo" class="filtros" required>
                                    <option value="">- Tipo -</option>
                                    <option value="C">[C] Compra</option>
                                    <option value="V">[V] Venta</option>
                                    <option value="T">[T] Trueque</option>
                                    <option value="I">[I] Informativo</option>
                                </select>
                            </div>

                            <!-- Sección para "Compra" -->
                            <div id="compra-fields" class="conditional-fields mt-3 d-none">
                                <div class="form-group">
                                    <label for="articulo_buscado">Busco:</label>
                                    <input type="text" name="articulo_buscado" id="articulo_buscado" class="filtros">
                                </div>
                            </div>

                            <!-- Sección para "Venta" -->
                            <div id="venta-fields" class="conditional-fields mt-3 d-none">
                                <div class="form-group">
                                    <label for="articulo_venta">Vendo:</label>
                                    <input type="text" name="articulo_venta" id="trueque_articulo_buscado" class="filtros">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="estado">Estado:</label>
                                    <select name="estado" id="estado" class="filtros">
                                        <option value="usado">Usado</option>
                                        <option value="como nuevo">Como nuevo</option>
                                        <option value="nuevo">Nuevo</option>
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="estado_detalle">Detalle del estado:</label>
                                    <select name="estado_detalle" id="estado_detalle" class="filtros">
                                    <option value="">- Sin especificar -</option>
                                    <option value="loose">Suelto</option>
                                    <option value="complete">Completo</option>
                                    <option value="cib">CIB (Completo en caja)</option>
                                    <option value="box only">Solo caja</option>
                                    <option value="manual only">Solo manual</option>
                                    <option value="disc only">Solo disco</option>
                                    <option value="like new">Como nuevo</option>
                                    <option value="sealed">Sellado</option>
                                    <option value="repro">Repro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Sección para "Trueque" -->
                            <div id="trueque-fields" class="conditional-fields mt-3 d-none">
                                <div class="form-group">
                                    <label for="trueque_ofrecido">Ofrezco:</label>
                                    <input type="text" name="trueque_ofrecido" id="trueque_ofrecido" class="filtros">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="trueque_buscado">Busco:</label>
                                    <input type="text" name="trueque_buscado" id="trueque_buscado" class="filtros">
                                </div>
                            </div>

                            <!-- Sección común a todos -->
                            <div id="common-fields" class="mt-3">
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea name="descripcion" id="descripcion" class="filtros" required></textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="whatsapp_numero">Número de WhatsApp (opcional):</label>
                                    <input type="text" name="whatsapp_numero" id="whatsapp_numero" class="filtros">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="facebook_grupo">Grupo de Facebook (opcional):</label>
                                    <input type="text" name="facebook_grupo" id="facebook_grupo" class="filtros">
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label for="ml_url">Enlace a ML (opcional):</label>
                                    <input type="text" name="ml_url" id="ml_url" class="filtros">
                                </div>
                            </div>
                            <!-- Sección para "Informativo" -->
                            <div id="informativo-fields" class="conditional-fields mt-3 d-none">
                                <p>Solo necesitas completar la descripción para un anuncio informativo.</p>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit">Pegar al tablón</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tipoSelect = document.getElementById("tipo");
        const sections = {
            C: document.getElementById("compra-fields"),
            V: document.getElementById("venta-fields"),
            T: document.getElementById("trueque-fields"),
            I: document.getElementById("informativo-fields"),
        };

        tipoSelect.addEventListener("change", () => {
            const selectedType = tipoSelect.value;

            // Oculta todas las secciones
            Object.values(sections).forEach(section => section.classList.add("d-none"));

            // Muestra la sección seleccionada
            if (sections[selectedType]) {
                sections[selectedType].classList.remove("d-none");
            }
        });
    });
</script>