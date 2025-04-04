<html>
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Profile</strong></h1>
        <?php
            if(!isset($_SESSION["login"])){
                header("Location: /");
            } 
            function getUser(){
                global $fname, $lname, $email, $pwd_hashed, $address, $dob, $dob_display, $errorMsg, $success;
                // Create database connection.
                $config = parse_ini_file('/var/www/private/db-config.ini');
                if (!$config){
                    $errorMsg = "Failed to read database config file.";
                    $success = false;
                }
                else{
                    $conn = new mysqli(
                    $config['servername'],
                    $config['username'],
                    $config['password'],
                    $config['dbname']
                    );
                    // Check connection
                    if ($conn->connect_error){
                        $errorMsg = "Connection failed: " . $conn->connect_error;
                        $success = false;
                    }
                    else{
                        $userID = $_SESSION["id"];
                        // Prepare the statement:
                        $stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE member_id=?");
                        // Bind & execute the query statement:
                        $stmt->bind_param("i", $userID);
                        $stmt->execute();
                        
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0){
                            // Note that email field is unique, so should only have one row.
                            $row = $result->fetch_assoc();
                            // $userID = $row["member_id"];
                            $fname = $row["fname"];
                            $lname = $row["lname"];
                            $email = $row['email'];
                            $pwd_hashed = $row["password"];
                            $address = $row['address'];
                            $dob = $row['dateofbirth'];
                            // $dob_display = date('d-m-Y',strtotime($dob));
                            $_SESSION['pw_hash'] = $pwd_hashed;
                        }
                        else{
                            $errorMsg = "Login Expired...";
                            $success = false;
                        }
                    $stmt->close();
                    }
                $conn->close();
                }
            }

            getUser();
        ?>
            <form action="../backend/update_profile.php" method="post">
                <div class="mb-3">
                    <form-label for="fname">First Name:</form-label>
                    <?php 
                    echo '<input type="text" id="fname" name="fname" class="form-control" maxlength="45"
                    value='.$fname.'>'
                    ?>
                </div>

                <div class="mb-3">
                    <form-label for="lname">Last Name:</form-label>
                    <?php 
                    echo '<input type="text" id="lname" name="lname" class="form-control" maxlength="45"
                    value=' .$lname.' required>'
                    ?>
                </div>

                <div class="mb-3">
                    <form-label for="pwd">Email:</form-label>
                    <?php 
                    echo '<input type="email" id="email" name="email" class="form-control" maxlength="45"
                    value='.$email.' required>'
                    ?>
                </div>

                <div class="mb-3">
                    <form-label for="address">Address:</form-label>
                        <input type="text" id="address" name="address" class="form-control"
                        maxlength="255" value="<?= $address ?>" required>
                    
                </div>

                <div class="mb-3">
                    <form-label for="dob">Date of Birth:</form-label>
                    <?php 
                    echo '<input type="date" id="dob" name="dob" class="form-control"
                    value='.$dob.' required/>'
                    ?>
                </div>

                <div class="mb-3">
                    <form-label for="pwd">Password:</form-label>
                    <input type="password" id="pwd" name="pwd" class="form-control"
                    placeholder="Enter new password" value="">
                </div>

                <button class="btn btn-primary" type="submit" name='submit'>Update Details</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>
