
<html>
    <head>
        <?php 
            include '../inc/head.inc.php';
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
            newEvent();
    
            if ($success){
                echo "<h4>Created successful!</h4>";
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
        function newEvent(){

            // Create database connection.
            global $title, $date, $time1, $time2, $venue, $details, $errorMsg, $success;

            require_once 'db.php';

            $time_range = $time1 . "-" . $time2;
            
            $date2 = date('F j, Y', strtotime($date));
            // Prepare the statement:
            $stmt = $conn->prepare("INSERT INTO events (title, date, time, venue, details) VALUES (?, ?, ?, ?, ?)");
            // Bind & execute the query statement:
            $stmt->bind_param("sssss", $title, $date2, $time_range, $venue, $details);
            if (!$stmt->execute()){
                $errorMsg = "Execute failed: (" . $stmt->errno . ") ";
                $stmt->error;
                $success = false;
                echo $errorMsg;
            }
            echo $errorMsg;
            $stmt->close();
            $conn->close();
        }

        ?>

        </main>

        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>