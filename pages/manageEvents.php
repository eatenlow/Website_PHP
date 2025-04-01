<?php
require_once 'backend/db.php';
global $result, $stmt, $conn;
$stmt = $conn->prepare("SELECT * FROM events");

if (!$stmt->execute()){
    $errorMsg = "Execute failed: (" . $stmt->errno . ") ";
    $stmt->error;
}
$result = $stmt->get_result();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <?php 
        include 'inc/head.inc.php';
        include 'inc/navbar.inc.php'; 
        if(!isset($_SESSION["login"]) || ($_SESSION["admin"] != '1')){
            header("Location: /a");
            exit();
        }
    ?>
    <title>Manage Listings</title>
</head>
<body>
    <div class="d-flex">
        <!-- Include sidebar -->
        <?php include "inc/sidebar.inc.php"; ?>
        
        <!-- Main Content -->
        <main class="main-content flex-grow-1 p-4">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><strong>Edit Events</strong></h1>
                    <a href="/addEvent" class='btn btn-success'>Add New Event</a>
                </div>
                
                <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Event ID</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["id"]) ?></td>
                                <td><?= htmlspecialchars($row["title"]) ?></td>
                                <td><?= htmlspecialchars($row["date"]) ?></td>
                                <td><?= htmlspecialchars($row["time"]) ?></td>
                                <td><?= htmlspecialchars($row["venue"]) ?></td>
                                <td><?= htmlspecialchars($row["details"]) ?></td>
                                <td>
                                    <a href="/editEvent?id=<?= $row["id"] ?>">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <form method="post" action="backend/delete_event.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['pet_ID']; ?>">
                                        <button type="submit" class="border-0 bg-transparent p-0" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash-fill text-danger" style="font-size: 1rem;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="alert alert-info">No Events found.</div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>