<?php /** @var \tglobally\tg_cat_sat\controllers\controlador_cat_sat_subsidio $controlador */ ?>

<?php (new \tglobally\template_tg\template())->sidebar($controlador); ?>

<div class="col-md-9 formulario">
    <div class="col-lg-12">

        <h3 class="text-center titulo-form">Hola, <?php echo $controlador->datos_session_usuario['adm_usuario_user']; ?> </h3>

        <div class="  form-main" id="form">
            <form method="post" action="<?php echo $controlador->link_modifica_bd;?>" class="form-additional">

                <?php echo $controlador->inputs->codigo; ?>
                <?php echo $controlador->inputs->descripcion; ?>
                <?php echo $controlador->inputs->limite_inferior; ?>
                <?php echo $controlador->inputs->limite_superior; ?>
                <?php echo $controlador->inputs->cuota_fija; ?>
                <?php echo $controlador->inputs->porcentaje_excedente; ?>
                <?php echo $controlador->inputs->cat_sat_periodicidad_pago_nom_id; ?>
                <?php echo $controlador->inputs->fecha_inicio; ?>
                <?php echo $controlador->inputs->fecha_fin; ?>

                <div class="buttons col-md-12">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-guarda col-md-12 " value="modifica">Guarda</button>
                    </div>
                    <div class="col-md-6 ">
                        <a href="<?php echo $controlador->link_lista; ?>"  class="btn btn-info btn-guarda col-md-12 ">Lista</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
