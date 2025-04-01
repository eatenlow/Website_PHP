<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $petId = $_POST['pet_id'];
    if (isset($_SESSION['cart'][$petId])) {
        $_SESSION['cart'][$petId]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$petId] = [
            'name' => $_POST['pet_name'],
            'price' => $_POST['pet_price'],
            'image' => $_POST['pet_image'], // Make sure this is passed from the form
            'quantity' => 1
        ];
    }

    header('Location: /cart');
    exit;
} else {
    header('Location: /listings');
    exit;
}