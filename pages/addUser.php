<?php
require_once 'backend/db.php';
session_start();

// Check admin privileges
if (!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')) {
    header("Location: /login");
    exit();
}

// Initialize variables for form fields
$fname = $lname = $email = $address = $dob = '';
$admin = 0;

// Check for error message passed from new_user.php
if (isset($_GET['error'])) {
    $errorMsg = urldecode($_GET['error']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
    <?php include "inc/head.inc.php"; ?>
</head>
<body>
    <?php include "inc/navbar.inc.php"; ?>
    
    <main class="container">
        <h1><strong>Add New User</strong></h1>
        <a href="/manageUser" class='btn btn-secondary mb-3'>Back to User Management</a>
        
        <?php if (isset($errorMsg)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>
        
        <form method="post" action="/backend/new_user.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">First Name*</label>
                <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($fname); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name*</label>
                <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($lname); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email*</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password*</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dateofbirth" class="form-control" value="<?php echo htmlspecialchars($dob); ?>">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="admin" class="form-check-input" <?php echo $admin ? 'checked' : ''; ?>>
                <label class="form-check-label">Admin Privileges</label>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add User</button>
        </form>
    </main>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>