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
        
        // Database connection
        require_once 'backend/db.php';
        
        // Query to count total users
        $user_sql = "SELECT COUNT(*) as total_users FROM world_of_pets.world_of_pets_members";
        $user_result = mysqli_query($conn, $user_sql);
        $user_row = mysqli_fetch_assoc($user_result);
        $total_users = $user_row['total_users'];
        
        // Query to count total listings
        $listing_sql = "SELECT COUNT(*) as total_listings FROM world_of_pets.pets";
        $listing_result = mysqli_query($conn, $listing_sql);
        $listing_row = mysqli_fetch_assoc($listing_result);
        $total_listings = $listing_row['total_listings'];
        
        // Query to count total events
        $event_sql = "SELECT COUNT(*) as total_events FROM world_of_pets.events";
        $event_result = mysqli_query($conn, $event_sql);
        $event_row = mysqli_fetch_assoc($event_result);
        $total_events = $event_row['total_events'];
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
                <div class="dashboard-header d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-0">Dashboard Overview</h1>
                        <p class="text-muted mb-0">Welcome back, Admin</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Last updated: <?php echo date('F j, Y, g:i a'); ?></small>
                    </div>
                </div>

                <div class="row mb-4">
                    <!-- Total Users Card -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="stat-card bg-primary bg-gradient text-white h-100">
                            <div class="card-body">
                                <i class="bi bi-people-fill card-icon"></i>
                                <h5 class="card-title">Total Users</h5>
                                <h2 class="card-value"><?php echo $total_users; ?></h2>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span>Manage Users</span>
                                <a href="/manageUser" class="text-white"><i class="bi bi-arrow-right-circle"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Listings Card -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="stat-card bg-success bg-gradient text-white h-100">
                            <div class="card-body">
                                <i class="bi bi-heart-fill card-icon"></i>
                                <h5 class="card-title">Total Listings</h5>
                                <h2 class="card-value"><?php echo $total_listings; ?></h2>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span>Manage Listings</span>
                                <a href="/manageList" class="text-white"><i class="bi bi-arrow-right-circle"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Events Card -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="stat-card bg-info bg-gradient text-white h-100">
                            <div class="card-body">
                                <i class="bi bi-calendar-event-fill card-icon"></i>
                                <h5 class="card-title">Total Events</h5>
                                <h2 class="card-value"><?php echo $total_events; ?></h2>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span>Manage Events</span>
                                <a href="/manageEvents" class="text-white"><i class="bi bi-arrow-right-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include "inc/footer.inc.php"; ?>
</body>
</html>