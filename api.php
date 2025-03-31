<?php
header('Content-Type: application/json');

// Configuración de la API Key (deberías configurar esto como variable de entorno en producción)
$API_KEY = 'AIzaSyCJDzJbEuz7YshbQn_7XVxCu3qzEyDa9kU';
$API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $API_KEY;

// Solo permitir solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener el cuerpo de la solicitud
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if (json_last_error() !== JSON_ERROR_NONE || !isset($data['contents'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

// Configurar la solicitud a la API de Gemini
$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ],
];

$context  = stream_context_create($options);
$response = file_get_contents($API_URL, false, $context);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al conectar con la API de Gemini']);
    exit;
}

// Devolver la respuesta de Gemini directamente
echo $response;
?>
