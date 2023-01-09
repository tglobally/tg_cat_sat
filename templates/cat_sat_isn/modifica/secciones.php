<?php
/** @var \tglobally\tg_cat_gen\controllers\controlador_cat_sat_isn $controlador $controlador */

use gamboamartin\errores\errores;

use tglobally\template_tg\menu_lateral;

?>
<div class="col-md-3 secciones">

    <div class="col-md-12 int_secciones ">
        <?php echo $controlador->menu_lateral; ?>
        <?php
        $data_template = (new menu_lateral())->contenido_menu_lateral(aplica_link:true,controlador:$controlador, titulo: 'Modifica Impuesto Sobre Nomina');
        if(gamboamartin\errores\errores::$error){
            return (new errores())->error(mensaje: 'Error al integrar include', data: $data_template);
        }
        ?>

    </div>
</div>
