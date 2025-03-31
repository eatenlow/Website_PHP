<?php

function fetchAllEvents()
{
    global $conn;
    require_once 'backend/db.php';
    $events = [];
    $stmt = $conn->prepare("SELECT * FROM events ORDER BY id DESC");
    $stmt->execute();
    // Bind the result columns to variables
    $stmt->bind_result($event_id, $event_name, $event_date, $event_time, $event_venue, $event_details, $event_link);

    // Create an array to store the results
    $events = [];

    // Fetch each row as an object and store it in the array
    while ($stmt->fetch()) {
        // Create an object for each row and store it in the array
        $resultObject = new stdClass();
        $resultObject->event_id = $event_id;
        $resultObject->event_name = $event_name;
        $resultObject->event_date = $event_date;
        $resultObject->event_time = $event_time;
        $resultObject->event_venue = $event_venue;
        $resultObject->event_details = $event_details;
        $events[] = $resultObject;
    }
    $stmt->close();
    $conn->close();
    return $events;
}


function fetchUnregisteredEvents($user_id)
{
    global $conn;
    require_once 'backend/db.php';

    $stmt = $conn->prepare(
        "SELECT e.* FROM events e
        LEFT JOIN event_registration ue ON e.id = ue.event_id AND ue.user_id = ?
        WHERE ue.user_id IS NULL;");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    // Bind the result columns to variables
    $stmt->bind_result($event_id, $event_name, $event_date, $event_time, $event_venue, $event_details, $event_link);

    // Create an array to store the results
    $events = [];

    // Fetch each row as an object and store it in the array
    while ($stmt->fetch()) {
        // Create an object for each row and store it in the array
        $resultObject = new stdClass();
        $resultObject->event_id = $event_id;
        $resultObject->event_name = $event_name;
        $resultObject->event_date = $event_date;
        $resultObject->event_time = $event_time;
        $resultObject->event_venue = $event_venue;
        $resultObject->event_details = $event_details;
        $events[] = $resultObject;
    }
    $stmt->close();
    // $conn->close();
    return $events;
}

function fetchUserRegistrations($user_id)
{
    global $conn;
    require_once 'backend/db.php';
    $registered_events = [];
    $stmt = $conn->prepare("SELECT e.id, e.title FROM events e
                            INNER JOIN event_registration er ON e.id = er.event_id
                            WHERE er.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    // Bind the result columns to variables
    $stmt->bind_result($event_id, $event_name);
    // Fetch each row as an object and store it in the array
    while ($stmt->fetch()) {
        // Create an object for each row and store it in the array
        $resultObject = new stdClass();
        $resultObject->event_id = $event_id;
        $resultObject->event_name = $event_name;
        $registered_events[] = $resultObject;
    }
    $stmt->close();
    $conn->close();
    return $registered_events;
}
