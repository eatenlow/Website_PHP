
<html>
    <head>
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
            if (empty($_POST["title"])){
                $errorMsg .= "title is required.<br>";
                $success = false;
            }
            else{
                $title = sanitize_input($_POST["title"]);
            }
            echo "KO";
            if (empty($_POST["date"])){
                $errorMsg .= "date is required.<br>";
                $success = false;
            }
            else{
                $tempDate = new DateTime($_POST["date"]);
                $now = new DateTime();

                if($tempDate < $now) {
                    $errorMsg .= "date cannot be in the past.<br>";
                    $success = false;
                }
                else{
                    $date = sanitize_input($_POST["date"]);
                }
            }
    
            if(empty($_POST["time1"])){
                $errorMsg .= "Start Time is required.<br>";
                $success = false;
            }
            else{
                $time1 = sanitize_input($_POST["time1"]);
            }

            if(empty($_POST["time2"])){
                $errorMsg .= "End Time is required.<br>";
                $success = false;
            }
            else{
                $time2 = sanitize_input($_POST["time2"]);
            }

            if(strtotime($time1) > strtotime($time2)){
                $success = false;
                $errorMsg .= "Start time cannot be AFTER end time.";
            }
            else{
                $time1 = date('h:i A', strtotime($time1));
                $time2 = date('h:i A', strtotime($time2));
            }

            if(empty($_POST["venue"])){
                $errorMsg .= "venue is required.<br>";
                $success = false;
            }
            else{
                $venue = sanitize_input($_POST["venue"]);
            }

            if(empty($_POST["details"])){
                $errorMsg .= "details is required.<br>";
                $success = false;
            }
            else{
                $details = sanitize_input($_POST["details"]);
            }

            echo $errorMsg;

            updateEvent();
    
            if ($success){
                echo "<h4>Update successful!</h4>";
                echo "<p>Name: " . $title . "</p>";
                echo "<p>date: " . $date . "</p>";
                echo "<p>Start Time: " . $time1 . "</p>";
                echo "<p>End Time: " . $time2 . "</p>";
                echo "<p>Venue: " . $venue . "</p>";
                echo "<p>Description: " . $details . "</p>";
                
                echo "<a href=/home><button type=submit class='btn btn-success'>Back to Home</button></a>";
            }
            else{
                echo "<h4><strong>The following input errors were detected:</strong></h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo "<a href=/home><button class='btn btn-danger'>Return to Home</button></a>";
            }
        }
        else{
            echo $_SESSION["login"];

            echo time();
            echo $_SESSION['LAST_ACTIVITY'];

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

        function debug_to_console($data) {
            $output = $data;
            if (is_array($output))
                $output = implode(',', $output);
        
            echo $output;
        }

        /*
        * Helper function to write the member data to the database.
        */
        function updateEvent(){
            $eventID = $_SESSION['edit_event_id'];

            // Create database connection.
            global $title, $date, $time1, $time2, $venue, $details, $errorMsg, $success;
            require_once 'db.php';
            
            $time_range = $time1 . "-" . $time2;
            
            $date2 = date('F j, Y', strtotime($date));

            // Prepare the statement:
            $stmt = $conn->prepare("UPDATE events SET
            title=?, date=?, time=?, venue=?, details=? WHERE id=?");
            // Bind & execute the query statement:
            $stmt->bind_param("sssssi", $title, $date2, $time_range, $venue, $details, $eventID);

            if (!$stmt->execute()){
                $errorMsg = "Execute failed: (" . $stmt->errno . ") ";
                $stmt->error;
                $success = false;
                debug_to_console($errorMsg);
            }
          
            $stmt->close();

            $conn->close();
        }


        function photoVerify(){
            global $image, $errorMsg;
            $target_dir = "/listingImages/";
            $uploadOk = 1;
            $image = basename($_FILES["image"]["name"]);
            if($_FILES["image"]["error"] != 0){
                $image = $_POST['image'];
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