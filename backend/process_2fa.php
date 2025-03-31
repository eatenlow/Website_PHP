<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = $errorMsg = "";
    $success = true;
    
    // Check if the verification code exists in the POST request
    if (empty($_POST["verification_code"])) {
        $errorMsg .= "Verification code is required.<br>";
        $success = false;
    } else {
        $code = sanitize_input($_POST["verification_code"]);
        
        // Verify the code against the one stored in the session
        if (!isset($_SESSION["verification_code"]) || $code != $_SESSION["verification_code"]) {
            $errorMsg .= "Invalid verification code.<br>";
            $success = false;
        }
    }
    
    if ($success) {
        // Clear the verification code from session
        unset($_SESSION["verification_code"]);
        unset($_SESSION["verification_code_time"]);
        
        // Complete the login process - transfer temp variables to permanent ones
        $_SESSION["login"] = true;
        $_SESSION["id"] = $_SESSION["temp_id"];
        
        // Check for admin status
        if (isset($_SESSION["temp_admin"]) && $_SESSION["temp_admin"] == 1) {
            $_SESSION["admin"] = $_SESSION["temp_admin"];
        }
        
        // Clean up temporary session variables
        unset($_SESSION["temp_login"]);
        unset($_SESSION["temp_id"]);
        unset($_SESSION["temp_email"]);
        unset($_SESSION["temp_fname"]);
        unset($_SESSION["temp_admin"]);
        
        // Redirect to homepage
        header("Location: ../index.php");
        exit;
    } else {
        // Show error with return button
        ?>
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="../css/styles.css">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <title>Verification Failed | PetAdopt</title>
                <?php include '../inc/navbar.inc.php'; ?>
            </head>
            <body>
                <main class="container">
                    <h4><strong>Verification Failed:</strong></h4>
                    <p><?php echo $errorMsg; ?></p>
                    <a href='../pages/2fa.php'><button class='btn btn-danger'>Try Again</button></a>
                </main>
                <?php include '../inc/footer.inc.php'; ?>
            </body>
        </html>
        <?php
    }
} else {
    echo "<h4>Get request not allowed</h4>";
    echo "<p>go away</p>";
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>