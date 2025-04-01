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
            // echo "<p> Please <a href='/login'>log in</a>to register for events.</p>";
            // Approach #1: Prominent Notice Box
            echo '<div class="alert alert-info text-center p-3 mb-4">';
            echo '<i class="bi bi-info-circle-fill me-2"></i>';
            echo '<strong>You need to be logged in to register for events.</strong>';
            echo '<a href="/login" class="btn btn-primary ms-3">Log In Now</a>';
            echo '</div>';

            $events = fetchAllEvents();
            // exit();
        } else {
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
            <section class="my-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h4 mb-0"><i class="bi bi-calendar-check me-2"></i>My Registered Events</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($registered_events)): ?>
                            <p class="mb-0 text-muted"><i class="bi bi-info-circle me-2"></i>You have not registered for any events.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Event</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($registered_events as $event): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <span class="fw-medium"><?= htmlspecialchars($event->event_name) ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <form method="POST" action="/event" style="display:inline;">
                                                        <input type="hidden" name="event_id" value="<?= $event->event_id ?>">
                                                        <input type="hidden" name="action" value="cancel">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Register for New Events -->
        <h3>Register for an Event</h3>
        <?php if ($events === []): ?>
            <p>There are no other events available...</p>
        <?php else: ?>
            <p>Select an Event:</p>
            <div class="row justify-content-left">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 mb-4">
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
                                    <?php if (isset($_SESSION['id'])): ?>
                                        <form method="POST" action=/event>
                                            <input type="hidden" name="event_id" id=event value="<?= $event->event_id ?>">
                                            <input type="hidden" name="action" value="register">
                                            <button type=submit class="btn btn-outline-light">Register</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="/login" class="btn btn-outline-light">
                                            <i class="bi bi-lock-fill me-1"></i> Log in to Register
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>