

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
    <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
    <h2><strong>Event Registration</strong></h2>
    <?php
    require 'backend/event_info_retrieve.php';

    if (!isset($_SESSION['id'])) {
        echo "<p> Please <a href='/login'>log in</a>to register for events.</p>";
        $events = fetchAllEvents();
        // exit();
    }
    else{
        require 'backend/event_register.php';
        $user_id = $_SESSION['id'];

        // // Handle form actions
        handleEventAction($user_id);

        // Get event data
        $events = fetchUnregisteredEvents($user_id);
        $registered_events = fetchUserRegistrations($user_id);
    }

    ?>
    <!-- Registered Events -->
    &nbsp;
    <?php if (isset($_SESSION['id'])): ?>
    <h3>My Registered Events</h3>
    <table style="width:70%;">
        <?php if (empty($registered_events)): ?>
            <p>You have not registered for any events.</p>
        <?php else: ?>
            <?php foreach ($registered_events as $event): ?>
                <tr>
                    <td class="col-1"><?= htmlspecialchars($event->event_name) ?></td>
                    <form method="POST" action="/event" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event->event_id ?>">
                        <input type="hidden" name="action" value="cancel">
                    <td class="col-1"><button type="submit" class="btn btn-danger">Cancel</button></td>
                    </form>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <?php endif; ?>

    <!-- Register for New Events -->
    <h3>Register for an Event</h3>
    <?php if($events === []): ?>
        <p>There are no other events available...</p>
    <?php else: ?>
    <!-- <form method="POST" action="/event"> -->
        <p>Select an Event:</p>
        <!-- <select name="event_id" id="event">
            <?php //foreach ($events as $event): ?>
                <option value="<?= $event->event_id ?>"><?= $event->event_name ?></option>
            <?php //endforeach; ?>
        </select> -->
        <div class="row justify-content-left">
        <?php foreach($events as $event): ?>
            <div class="col-md-4">
                <div class="event-card">
                    <div class="card-inner">
                        <div class="card-front">
                            <h3><?= $event->event_name ?></h3>
                            <p><?= $event->event_date ?></p>
                            <p><?= $event->event_time ?></p>
                            <p><?= $event->event_venue ?></p>
                        </div>
                        <div class="card-back">
                            <p><?= $event->event_details ?></p>    
                            <form method="POST" action=/event>
                                <input type="hidden" name="event_id" id=event value="<?= $event->event_id?>">
                                <input type="hidden" name="action" value="register">
                                <button type=submit class="btn btn-outline-light">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        <!-- <input type="hidden" name="action" value="register">
        <button type="submit" class="btn btn-success">Register</button> -->
    <!-- </form> -->
    <?php endif; ?>
    </main>
</body>

</html>