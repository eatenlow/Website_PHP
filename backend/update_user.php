<?php
require_once 'db.php';
global $conn;

// Check if user is logged in and is admin
session_start();
if(!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')){
    header("Location: /a");
    exit();
}

// Check if ID parameter is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /manageUser");
    exit();
}

$userId = $_GET['id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE member_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /manageUser");
    exit();
}

$userData = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $dateofbirth = trim($_POST['dateofbirth']);
    $admin = isset($_POST['admin']) ? 1 : 0;
    
    // Basic validation
    if (empty($fname) || empty($lname) || empty($email)) {
        $_SESSION['errorMsg'] = "First name, last name and email are required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errorMsg'] = "Invalid email format.";
    } else {
        // Update database
        $stmt = $conn->prepare("UPDATE world_of_pets_members SET fname=?, lname=?, email=?, address=?, dateofbirth=?, admin=? WHERE member_id=?");
        $stmt->bind_param("sssssii", $fname, $lname, $email, $address, $dateofbirth, $admin, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['successMsg'] = "User updated successfully!";
            // Refresh user data
            $stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE member_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();
            $_SESSION['userData'] = $userData;
        } else {
            $_SESSION['errorMsg'] = "Error updating user: " . $stmt->error;
        }
        
        $stmt->close();
    }
    
    // Redirect back to edit page
    header("Location: /editUser?id=" . $userId);
    exit();
}

// Store user data in session for the frontend
$_SESSION['userData'] = $userData;
$conn->close();

// Redirect to frontend
header("Location: /editUser?id=" . $userId);
exit();
?>