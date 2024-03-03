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

// =========================AUTH============================//

function generar_token($request)
{
    
    $dni = $request->get_param('dni');

    $usuario = $request->get_param('usuario');

    $email = $request->get_param('email');  

    $arr = ['alg' => 'HS256', 'typ' => 'JWT'];
    $arr2 = json_encode($arr);
    $encoded_header = urlsafeB64Encode($arr2);

    $arr3 = ['email' => $email, 'password' => $password];
    $arr33 = json_encode($arr3);
    $encoded_payload = urlsafeB64Encode($arr33);

    $segments = [];

    $segments[] = $encoded_header;

    $segments[] = $encoded_payload;

    $header_payload = implode('.', $segments);

    $secret_key = '90481cd8c9b821da4a6f8a6aa72b4867b71986555819865f522111e71052e3ef';

    $signature = urlsafeB64Encode(hash_hmac('sha256', $header_payload, $secret_key, true));

    $segments[] = $signature;

    $jwt_token = implode('.', $segments);

    wp_send_json(['token' => $jwt_token]);
}

function leer_token($request)
{
    
    $authorization = $request->get_headers()['authorization'][0];

    $len = strlen($authorization);

    $recievedJwt = substr($authorization,7,$len);

    $secret_key = '90481cd8c9b821da4a6f8a6aa72b4867b71986555819865f522111e71052e3ef';

    $jwt_values = explode('.', $recievedJwt);

    $recieved_signature = $jwt_values[2];

    $segments = [];

    $segments[] = $jwt_values[0];

    $segments[] = $jwt_values[1];

    $recievedHeaderAndPayload = implode('.', $segments);

    $resultedsignature = urlsafeB64Encode(hash_hmac(
        'sha256', $recievedHeaderAndPayload, $secret_key, true));


    wp_send_json([
        "received sig"=>$recieved_signature,
        "resulted sig"=>$resultedsignature
    ]);
    

    if ($resultedsignature == $recieved_signature) {

        wp_send_json(["result"=>'Success']) ;
    } else {
        //echo 'Password no valida';
        wp_send_json(["result:"=>'Password no valido']) ;
    }
}

function urlsafeB64Encode(string $input): string
{
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}

function urlsafeB64Decode(string $input): string
{
    $remainder = strlen($input) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $input .= str_repeat('=', $padlen);
    }

    return base64_decode(strtr($input, '-_', '+/'));
}

// Función para registrar el endpoint de la API REST
function registrar_endpoint_rest_trabajadores()
{
    register_rest_route('auth/v1', '/token', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'generar_token',
    ]);

    register_rest_route('auth/v1', '/validate', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'leer_token',
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