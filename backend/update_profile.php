<?php
chdir("/var/www/html");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | PetAdopt</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

        <?php include 'inc/navbar.inc.php'; ?>
    </head>
    <body>
        <main class="container">
        <?php
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            if(!isset($_POST['submit'])){
                echo 'Try accessing this page by pressing submit button'. '<br />';
                echo "<a href='/profile'>Goto Form Page</a>";
                exit();
            }
            $email = $errorMsg = "";
            $success = true;
            if (empty($_POST["email"])){
                $errorMsg .= "Email is required.<br>";
                $success = false;
            }
            else{
                $email = sanitize_input($_POST["email"]);
            
                // Additional check to make sure e-mail address is well-formed.
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errorMsg .= "Invalid email format.<br>";
                    $success = false;
                }
            }
    
            if (empty($_POST["lname"])){
                $errorMsg .= "Last name is required.<br>";
                $success = false;
            }

            if (empty($_POST["dob"])){
                $errorMsg .= "Date of Birth is required.<br>";
                $success = false;
            }
    
            if(empty($_POST["pwd"])){
                // reuse old password if there was no change
                $pwd_hashed = $_SESSION['pw_hash'];
            }
            else{
                $pwd_hashed = hash('sha256', $_POST["pwd"]);
            }
            $fname = sanitize_input($_POST["fname"]);
            $lname = sanitize_input($_POST["lname"]);
            $address = sanitize_input($_POST["address"]);
            $dob2 = strtotime($_POST["dob"]);
            $dob = date('Y-m-d', $dob2);

    
            if ($success){
                updateProfile($fname, $lname, $email, $address, $dob, $pwd_hashed);
                echo "<h4>Update successful!</h4>";
                echo "<p>Email: " . $email . "</p>";
                echo "<p>First Name: " . $fname . "</p>";
                echo "<p>Last Name: " . $lname . "</p>";
                echo "<p>Address: " . $address . "</p>";
                echo "<p>Date of Birth: " . $dob . "</p>";
                // echo "<p>pass hash: ";
                // var_dump($pwd_hashed);
                echo"</p>";
                
                echo "<a href=/home><button type=submit class='btn btn-success'>Return to Home</button></a>";
                unset($_SESSION['pw_hash']); 
            }
            else{
                echo "<h4><strong>The following input errors were detected:</strong></h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo "<a href=/home><button class='btn btn-danger'>Return to Home</button></a>";
            }
        }
        else{
            echo "<h4>Get request not allowed</h4>";
            echo "<p>go away</p>";
        }
        

        /*
        * Helper function that checks input for malicious or unwanted content.
        */
        function sanitize_input($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function debug_to_console($data) {
            $output = $data;
            if (is_array($output))
                $output = implode(',', $output);
        
            echo $output;
        }

        /*
        * Helper function to write the member data to the database.
        */
        function updateProfile($fname, $lname, $email, $address, $dob, $pwd_hashed){
            $userID = $_SESSION['id'];
           
            // Create database connection.
            require_once 'backend/db.php';

            // Prepare the statement:
            $stmt = $conn->prepare("UPDATE world_of_pets_members SET
            fname=?, lname=?, email=?, password=?, address=?, dateofbirth=? WHERE member_id=?");
            // Bind & execute the query statement:
            $stmt->bind_param("ssssssi", $fname, $lname, $email, $pwd_hashed, $address, $dob, $userID);
            if (!$stmt->execute()){
                $errorMsg = "Execute failed: (" . $stmt->errno . ")";
                $stmt->error;
                $success = false;
                debug_to_console($errorMsg);
            }
            $stmt->close();
        }
        ?>

        </main>

        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>