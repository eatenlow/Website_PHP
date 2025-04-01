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
        
        // Query for age distribution
        $age_sql = "SELECT 
                      CASE
                        WHEN TIMESTAMPDIFF(YEAR, dateofbirth, CURDATE()) BETWEEN 0 AND 17 THEN '0-17'
                        WHEN TIMESTAMPDIFF(YEAR, dateofbirth, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                        WHEN TIMESTAMPDIFF(YEAR, dateofbirth, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
                        WHEN TIMESTAMPDIFF(YEAR, dateofbirth, CURDATE()) BETWEEN 36 AND 50 THEN '36-50'
                        ELSE '50+'
                      END as age_group,
                      COUNT(*) as count
                    FROM world_of_pets_members
                    WHERE dateofbirth IS NOT NULL
                    GROUP BY age_group
                    ORDER BY age_group";
        $age_result = mysqli_query($conn, $age_sql);
        
        $age_labels = [];
        $age_data = [];
        $age_colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
        
        while ($row = mysqli_fetch_assoc($age_result)) {
            $age_labels[] = $row['age_group'];
            $age_data[] = $row['count'];
        }

        // Query for pet type distribution
        $pet_sql = "SELECT 
        pet_type, 
        COUNT(*) as count 
        FROM pets 
        GROUP BY pet_type 
        ORDER BY count DESC";
        $pet_result = mysqli_query($conn, $pet_sql);

        $pet_labels = [];
        $pet_data = [];
        $pet_colors = ['#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#4e73df'];

        while ($row = mysqli_fetch_assoc($pet_result)) {
        $pet_labels[] = $row['pet_type'];
        $pet_data[] = $row['count'];
        }

        
    ?>    

    <title>Admin Dashboard</title>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <button id="refreshDashboard" class="btn btn-sm btn-outline-secondary me-2"
                        aria-label="Refresh dashboard data">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                    <small class="text-muted">Last updated: <span id="lastUpdated"><?php echo date('F j, Y, g:i a'); ?></span></small>
                </div>
            </div>

                <div class="row mb-4">
                    <!-- Total Users Card with Age Pie Chart -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="stat-card bg-primary bg-gradient text-white h-100">
                            <div class="card-body">
                                <i class="bi bi-people-fill card-icon"></i>
                                <h2 class="card-title">Total Users</h2>
                                <h2 class="card-value"><?php echo $total_users; ?></h2>
                                
                                <!-- Age Distribution Pie Chart -->
                                <div class="age-chart-container">
                                    <canvas id="ageDistributionChart"></canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span>Manage Users</span>
                                    <a href="/manageUser" class="text-white" aria-label="Manage users">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>                    

                    <!-- Total Listings Card with Pet Distribution -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="stat-card bg-success bg-gradient text-white h-100">
                            <div class="card-body">
                                <i class="bi bi-heart-fill card-icon"></i>
                                <h2 class="card-title">Total Listings</h2>
                                <h2 class="card-value"><?php echo $total_listings; ?></h2>
                                
                                <!-- Pet Distribution Chart -->
                                <div class="age-chart-container">
                                    <canvas id="petDistributionChart"></canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span>Manage Listings</span>
                                    <a href="/manageList" class="text-white" aria-label="Manage pet listings">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>              

                    <!-- Total Events Card -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="stat-card bg-info bg-gradient text-white h-100">
                            <div class="card-body">
                                <i class="bi bi-calendar-event-fill card-icon"></i>
                                <h2 class="card-title">Total Events</h2>
                                <h2 class="card-value"><?php echo $total_events; ?></h2>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span>Manage Events</span>
                                <a href="/manageEvents" class="text-white" aria-label="Manage events">
                                <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include "inc/footer.inc.php"; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Age Distribution Pie Chart with proper tooltips
    const ageCtx = document.getElementById('ageDistributionChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($age_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($age_data); ?>,
                backgroundColor: [
                    'rgba(255,255,255,0.7)',
                    'rgba(255,255,255,0.5)',
                    'rgba(255,255,255,0.3)',
                    'rgba(255,255,255,0.2)',
                    'rgba(255,255,255,0.1)'
                ],
                borderColor: 'rgba(255,255,255,0.2)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        color: 'white',
                        font: {
                            weight: '500'
                        },
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    enabled: true,
                    usePointStyle: true,
                    callbacks: {
                        title: function(context) {
                            return context[0].label; // Just return the label text
                        },
                        label: function(context) {
                            const total = <?php echo $total_users; ?>;
                            const percentage = Math.round((context.raw / total) * 100);
                            return `${context.raw} users (${percentage}%)`; // Plain text format
                        }
                    }
                }
            },
            cutout: '0%'
        }
    });
});

    // Pet Distribution Chart
    const petCtx = document.getElementById('petDistributionChart').getContext('2d');
    new Chart(petCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($pet_labels); ?>,
            datasets: [{
                label: 'Listings',
                data: <?php echo json_encode($pet_data); ?>,
                backgroundColor: 'rgba(255,255,255,0.7)',
                borderColor: 'rgba(255,255,255,0.2)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.raw} listings (${Math.round((context.raw/<?php echo $total_listings; ?>)*100)}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    },
                    ticks: {
                        color: 'rgba(255,255,255,0.7)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: 'rgba(255,255,255,0.7)'
                    }
                }
            }
        }
    });
</script>
</body>
</html>