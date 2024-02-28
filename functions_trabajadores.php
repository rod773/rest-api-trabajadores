<?php

// Función para obtener un trabajador por ID
function obtener_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    $dni = $request['dni'];
    // Obtener el trabajador de la base de datos
    $trabajador = $wpdb->get_row("SELECT * FROM $tabla_trabajadores WHERE dni = $dni");

    return $trabajador;
}

function obtener_trabajadores($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $sql = "SELECT * FROM $tabla_trabajadores";
    $trabajadores = $wpdb->get_results($sql);

    wp_send_json($trabajadores);
}

// Función para crear un trabajador
function crear_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    $trabajador = [
        'dni' => $request->get_param('dni'),
        'nombre' => $request->get_param('nombre'),
        'apellido' => $request->get_param('apellido'),
        'usuario' => $request->get_param('usuario'),
        'email' => $request->get_param('email'),
        'password' => $request->get_param('password'),
        'fechaini' => $request->get_param('fechaini'),
        'fechafin' => $request->get_param('fechafin'),
    ];
    // Insertar el trabajador en la base de datos

    if ($wpdb->insert($tabla_trabajadores, $trabajador)) {
        // rest_ensure_response
        // rest_request_after_callbacks
        // rest_request_before_callbacks

        wp_send_json(['inserted' => true]);
    } else {
        wp_send_json(['inserted' => false]);
    }
}

// Función para actualizar un trabajador por ID
function actualizar_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    $dni = $request->get_param('dni');
    $trabajador = [
        'dni' => $request->get_param('dni'),
        'nombre' => $request->get_param('nombre'),
        'apellido' => $request->get_param('apellido'),
        'usuario' => $request->get_param('usuario'),
        'email' => $request->get_param('email'),
        'password' => $request->get_param('password'),
        'fechaini' => $request->get_param('fechaini'),
        'fechafin' => $request->get_param('fechafin'),
    ];

    if ($wpdb->update($tabla_trabajadores, $trabajador, ['dni' => $dni])) {
        wp_send_json(['updated' => true]);
    } else {
        wp_send_json(['updated' => false]);
    }
}
// Función para eliminar un trabajador por ID
function eliminar_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    $dni = $request->get_param('dni');

    if ($wpdb->delete($tabla_trabajadores, ['dni' => $dni])) {
        wp_send_json(['deleted' => true]);
    } else {
        wp_send_json(['deleted' => false]);
    }
}

// ==============================JORNADAS======================//

// ==========================funciones=========================//

function obtener_jornadas($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';

    $sql = "SELECT * FROM $tabla_jornadas";
    $jornadas = $wpdb->get_results($sql);

    wp_send_json($jornadas);
}

// Función para crear un trabajador
function crear_jornada($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';

    $jornada = [
    'id' => $request->get_param('id'),
    'dniTrabajador' => $request->get_param('dniTrabajador'),
    'fecha' => $request->get_param('fecha'),
    'horaInicio' => $request->get_param('horaInicio'),
    'horaFin' => $request->get_param('horaFin'),
    ];

    if ($wpdb->insert($tabla_jornadas, $jornada)) {
        wp_send_json(['inserted' => true]);
    } else {
        wp_send_json(['inserted' => false]);
    }
}

// Función para eliminar un jornada por ID
function eliminar_jornada($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';
    $id = $request->get_param('id');

    $sql = "delete from $tabla_jornadas where id=$id";

    if ($wpdb->query($sql)) {
        wp_send_json(['deleted' => true]);
    } else {
        wp_send_json(['deleted' => false]);
    }
}

function actualizar_jornada($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';
    $id = $request->get_param('id');
    $jornada = [
        'id' => $request->get_param('id'),
        'dniTrabajador' => $request->get_param('dniTrabajador'),
        'fecha' => $request->get_param('fecha'),
        'horaInicio' => $request->get_param('horaInicio'),
        'horaFin' => $request->get_param('horaFin'),
    ];

    if ($wpdb->update($tabla_jornadas, $jornada, ['id' => $id])) {
        wp_send_json(['updated' => true]);
    } else {
        wp_send_json(['updated' => false]);
    }
}

// =========================endpoints============================//

function generar_token($request)
{
    $userId = $request->get_param('userId');

    $name = $request->get_param('name');

    $time = time();
    $key = 'my_secret_key';

    $token = array(
    'iat' => $time, // Tiempo que inició el token
    'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
    'data' => [ // información del usuario
       'id' => $userId, // key 
       'name' => $name // secret
     ]);
    $jwt = JWT::encode($token, $key);

    wp_send_json(['token' => $jwt]);
}

// Función para registrar el endpoint de la API REST
function registrar_endpoint_rest_trabajadores()
{
    register_rest_route('auth/v1', '/token', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'generar_token',
    ]);


    register_rest_route('trabajadores/v1', '/(?P<dni>\d+)', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'obtener_trabajador',
        'args' => [
            'dni' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param);
                },
            ],
        ],
    ]);

    register_rest_route('trabajadores/v1', '/todos', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'obtener_trabajadores',
    ]);

    register_rest_route('trabajadores/v1', '/add', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'crear_trabajador',
    ]);

    register_rest_route('trabajadores/v1', '/update', [
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'actualizar_trabajador',
    ]);
    register_rest_route('trabajadores/v1', '/delete', [
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'eliminar_trabajador',
    ]);

    register_rest_route('jornadas/v1', '/all', [
         'methods' => WP_REST_Server::READABLE,
         'callback' => 'obtener_jornadas',
    ]);

    register_rest_route('jornadas/v1', '/add', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'crear_jornada',
    ]);

    register_rest_route('jornadas/v1', '/delete', [
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'eliminar_jornada',
    ]);

    register_rest_route('jornadas/v1', '/update', [
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'actualizar_jornada',
    ]);
}