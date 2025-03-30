<?php
require_once 'db.php';

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
                    echo "Successfully registered for the event!";
                } else {
                    echo "Error registering for event.";
                }
                $stmt->close();
            }
            else{
                echo "Already registered for the event..";
            }
            // $conn->close();
        }
        elseif ($_POST['action'] === "cancel") {
            $stmt = $conn->prepare("DELETE FROM event_registration WHERE user_id = ? AND event_id = ?");
            $stmt->bind_param("ii", $user_id, $event_id);
            if ($stmt->execute()) {
                echo "Successfully canceled your registration.";
            } else {
                echo "Error canceling registration.";
            }
            $stmt->close();
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
?>
