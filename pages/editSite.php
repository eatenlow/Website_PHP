<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | PetAdopt</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <style>
        /* Admin-specific styles */
        .sidebar {
            width: 200px; /* Reduced from 250px */
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: all 0.3s;
            border-right: 1px solid #dee2e6;
        }
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar.collapsed .nav-link-text {
            display: none;
        }
        .sidebar.collapsed .nav-link {
            justify-content: center;
        }
        .sidebar.collapsed .dropdown-toggle::after {
            display: none;
        }
        .sidebar.collapsed .dropdown-menu {
            position: absolute;
            left: 60px;
            top: 0;
        }
        .main-content {
            margin-left: 200px; /* Reduced from 250px to match sidebar width */
            transition: all 0.3s;
        }
        .main-content.expanded {
            margin-left: 60px;
        }
        .nav-link {
            padding: 8px 10px; /* Reduced padding */
            border-radius: 5px;
            margin: 2px 5px;
            font-size: 0.9rem; /* Slightly smaller font */
        }
        .nav-link:hover {
            background-color: #e9ecef;
        }
        .nav-link.active {
            background-color: #0d6efd;
            color: white !important;
        }
        .dropdown-menu {
            border: none;
            box-shadow: none;
            background-color: #f1f1f1;
        }
        .dropdown-item {
            padding: 6px 10px 6px 25px; /* Reduced padding */
            font-size: 0.85rem; /* Slightly smaller font */
        }
        .dropdown-item:hover {
            background-color: #e9ecef;
        }
        .toggle-btn {
            border: none;
            background: none;
            font-size: 1.1rem; /* Slightly smaller */
            padding: 8px;
        }
        .nav-item .bi {
            font-size: 1rem; /* Slightly smaller icons */
        }
    </style>
</head>
<body>
    <?php include 'inc/navbar.inc.php'; ?>
    
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="p-2"> <!-- Reduced padding -->
                <button class="toggle-btn" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link active" href="/admin">
                        <i class="bi bi-speedometer2 me-2"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/manageUser">
                        <i class="bi bi-people me-2"></i>
                        <span class="nav-link-text">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/manageList">
                        <i class="bi bi-paw me-2"></i>
                        <span class="nav-link-text">Pets</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#applicationsCollapse">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        <span class="nav-link-text">Applications</span>
                    </a>
                    <div class="collapse" id="applicationsCollapse">
                        <ul class="nav flex-column ps-3"> <!-- Reduced padding -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-hourglass-split me-2"></i>
                                    <span class="nav-link-text">Pending</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span class="nav-link-text">Approved</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-x-circle me-2"></i>
                                    <span class="nav-link-text">Rejected</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#analyticsCollapse">
                        <i class="bi bi-graph-up me-2"></i>
                        <span class="nav-link-text">Analytics</span>
                    </a>
                    <div class="collapse" id="analyticsCollapse">
                        <ul class="nav flex-column ps-3"> <!-- Reduced padding -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-file-earmark-bar-graph me-2"></i>
                                    <span class="nav-link-text">Reports</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-bar-chart-line me-2"></i>
                                    <span class="nav-link-text">Charts</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#settingsCollapse">
                        <i class="bi bi-gear me-2"></i>
                        <span class="nav-link-text">Settings</span>
                    </a>
                    <div class="collapse" id="settingsCollapse">
                        <ul class="nav flex-column ps-3"> <!-- Reduced padding -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-wrench me-2"></i>
                                    <span class="nav-link-text">Site Config</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-envelope me-2"></i>
                                    <span class="nav-link-text">Email Templates</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4" id="mainContent">
            <h1>Admin Dashboard</h1>
            
            <div class="row mt-4">
                <!-- Recent Activity -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Recent Activity</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">User "JohnDoe" updated profile</li>
                                <li class="list-group-item">New pet "Buddy" added</li>
                                <li class="list-group-item">5 new applications received</li>
                                <li class="list-group-item">System backup completed</li>
                                <li class="list-group-item">Settings updated</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Urgent Tasks -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Urgent Tasks</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                10 pending applications need review
                            </div>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                5 pets need medical attention
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar collapse
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Store preference in localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
        
        // Check for saved preference
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('expanded');
            }
        });
    </script>

    <?php include 'inc/footer.inc.php'; ?>
</body>
</html>