<?php
require_once 'db.php';
require 'sendEmail.php';

function handleEventAction($user_id)
{
    global $conn;
    if (($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']))) {
        $event_id = $_POST['event_id'];
        if ($_POST['action'] === "register") {
            if(!checkAlreadyRegistered($user_id, $event_id)){
                $stmt = $conn->prepare("INSERT INTO event_registration (user_id, event_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $event_id);
                if ($stmt->execute()) {
                    echo "<p>Successfully registered for the event!</p>";
                    $stmt->close();
                    notifyUserRegistration($user_id, $event_id);
                } else {
                    echo "<p>Error registering for event.</p>";
                    $stmt->close();
                }
                
            }
            else{
                echo "<p>Already registered for the event..</p>";
            }
            // $conn->close();
        }
        elseif ($_POST['action'] === "cancel") {
            $stmt = $conn->prepare("DELETE FROM event_registration WHERE user_id = ? AND event_id = ?");
            $stmt->bind_param("ii", $user_id, $event_id);
            if ($stmt->execute()) {
                echo "<p>Successfully canceled your registration.</p>";
                $stmt->close();
                notifyUserCancel($user_id, $event_id);
            } else {
                echo "<p>Error canceling registration.</p>";
                $stmt->close();
            }
            // $conn->close();
        }

    }
}

function checkAlreadyRegistered($user_id, $event_id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM event_registration WHERE user_id = ? AND event_id = ?");
    $stmt->bind_param("ii", $user_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0){
        $stmt->close();
        // $conn->close();
        return true;
    } else {
        $stmt->close();
        // $conn->close();
        return false;
    }}

function notifyUserRegistration($user_id, $event_id){
    [$fname, $lname, $email] = getUserDetails($user_id);
    [$title, $date, $time, $venue] = getEventDetails($event_id);
    $fullName = $fname . $lname;
    $mailSubject = "Thank you for registering!";
    $mailMessage = "Dear $fullName,\n
                    Your registration for $title has been noted. The details of the event are as follows:\n
                    Date: $date\n
                    Time: $time\n
                    Venue: $venue";
    sendEmail($fullName, $email, $mailMessage, $mailSubject);
}

function notifyUserCancel($user_id, $event_id){
    [$fname, $lname, $email] = getUserDetails($user_id);
    [$title, $date, $time, $venue] = getEventDetails($event_id);
    $fullName = $fname . $lname;
    $mailSubject = "Cancellation Notice";
    $mailMessage = "Dear $fullName,\n
                    Your canellation for the $title event has been noted.\n
                    You may still re-register for the event should you wish but we regret to inform you that subsequent registration attempts will be subject to availability";
    sendEmail($fullName, $email, $mailMessage, $mailSubject);
}
function getUserDetails($user_id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE member_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $fname = $row["fname"];
        $lname = $row["lname"];
        $email = $row['email'];
    }
    $stmt->close();
    return [$fname, $lname, $email];
}

function getEventDetails($event_id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $title = $row["title"];
        $date = $row["date"];
        $time = $row['time'];
        $venue = $row['venue'];
    }
    $stmt->close();
    return [$title, $date, $time, $venue];
}

?>
