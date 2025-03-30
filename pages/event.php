

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="JS/main.js"></script> -->
</head>

<body>
    <?php include 'inc/navbar.inc.php'; ?>
    <h2>Event Registration</h2>
    
    <?php
    require 'backend/event_info_retrieve.php';
    require 'backend/event_register.php';

    if (!isset($_SESSION['id'])) {
        echo "<p> Please <a href='/login'>log in</a>to register for events.</p>";
        exit();
    }
    else{
        $user_id = $_SESSION['id'];

        // // Handle form actions
        handleEventAction($user_id);
    
        // Get event data
        // $events = fetchAllEvents();
        $events = fetchUnregisteredEvents($user_id);
        $registered_events = fetchUserRegistrations($user_id);
    }

    ?>
    <!-- Registered Events -->
    <h3>My Registered Events</h3>
    <ul>
        <?php if (empty($registered_events)): ?>
            <li>You have not registered for any events.</li>
        <?php else: ?>
            <?php foreach ($registered_events as $event): ?>
                <li>
                    <?= htmlspecialchars($event->event_name) ?>
                    <form method="POST" action="/event" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event->event_id ?>">
                        <input type="hidden" name="action" value="cancel">
                        <button type="submit">Cancel</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- Register for New Events -->
    <h3>Register for an Event</h3>
    <form method="POST" action="/event">
        <label for="event">Select an Event:</label>
        <select name="event_id" id="event">
            <?php foreach ($events as $event): ?>
                <option value="<?= $event->event_id ?>"><?= $event->event_name ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="action" value="register">
        <button type="submit">Register</button>
    </form>
</body>

</html>