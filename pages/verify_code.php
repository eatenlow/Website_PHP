<?php
session_start();

// Check if the user is already logged in (optional)
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: /"); // Redirect to homepage
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enteredCode = $_POST['verification_code'];

    // Check if the entered code matches the stored code
    if ($enteredCode == $_SESSION['verification_code']) {
        // Verification successful, complete the login
        $_SESSION['login'] = true;
        unset($_SESSION['verification_code']); // Remove the code from the session
        header("Location: /"); // Redirect to homepage
        exit;
    } else {
        $errorMsg = "Incorrect verification code.";
    }
}
?>

<html>
<head>
    <title>Verify Code</title>
    </head>
<body>
    <main class="container">
        <h1>Enter Verification Code</h1>
        <?php if (isset($errorMsg)) { echo "<p style='color: red;'>$errorMsg</p>"; } ?>
        <form method="post">
            <label for="verification_code">Verification Code:</label>
            <input type="text" name="verification_code" id="verification_code" required>
            <button type="submit">Verify</button>
        </form>
    </main>
</body>
</html>