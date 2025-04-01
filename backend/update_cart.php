<?php
session_start();

// Debugging - uncomment to troubleshoot
// error_log('POST data: ' . print_r($_POST, true));
// error_log('SESSION cart: ' . print_r($_SESSION['cart'] ?? 'No cart', true));

// Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate required fields
        if (!isset($_POST['pet_id'], $_POST['quantity'])) {
            throw new Exception('Missing required fields');
        }

        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $petId = $_POST['pet_id'];
        $quantity = (int)$_POST['quantity'];

        // Validate quantity
        if ($quantity < 1) {
            throw new Exception('Quantity must be at least 1');
        }

        // Update cart if item exists
        if (isset($_SESSION['cart'][$petId])) {
            $_SESSION['cart'][$petId]['quantity'] = $quantity;
            $response = [
                'success' => true,
                'message' => 'Quantity updated successfully',
                'subtotal' => number_format($_SESSION['cart'][$petId]['price'] * $quantity, 2),
                'total' => calculateCartTotal()
            ];
        } else {
            throw new Exception('Item not found in cart');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Return JSON response for AJAX or redirect for normal form submission
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $_SESSION['flash_message'] = $response['message'];
    header('Location: /cart');
}

function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return number_format($total, 2);
}

exit;