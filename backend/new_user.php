<?php
require_once '../backend/db.php';
session_start();

// Check admin privileges
if (!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')) {
    header("Location: /login");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    try {
        // Validate input
        if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email']) || empty($_POST['password'])) {
            throw new Exception("All required fields must be filled");
        }

        // Prepare data
        $fname = $conn->real_escape_string(trim($_POST['fname']));
        $lname = $conn->real_escape_string(trim($_POST['lname']));
        $email = $conn->real_escape_string(trim($_POST['email']));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $address = isset($_POST['address']) ? $conn->real_escape_string(trim($_POST['address'])) : '';
        $dob = isset($_POST['dateofbirth']) ? $conn->real_escape_string(trim($_POST['dateofbirth'])) : null;
        $admin = isset($_POST['admin']) ? 1 : 0;

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT email FROM world_of_pets_members WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            throw new Exception("Email already exists");
        }
        $checkStmt->close();

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO world_of_pets_members (fname, lname, email, password, address, dateofbirth, admin) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssssi", $fname, $lname, $email, $password, $address, $dob, $admin);
        
        if ($stmt->execute()) {
            // Success - redirect to manage users
            $stmt->close();
            header("Location: /manageUser");
            exit();
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Redirect back with error message
        $errorMsg = urlencode($e->getMessage());
        header("Location: /addUser?error=" . $errorMsg);
        exit();
    } finally {
        if (isset($stmt) && $stmt) {
            $stmt->close();
        }
    }
} else {
    // If not a POST request, redirect to add user page
    header("Location: /addUser");
    exit();
}
?>