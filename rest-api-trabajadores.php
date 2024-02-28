<?php

/*
Plugin Name: REST API trabajadores
Description: Este plugin agrega un endpoint a la API REST de WordPress para manipular datos de la tabla de trabajadores.
Version: 1.0
Author: Rodrigo
*/

function react_plugin_shortcode()
{
    wp_enqueue_script(
        'rest-api-trabajadores_js',

        plugin_dir_url(__FILE__).'/build/index.js',

        ['wp-element'],

        '0.1.0',

        true
    );

    wp_enqueue_style(
        'rest-api-trabajadores_css',

        plugin_dir_url(__FILE__).'/build/index.css'
    );

    return "<div class='react-plugin'></div>";
}

add_shortcode('rest-api-trabajadores', 'react_plugin_shortcode');

include 'functions_trabajadores.php';

// Función para crear la tabla de trabajadores al activar el plugin
function crear_tabla_trabajadores()
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    // Definir la estructura de la tabla
    $sql = "CREATE TABLE $tabla_trabajadores (
        dni VARCHAR(255) NOT NULL,
        nombre VARCHAR(255),
        apellido VARCHAR(255),
        usuario VARCHAR(255),
        email VARCHAR(255),
        password VARCHAR(50),
        fechaini VARCHAR(50),
        fechafin VARCHAR(50),
        PRIMARY KEY (dni)
    )";
    // Incluir el archivo necesario para ejecutar dbDelta()
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    // Crear o modificar la tabla en la base de datos
    dbDelta($sql);
}

function crear_tabla_jornadas()
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';

    // Definir la estructura de la tabla
    $sql = "CREATE TABLE $tabla_jornadas (
        id INT NOT NULL,
        dniTrabajador VARCHAR(255) NOT NULL,
        fecha VARCHAR(50),
        horaInicio VARCHAR(50),
        horaFin VARCHAR(50),
        PRIMARY KEY (id)
        
    )";
    // Incluir el archivo necesario para ejecutar dbDelta()
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    // Crear o modificar la tabla en la base de datos
    dbDelta($sql);
}
// Agregar la acción para crear la tabla de trabajadores al activar el plugin
register_activation_hook(__FILE__, 'crear_tabla_trabajadores');
register_activation_hook(__FILE__, 'crear_tabla_jornadas');

// ===========================================================================

function handle_preflight()
{
    $origin = '*';

    header('Access-Control-Allow-Origin: '.$origin);
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, X-WP-Nonce, Content-Type, Accept, Authorization');
    if ('OPTIONS' == $_SERVER['REQUEST_METHOD']) {
        status_header(200);
        exit;
    }
}

add_action('init', 'handle_preflight');

add_action('rest_api_init', 'registrar_endpoint_rest_trabajadores');