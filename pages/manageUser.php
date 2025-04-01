<?php
require_once 'backend/db.php';
global $result, $stmt, $conn;
$stmt = $conn->prepare("SELECT * FROM world_of_pets_members");

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
    <title>User Management</title>
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
                    <h1><strong>User Management</strong></h1>
                    <a href="/addUser" class='btn btn-success'>Add New User</a>
                </div>
                
                <?php if (isset($errorMsg)): ?>
                    <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Date of Birth</th>
                                <th>Admin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['member_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['fname']); ?></td>
                                <td><?php echo htmlspecialchars($row['lname']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['dateofbirth']); ?></td>
                                <td><?php echo $row['admin'] ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="/editUser?id=<?php echo $row['member_id']; ?>" aria-label='Edit User' class="text-decoration-none">
                                            <i class="bi bi-pencil-fill text-primary" style="font-size: 1rem;"></i>
                                        </a>
                                        <form method="post" action="backend/delete_user.php" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $row['member_id']; ?>">
                                            <button type="submit" class="border-0 bg-transparent p-0" onclick="return confirm('Are you sure?')" aria-label="Delete user">
                                                <i class="bi bi-trash-fill text-danger" style="font-size: 1rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>