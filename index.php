<?php

//$request = $_SERVER['REQUEST_URI'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$viewDir = '/pages/';
switch ($request) {
    case '':
    case '/':
    case '/home':
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

    case '/logout':
        require __DIR__ . $viewDir . 'logout.php';
        break;
    
    case '/manageList':
        require __DIR__ . $viewDir . 'manageList.php';
        break;

    case '/editList':
        require __DIR__ . $viewDir . 'editList.php';
        break;
    
    case '/addList':
        require __DIR__ . $viewDir . 'addList.php';
        break;
        
    case '/listings':
        require __DIR__ . $viewDir . 'listings.php';
        break;

    case '/about':
        require __DIR__ . $viewDir . 'about.php';
        break;

    case '/checkout':
        require __DIR__ . $viewDir . 'checkout.php';
        break;

    case '/events':
        require __DIR__ . $viewDir . 'events.php';
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