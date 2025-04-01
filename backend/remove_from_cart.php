<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    $petId = $_POST['pet_id'];
    if (isset($_SESSION['cart'][$petId])) {
        unset($_SESSION['cart'][$petId]);
    }
}

header('Location: /cart');
exit;
?>