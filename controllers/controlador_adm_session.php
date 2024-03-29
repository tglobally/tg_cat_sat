<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace tglobally\tg_cat_sat\controllers;

use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use JsonException;
use PDO;
use stdClass;

class controlador_adm_session extends \gamboamartin\controllers\controlador_adm_session {
    public bool $existe_msj = false;
    public string $include_menu = '';
    public string $mensaje_html = '';
    public string $seccion = 'adm_seccion';

    public array $secciones = array("cat_sat_division_producto","cat_sat_grupo_producto","cat_sat_clase_producto","cat_sat_tipo_producto",
        "cat_sat_producto",'cat_sat_moneda','cat_sat_metodo_pago','cat_sat_forma_pago','cat_sat_unidad','cat_sat_obj_imp',
        'cat_sat_uso_cfdi','cat_sat_regimen_fiscal','cat_sat_tipo_factor','cat_sat_factor','cat_sat_tipo_de_comprobante',
        'cat_sat_isr','cat_sat_isn','cat_sat_subsidio', 'cat_sat_periodicidad_pago_nom');
    public array $links_catalogos = array();

    public stdClass $links;

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct($link, $paths_conf);

        $this->links = (new links_menu(link: $link, registro_id: $this->registro_id))->genera_links($this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $this->links);
            print_r($error);
            die('Error');
        }

        $this->links_catalogos["cat_sat_producto"]["titulo"] = "Productos";
        $this->links_catalogos["cat_sat_producto"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_tipo_producto"]["titulo"] = "Tipos de producto";
        $this->links_catalogos["cat_sat_tipo_producto"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_grupo_producto"]["titulo"] = "Grupo producto";
        $this->links_catalogos["cat_sat_grupo_producto"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_clase_producto"]["titulo"] = "Clase producto";
        $this->links_catalogos["cat_sat_clase_producto"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_division_producto"]["titulo"] = "División producto";
        $this->links_catalogos["cat_sat_division_producto"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_moneda"]["titulo"] = "Catalogo Moneda";
        $this->links_catalogos["cat_sat_moneda"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_metodo_pago"]["titulo"] = "Metodo Pago";
        $this->links_catalogos["cat_sat_metodo_pago"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_forma_pago"]["titulo"] = "Formas de Pago";
        $this->links_catalogos["cat_sat_forma_pago"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_unidad"]["titulo"] = "Unidad";
        $this->links_catalogos["cat_sat_unidad"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_obj_imp"]["titulo"] = "Obj Impuesto";
        $this->links_catalogos["cat_sat_obj_imp"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_uso_cfdi"]["titulo"] = "Uso CFDI";
        $this->links_catalogos["cat_sat_uso_cfdi"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_regimen_fiscal"]["titulo"] = "Regímenes Fiscales";
        $this->links_catalogos["cat_sat_regimen_fiscal"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_tipo_factor"]["titulo"] = "Tipo Factor";
        $this->links_catalogos["cat_sat_tipo_factor"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_factor"]["titulo"] = "Factor";
        $this->links_catalogos["cat_sat_factor"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_tipo_de_comprobante"]["titulo"] = "Tipo de Comprobante";
        $this->links_catalogos["cat_sat_tipo_de_comprobante"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_isr"]["titulo"] = "ISR";
        $this->links_catalogos["cat_sat_isr"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_isn"]["titulo"] = "ISN";
        $this->links_catalogos["cat_sat_isn"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_subsidio"]["titulo"] = "Subsidio";
        $this->links_catalogos["cat_sat_subsidio"]["subtitulo"] = "Catálogo";
        $this->links_catalogos["cat_sat_periodicidad_pago_nom"]["titulo"] = "Periodicidad Pago";
        $this->links_catalogos["cat_sat_periodicidad_pago_nom"]["subtitulo"] = "Catálogo";
    }

    /**
     * Funcion de controlador donde se ejecutaran siempre que haya un acceso denegado
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return array vacio siempre
     */
    public function denegado(bool $header, bool $ws = false): array
    {

        return array();

    }

    public function get_link(string $seccion, string $accion = "lista"): array|string
    {
        if (!property_exists($this->links, $seccion)) {
            $error = $this->errores->error(mensaje: "Error no existe la seccion: $seccion", data: $seccion);
            print_r($error);
            die('Error');
        }

        if (!property_exists($this->links->$seccion, $accion)) {
            $error = $this->errores->error(mensaje: 'Error no existe la accion', data: $accion);
            print_r($error);
            die('Error');
        }

        return $this->links->$seccion->$accion;
    }

    /**
     * Funcion de controlador donde se ejecutaran los elementos necesarios para poder mostrar el inicio en
     *      session/inicio
     *
     * @param bool $aplica_template Si aplica template buscara el header de la base
     *              No recomendado para vistas ajustadas como esta
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return string|array string = html array = error
     * @throws JsonException si hay error en forma ws
     */
    public function inicio(bool $aplica_template = false, bool $header = true, bool $ws = false): string|array
    {

        $template =  parent::inicio($aplica_template, false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al generar template',data: $template, header: $header, ws: $ws);
        }

        $this->links_catalogos = $this->inicializar_links();
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar links', data: $this->links_catalogos);
        }

        $this->include_menu = (new generales())->path_base;
        $this->include_menu .= 'templates/inicio.php';

        return $template;
    }

    public function inicializar_links(): array
    {
        foreach ($this->secciones as $link => $valor){

            $seccion = $valor;
            $accion = "lista";

            if (!is_numeric($link)){
                $seccion = $link;
                $accion = $valor;
            }

            if (!array_key_exists($seccion,$this->links_catalogos)){
                $this->links_catalogos[$seccion] = array();
            }

            if (!array_key_exists("titulo",$this->links_catalogos[$seccion])){
                $this->links_catalogos[$seccion]["titulo"] = $seccion;
            }

            if (!array_key_exists("subtitulo",$this->links_catalogos[$seccion])){
                $this->links_catalogos[$seccion]["subtitulo"] = $accion;
            }

            $this->links_catalogos[$seccion]["link"] = $this->get_link(seccion: $seccion,accion: $accion);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al obtener link', data: $this->links);
            }
        }
        return $this->links_catalogos;
    }

    /**
     * Funcion de controlador donde se ejecutaran los elementos necesarios para la asignacion de datos de logueo
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @param string $accion_header
     * @param string $seccion_header
     * @return array string = html array = error
     *
     */
    public function loguea(bool $header, bool $ws = false, string $accion_header = 'login', string $seccion_header = 'session'): array
    {
        $loguea = parent::loguea(header: true,accion_header:  $accion_header,
            seccion_header:  $seccion_header); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al loguear',data: $loguea, header: $header, ws: $ws);
        }
        return $loguea;
    }


    /**
     * Funcion de controlador donde se ejecutaran los elementos de session/login
     *
     * @param bool $header Si header es true cualquier error se mostrara en el html y cortara la ejecucion del sistema
     *              En false retornara un array si hay error y un string con formato html
     * @param bool $ws Si ws es true retornara el resultado en formato de json
     * @return string|array string = html array = error
     */
    public function login(bool $header = true, bool $ws = false): stdClass|array
    {
        $login = parent::login($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje:  'Error al generar template',data: $login, header: $header, ws: $ws);
        }

        $this->mensaje_html = '';
        if(isset($_GET['mensaje']) && $_GET['mensaje'] !==''){
            $mensaje = trim($_GET['mensaje']);
            if($mensaje !== ''){
                $this->mensaje_html = $mensaje;
                $this->existe_msj = true;
            }
        }

        $this->include_menu .= 'templates/login.php';

        return $login;

    }



}
