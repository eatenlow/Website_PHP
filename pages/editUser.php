<?php
require_once 'backend/db.php';
global $conn;

// Check if user is logged in and is admin
session_start();
if(!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')){
    header("Location: /a");
    exit();
}

// Initialize variables
$errorMsg = '';
$successMsg = '';
$userData = [];

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
        $errorMsg = "First name, last name and email are required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email format.";
    } else {
        // Update database
        $stmt = $conn->prepare("UPDATE world_of_pets_members SET fname=?, lname=?, email=?, address=?, dateofbirth=?, admin=? WHERE member_id=?");
        $stmt->bind_param("sssssii", $fname, $lname, $email, $address, $dateofbirth, $admin, $userId);
        
        if ($stmt->execute()) {
            $successMsg = "User updated successfully!";
            // Refresh user data
            $stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE member_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();
        } else {
            $errorMsg = "Error updating user: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<html lang="en">
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
        <title>Edit User</title>
    </head>
<body>    
    <main class="container">
        <h1><strong>Edit User</strong></h1>
        <a href="/manageUser" class='btn btn-secondary mb-3'>Back to User Management</a>
        
        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success"><?php echo $successMsg; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" 
                       value="<?php echo htmlspecialchars($userData['fname']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" 
                       value="<?php echo htmlspecialchars($userData['lname']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address"><?php echo htmlspecialchars($userData['address']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="dateofbirth" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dateofbirth" name="dateofbirth" 
                       value="<?php echo htmlspecialchars($userData['dateofbirth']); ?>">
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="admin" name="admin" 
                       <?php echo $userData['admin'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="admin">Admin User</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </main>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>