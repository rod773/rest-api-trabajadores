<?php


// ELiminar Las rutas/endpoints por defecto
remove_action('rest_api_init', 'create_initial_rest_routes', 99);


// DeshabiLitar La WP REST API
add_filter('rest_enabled','__return_false'); 

add_filter('rest_json_enabled', '__return_false');


// Añadir ruta personaLizada
add_action('rest_api_init',



function(){
    register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'myawesomefunc',
        'permision_callback' => 'mycall_permission_callback'
        ));
}

);

?>