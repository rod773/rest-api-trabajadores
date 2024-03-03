<?php

require_once __DIR__.'/vendor/autoload.php';

use Firebase\JWT\JWT;

// Función para generar un token JWT
function generateToken($user_id)
{
    $key = 'secret_key';
    $payload = [
      'iss' => 'localhost',
      'sub' => $user_id,
      'iat' => time(),
      'exp' => time() + (60 * 60 * 24),
    ];

    $token = JWT::encode($payload, $key);

    return $token;
}

// Función para verificar un token JWT
function verifyToken($token)
{
    $key = 'secret_key';
    try {
        $decoded = JWT::decode($token, $key, ['HS256']);
        $user_id = $decoded->sub;

        return ['success' => true, 'user_id' => $user_id];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Si el formulario de inicio de sesión se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar las credenciales del usuario
    if ($email === 'usuario@example.com' && $password === 'contraseña') {
        // Generar un token JWT para el usuario
        $token = generateToken(123);

        // Almacenar el token en una cookie
        setcookie('token', $token, time() + (60 * 60 * 24), '/');

        // Redireccionar al usuario a la página protegida
        header('Location: protegida.php');
        exit;
    } else {
        echo 'Credenciales inválidas.';
    }
}

// Si el usuario intenta acceder a la página protegida
if ($_SERVER['REQUEST_URI'] === '/protegida.php') {
    // Verificar el token JWT almacenado en la cookie
    if (isset($_COOKIE['token'])) {
        $token = $_COOKIE['token'];
        $result = verifyToken($token);

        // Permitir que el usuario acceda a los recursos protegidos si el token es válido
        if ($result['success'] === true) {
            echo '¡Bienvenido, usuario #'.$result['user_id'].'!';
        } else {
            // Redireccionar al usuario a la página de inicio de sesión si el token es inválido
            header('Location: inicio.php');
            exit;
        }
    } else {
        // Redireccionar al usuario a la página de inicio de sesión si no hay token
        header('Location: inicio.php');
        exit;
    }
}