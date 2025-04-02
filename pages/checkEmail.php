<html lang="en">
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Forgot Password</strong></h1>
            <p>
                Please enter your account email here
            </p>
            <form action="../backend/check_user_exists.php" method="post">

                <div class="mb-3">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" maxlength="45"
                    placeholder="Enter email" required>
                </div>

                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>