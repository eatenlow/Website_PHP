<html lang="en">
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        
        <?php
            echo '<h1><strong>Edit Event ' . $_GET['id'] . '</strong></h1>';
            if(!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')){
                header("Location: /a");
            } 
            function getEvent(){
                global $title, $date, $time_range, $time1, $time2, $venue, $details, $errorMsg, $success;
                require_once 'backend/db.php';
                $eventID = $_GET['id'];
                $_SESSION['edit_event_id'] = $eventID;
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT * FROM events WHERE id=?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $eventID);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $title = $row["title"];
                    $date = $row["date"];
                    $time_range = $row['time'];
                    $venue = $row["venue"];
                    $details = $row['details'];

                    $times = explode('-', $time_range);
                    $preTime1 = (explode(' ', $times[0]));
                    if($preTime1[1] == "PM"){
                        $tempTime = explode(' ', $times[0])[0];
                        $hour = (int)explode(':', $tempTime)[0];
                        if($hour != 12){
                            $hour += 12;
                        }
                        $time1 = $hour . ":" . explode(':', $tempTime)[1];
                    }
                    else{
                        $tempTime = explode(' ', $times[0])[0];
                        $hour = (int)explode(':', $tempTime)[0];
                        if($hour < 10){
                            $time1 = '0' . $hour . ":" . explode(':', $tempTime)[1];
                        }
                        else{
                            $time1 = $preTime1[0];
                        }
                       
                    }
                    $preTime2 = (explode(' ', $times[1]));
                    if($preTime2[1] == "PM"){
                        $tempTime = explode(' ', $times[1])[0];
                        $hour = (int)explode(':', $tempTime)[0];
                        if($hour != 12){
                            $hour += 12;
                        }
                        $time2 = $hour . ":" . explode(':', $tempTime)[1];
                    }
                    else{
                        $tempTime = explode(' ', $times[1])[0];
                        $hour = (int)explode(':', $tempTime)[0];
                        if($hour < 10){
                            $time2 = '0' . $hour . ":" . explode(':', $tempTime)[1];
                        }
                        else{
                            $time2 = $preTime2[0];
                        }
                    }
                }
                else{
                    // invalid listing ID redirect
                    header("Location: /a");
                    $success = false;
                }
                $stmt->close();
                $conn->close();
            }

            getEvent();
        ?>
            <a href="/manageEvents" class='btn btn-secondary mb-3'>Back to Manage Events</a>

            <form action="/backend/update_event.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <form-label for="title">Title:</form-label>
                    <input type="text" id=title name="title" aria-label='event title' value="<?= htmlspecialchars($title) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="date">Date:</form-label>
                    <input type="text" id="date" name="date" class="form-control" aria-label='date'
                    value="<?= $date ?>" required onfocus="(this.type='date')" onblur="(this.type='text')" required/>
                </div>

                <div class="mb-3">
                    <form-label for="time1">Start Time:</form-label>
                    <input type="time" id=time1 name="time1" aria-label='start time' value="<?= htmlspecialchars($time1) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="time2">End Time:</form-label>
                    <input type="time" id=time2 name="time2" aria-label='end time' value="<?= htmlspecialchars($time2) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="venue">Venue:</form-label>
                    <input type="text" id=venue name="venue" aria-label='Venue' value="<?= htmlspecialchars($venue) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="details">Details:</form-label>
                    <input type="text" id=details name="details" aria-label='details' value="<?= htmlspecialchars($details) ?>" class="form-control" maxlength="255" required>
 
                </div>

                <button class="btn btn-primary" type="submit" aria-label='update event' name='submit'>Update Event</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>
