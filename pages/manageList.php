<?php
require_once 'backend/db.php';
global $result, $stmt, $conn;
$stmt = $conn->prepare("SELECT * FROM pets");

if (!$stmt->execute()){
    $errorMsg = "Execute failed: (" . $stmt->errno . ") ";
    $stmt->error;
}
$result = $stmt->get_result();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
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
        <nav>
            <!-- Include sidebar -->
            <?php include "inc/sidebar.inc.php"; ?>
        </nav>
        
        <!-- Main Content -->
        <main class="main-content flex-grow-1 p-4">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><strong>Edit Listings</strong></h1>
                    <a href="/addList" class='btn btn-success'>Add New Listing</a>
                </div>
                
                <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Pet ID</th>
                                <th>Pet Name</th>
                                <th>Pet Type</th>
                                <th>Breed</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Adoption Cost</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["pet_ID"]) ?></td>
                                <td><?= htmlspecialchars($row["pet_name"]) ?></td>
                                <td><?= htmlspecialchars($row["pet_type"]) ?></td>
                                <td><?= htmlspecialchars($row["breed"]) ?></td>
                                <td><?= htmlspecialchars($row["age"]) ?></td>
                                <td><?= htmlspecialchars($row["gender"]) ?></td>
                                <td><?= htmlspecialchars($row["adopt_cost"]) ?></td>
                                <td>
                                    <a href="/editList?id=<?= $row["pet_ID"] ?>" aria-label='edit listing'>
                                    <i class="bi bi-pencil-fill text-primary" style="font-size: 1rem;"></i>
                                    </a>
                                    <form method="post" action="backend/delete_listing.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['pet_ID']; ?>">
                                        <button type="submit" class="border-0 bg-transparent p-0" onclick="return confirm('Are you sure?')" aria-label='Delete Listing'>
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
                    <div class="alert alert-info">No listings found.</div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>