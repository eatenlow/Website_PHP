
<html>
    <head>
        <!-- <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | PetAdopt</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"> -->

        <?php 
            include '../inc/head.inc.php';
            // include dirname(__DIR__) . '/inc/navbar.inc.php'; 
            include '../inc/navbar.inc.php'; 

        ?>
    </head>
    <body>
        <main class="container">
        <?php
        if(!isset($_POST['submit'])){
            echo 'Try accessing this page by pressing submit button'. '<br />';
            echo "<a href=/>Back to Home</a>";
            exit();
        }
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["login"]) && ($_SESSION["admin"] == '1')){
            $errorMsg = "";
            $success = true;
            if (photoVerify() == 0){
                $success = false;
            }
            if (empty($_POST["name"])){
                $errorMsg .= "Name is required.<br>";
                $success = false;
            }
            else{
                $name = sanitize_input($_POST["name"]);
            }
    
            if (empty($_POST["type"])){
                $errorMsg .= "Type is required.<br>";
                $success = false;
            }
            else{
                $type = sanitize_input($_POST["type"]);
            }
    
            if(empty($_POST["breed"])){
                $errorMsg .= "Breed is required.<br>";
                $success = false;
            }
            else{
                $breed = sanitize_input($_POST["breed"]);
            }

            if(empty($_POST["age"])){
                $errorMsg .= "Age is required.<br>";
                $success = false;
            }
            else{
                $age = sanitize_input($_POST["age"]);
            }

            if(empty($_POST["gender"])){
                $errorMsg .= "Gender is required.<br>";
                $success = false;
            }
            else{
                $gender = sanitize_input($_POST["gender"]);
            }

            if(empty($_POST["cost"])){
                $errorMsg .= "Cost is required.<br>";
                $success = false;
            }
            else{
                $cost = sanitize_input($_POST["cost"]);
            }
            $desc = sanitize_input($_POST['desc']);

            newListing();
    
            if ($success){
                echo "<h4>Created successful!</h4>";
                echo "<p>Name: " . $name . "</p>";
                echo "<p>Type: " . $type . "</p>";
                echo "<p>Breed: " . $breed . "</p>";
                echo "<p>Age: " . $age . "</p>";
                echo "<p>Gender: " . $gender . "</p>";
                echo "<p>Cost: " . $cost . "</p>";
                echo "<p>Description: " . $desc . "</p>";
                
                echo "<a href=/home><button type=submit class='btn btn-success'>Back to Home</button></a>";
            }
            else{
                echo "<h4><strong>The following input errors were detected:</strong></h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo "<a href=/home><button class='btn btn-danger'>Return to Home</button></a>";
            }
        }
        else{

            echo "<h4>Get request not allowed</h4>";
            echo "<p>go awayZ</p>";
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

        /*
        * Helper function to write the member data to the database.
        */
        function newListing(){

            // Create database connection.
            global $name, $type, $breed, $age, $cost, $gender, $desc, $image, $errorMsg, $success;
            require_once 'db.php';

            // Prepare the statement:
            $stmt = $conn->prepare("INSERT INTO pets (pet_name, pet_type, breed, age, adopt_cost, gender, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            // Bind & execute the query statement:
            $stmt->bind_param("sssiisss", $name, $type, $breed, $age, $cost, $gender, $desc, $image);
            if (!$stmt->execute()){
                $errorMsg = "Execute failed: (" . $stmt->errno . ") ";
                $stmt->error;
                $success = false;
                echo $errorMsg;
            }
            $stmt->close();
            $conn->close();
        }

        function photoVerify(){
            global $image, $errorMsg;
            $target_dir = "/listingImages/";
            $uploadOk = 1;
            $image = basename($_FILES["image"]["name"]);
            echo $image;
            if(($_FILES["image"]["error"] != 0) ){
                $image = ' ';
            }
            else{
                $target_file = dirname(__DIR__) . $target_dir . $image;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                  $check = getimagesize($_FILES["image"]["tmp_name"]);
                  if($check !== false) {
                    $errorMsg .= "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                  } else {
                    $errorMsg .= "File is not an image.";
                    $uploadOk = 0;
                  }
                }
                if (file_exists($target_file)) {
                    $errorMsg .= "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["image"]["size"] > 500000) {
                    $errorMsg .= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
    
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $errorMsg .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
    
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $errorMsg .= "Sorry, your file was not uploaded.";
                
                    // if everything is ok, try to upload file
                } 
                else {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $errorMsg .= "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
                    } else {
                        $errorMsg .= "Sorry, there was an error uploading your file.";
                    }
                }
            }
            return $uploadOk;
        }
        ?>

        </main>

        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>