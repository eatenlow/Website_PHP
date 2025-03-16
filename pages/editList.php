<html>
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Edit Listing <? $_GET['id'] ?></strong></h1>
        <?php
            if(!isset($_SESSION["login"]) || ($_SESSION["admin"] != 1)){
                header("Location: /a");
            } 
            function getListing(){
                global $name, $type, $breed, $age, $cost, $gender, $errorMsg, $success;
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
                        $petID = $_GET['id'];
                        $_SESSION['edit_pet_id'] = $petID;
                        // Prepare the statement:
                        $stmt = $conn->prepare("SELECT * FROM pets WHERE pet_ID=?");
                        // Bind & execute the query statement:
                        $stmt->bind_param("i", $petID);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                            $name = $row["pet_name"];
                            $type = $row["pet_type"];
                            $breed = $row['breed'];
                            $age = $row["age"];
                            $cost = $row['adopt_cost'];
                            $gender = $row['gender'];
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

            getListing();
        ?>
            <form action="../backend/update_listing.php" method="post">
                <div class="mb-3">
                    <form-label for="fname">Pet Name:</form-label>
                    <input type="text" id=name name="name" value="<?= htmlspecialchars($name) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="type">Pet Type:</form-label>
                    <input type="text" id=type name="type" value="<?= htmlspecialchars($type) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="breed">Breed:</form-label>
                    <input type="text" id=breed name="breed" value="<?= htmlspecialchars($breed) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="age">Age:</form-label>
                    <input type="number" id=age name="age" value="<?= htmlspecialchars($age) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="gender">Gender:</form-label>
                    <?php 
                        echo '
                        <select name="hall" id="hall" value='.$gender.'>
                          <option value="Male">Male</option>
                          <option "Female">Female</option>
                        </select>
                        ';
                    ?>
                </div>

                <div class="mb-3">
                    <form-label for="cost">Adoption Cost:</form-label>
                    <input type="number" id=cost name="cost" value="<?= htmlspecialchars($cost) ?>" class="form-control" maxlength="45" required>
                </div>

                <button class="btn btn-primary" type="submit">Update Details</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>
