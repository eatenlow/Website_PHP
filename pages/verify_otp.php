<?php
// verify_otp.php (New file for OTP verification)
session_start();

if (!isset($_SESSION['2fa_user_id']) || !isset($_SESSION['2fa_otp']) || !isset($_SESSION['2fa_otp_expiry'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['2fa_user_id'];
$stored_otp = $_SESSION['2fa_otp'];
$otp_expiry = $_SESSION['2fa_otp_expiry'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entered_otp = $_POST['otp'];
    echo $entered_otp;
    echo $stored_otp;

    if ($entered_otp == $stored_otp && time() < $otp_expiry) {
        // OTP is correct and not expired
        $_SESSION['login'] = true;
        $_SESSION['id'] = $userID;

        unset($_SESSION['2fa_user_id']); // Remove 2FA session variables
        unset($_SESSION['2fa_otp']);
        unset($_SESSION['2fa_otp_expiry']);

        header("Location: /");
        exit;
    } else {
        $errorMsg = "Invalid or expired OTP.";
    }
}
?>

<html>
<head>
    <?php
    include 'inc/head.inc.php';
    ?>
</head>
<body>
    <?php include 'inc/navbar.inc.php'; ?>
    <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Verify OTP</strong></h1>
        <p>
            Please enter the One-Time Password sent to your email.
        </p>
        <form method="post">
            <?php if (isset($errorMsg)) echo "<p style='color:red;'>$errorMsg</p>"; ?>

            <div class="mb-3">
                <label for="otp">Enter OTP:</label>
                <input type="text" name="otp" id="otp" class="form-control" placeholder="Enter OTP" required>
            </div>

            <button class="btn btn-primary" type="submit">Verify</button>
        </form>
    </main>
    <?php
    include "inc/footer.inc.php";
    ?>
</body>
</html>