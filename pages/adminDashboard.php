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
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include "inc/sidebar.inc.php"; ?>
        
        <!-- Main Content -->
        <main class="main-content flex-grow-1 p-4">
            <div class="container-fluid">
                <h1 class="mb-4">Dashboard Overview</h1>
                
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text fs-3"><?php echo getTotalUsers(); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <h5 class="card-title">Active Listings</h5>
                                <p class="card-text fs-3"><?php echo getActiveListings(); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card bg-warning text-dark h-100">
                            <div class="card-body">
                                <h5 class="card-title">Pending Applications</h5>
                                <p class="card-text fs-3"><?php echo getPendingApplications(); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach(getRecentActivity() as $activity): ?>
                            <li class="list-group-item"><?php echo htmlspecialchars($activity); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php include "inc/footer.inc.php"; ?>
</body>
</html>