<?php

$request = $_SERVER['REQUEST_URI'];

$viewDir = '/pages/';
switch ($request) {
    case '':
        require __DIR__ . $viewDir . 'home.php';
        break;
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;

    case '/profile':
        require __DIR__ . $viewDir . 'profile.php';
        break;

    case '/register':
        require __DIR__ . $viewDir . 'register.php';
        break;

    case '/login':
        require __DIR__ . $viewDir . 'login.php';
        break;

    case '/admin':
        require __DIR__ . $viewDir . 'admin.php';
        break;
    
    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo $output;
}
?>