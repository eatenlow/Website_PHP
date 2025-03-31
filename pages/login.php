<html>
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
        <!-- Add the reCAPTCHA script -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Member Login</strong></h1>
            <p>
                Existing members log in here. For new members, please go to the
                <a href="/register">Member Registration page</a>.
            </p>
            <form action="../backend/process_login.php" method="post">

                <div class="mb-3">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" maxlength="45"
                    placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                    <label for="pwd">Password:</label>
                    <input type="password" id="pwd" name="pwd" class="form-control"
                    placeholder="Enter password" required>
                </div>
                
                <!-- Add the reCAPTCHA widget -->
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey=<?= file_get_contents("/var/www/private/site-key.txt") ?>></div>
                </div>

                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>