<?php
require_once '../backend/db.php';
session_start();

// Check admin privileges
if (!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')) {
    header("Location: /login");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    $stmt = $conn->prepare("DELETE FROM world_of_pets_members WHERE member_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: /manageUser");
exit();
?>