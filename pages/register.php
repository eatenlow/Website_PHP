<html>
    <head>
        <title>World of Pets</title>
        <?php
            include "inc/head.inc.php";
        ?>
    </head>
    <body>
        <?php
        include "inc/nav.inc.php";
        ?>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Member Registration</strong></h1>
            <p>
                For existing members, please go to the
                <a href="#">Sign In page</a>.
            </p>
            <form action="../backend/process_register.php" method="post">
                <div class="mb-3">
                    <form-label for="fname">First Name:</form-label>
                    <input type="text" id="fname" name="fname" class="form-control" maxlength="45"
                    placeholder="Enter first name">
                </div>

                <div class="mb-3">
                    <form-label for="lname">Last Name:</form-label>
                    <input type="text" id="lname" name="lname" class="form-control" maxlength="45"
                    placeholder="Enter last name" required>
                </div>

                <div class="mb-3">
                    <form-label for="email">Email:</form-label>
                    <input type="email" id="email" name="email" class="form-control" maxlength="45"
                    placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                    <form-label for="address">Address:</form-label>
                    <input type="text" id="address" name="address" class="form-control" maxlength="255"
                    placeholder="Enter address" required>
                </div>

                <div class="mb-3">
                    <form-label for="dob">Date of Birth:</form-label>
                    <input type="date" id="dob" name="dob" class="form-control"
                    placeholder="Enter Date of Birth" required>
                </div>

                <div class="mb-3">
                    <form-label for="pwd">Password:</form-label>
                    <input type="password" id="pwd" name="pwd" class="form-control"
                    placeholder="Enter password" required>
                </div>

                <div class="mb-3">
                    <form-label for="pwd_confirm">Confirm Password:</form-label>
                    <input type="password" id="pwd_confirm" name="pwd_confirm" class="form-control"
                    placeholder="Confirm password" required>
                </div>

                <div class="mb-3 form-check">
                    <form-check-label>
                    <input type="checkbox" name="agree" class="form-check-input" required>
                    Agree to terms and conditions.
                    </form-check-label>
                </div>

            <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>
