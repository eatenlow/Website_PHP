<html>
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>

    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Member Login</strong></h1>
            <p>
                Rxisting members log in here. For new members, please go to the
                <a href="/register">Member Registration page</a>.
            </p>
            <form action="../backend/process_login.php" method="post">

                <div class="mb-3">
                    <form-label for="email">Email:</form-label>
                    <input type="email" id="email" name="email" class="form-control" maxlength="45"
                    placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                    <form-label for="pwd">Password:</form-label>
                    <input type="password" id="pwd" name="pwd" class="form-control"
                    placeholder="Enter password" required>
                </div>

            <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>
