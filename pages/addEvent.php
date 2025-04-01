<html lang="en">
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container-lg w-60 w-md-80 w-sm-90 w-100 mx-auto">
        <h1><strong>Add Event</strong></h1>
        <a href="/manageEvents" class='btn btn-secondary mb-3'>Back to Manage Events</a>
        <?php
            if(!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')){
                header("Location: /a");
            }
        ?>
            <form action="/backend/new_event.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <form-label for="title">Title:</form-label>
                    <input type="text" id=title name="title" aria-label='event title' value="<?= htmlspecialchars($title) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="date">Date:</form-label>
                    <input type="date" id=date name="date" aria-label='event date' value="<?= htmlspecialchars($date) ?>" class="form-control" maxlength="45" required>
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
                    <input type="text" id=venue name="venue" aria-label='venue' value="<?= htmlspecialchars($venue) ?>" class="form-control" maxlength="45" required>
                </div>

                <div class="mb-3">
                    <form-label for="details">Details:</form-label>
                    <input type="text" id=details name="details" aria-label='details' value="<?= htmlspecialchars($details) ?>" class="form-control" maxlength="255" required>
 
                </div>

                <button class="btn btn-primary" type="submit" aria-label='create new event' name='submit'>Add new Event</button>
            </form>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>
