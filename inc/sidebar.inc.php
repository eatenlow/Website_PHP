<!-- sidebar.inc.php -->
    <aside class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-light" 
       style="width: 250px; min-height: 100vh; transition: all 0.3s;"
       aria-label="Admin navigation">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/adminDashboard" class="d-flex align-items-center text-decoration-none" aria-current="page">
            <i class="bi bi-speedometer2 fs-4 me-2" aria-hidden="true"></i>
            <span class="fs-4 nav-link-text text-dark">Admin Panel</span> <!-- Fixed contrast -->
        </a>
        <button class="btn btn-link toggle-btn p-0" type="button" onclick="toggleSidebar()" 
                aria-label="Toggle sidebar" aria-expanded="true">
            <i class="bi bi-chevron-double-left fs-5" aria-hidden="true"></i>
        </button>
    </div>

    <hr aria-hidden="true">
    
    <!-- Main Navigation -->
    <nav aria-label="Main menu">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="/adminDashboard" class="nav-link link-dark rounded-3" aria-current="page">
                    <i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            
            <!-- Users Section -->
            <li class="nav-item">
                <a href="#usersSubmenu" class="nav-link link-dark rounded-3" 
                   data-bs-toggle="collapse" 
                   aria-expanded="true" 
                   aria-controls="usersSubmenu">
                    <i class="bi bi-people-fill me-2" aria-hidden="true"></i>
                    <span class="nav-link-text">Users</span>
                    <i class="bi bi-chevron-down ms-auto" aria-hidden="true"></i>
                </a>
                <div class="collapse show" id="usersSubmenu">
                    <ul class="nav flex-column ms-3" aria-label="Users submenu">
                        <li class="nav-item">
                            <a href="/manageUser" class="nav-link link-dark rounded-3">
                                <i class="bi bi-list-ul me-2" aria-hidden="true"></i>
                                <span class="nav-link-text">Manage Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/addUser" class="nav-link link-dark rounded-3">
                                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                                <span class="nav-link-text">Add User</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Pets Section -->
            <li class="nav-item">
                <a href="#petsSubmenu" class="nav-link link-dark rounded-3" 
                   data-bs-toggle="collapse" 
                   aria-expanded="true" 
                   aria-controls="petsSubmenu">
                    <i class="bi bi-heart-fill me-2" aria-hidden="true"></i>
                    <span class="nav-link-text">Pets</span>
                    <i class="bi bi-chevron-down ms-auto" aria-hidden="true"></i>
                </a>
                <div class="collapse show" id="petsSubmenu">
                    <ul class="nav flex-column ms-3" aria-label="Pets submenu">
                        <li class="nav-item">
                            <a href="/manageList" class="nav-link link-dark rounded-3">
                                <i class="bi bi-list-ul me-2" aria-hidden="true"></i>
                                <span class="nav-link-text">Manage Listings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/addList" class="nav-link link-dark rounded-3">
                                <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
                                <span class="nav-link-text">Add Listing</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Events Section -->
            <li class="nav-item">
                <a href="#eventsSubmenu" class="nav-link link-dark rounded-3" 
                   data-bs-toggle="collapse" 
                   aria-expanded="true" 
                   aria-controls="eventsSubmenu">
                    <i class="bi bi-calendar-event me-2" aria-hidden="true"></i>
                    <span class="nav-link-text">Events</span>
                    <i class="bi bi-chevron-down ms-auto" aria-hidden="true"></i>
                </a>
                <div class="collapse show" id="eventsSubmenu">
                    <ul class="nav flex-column ms-3" aria-label="Events submenu">
                        <li class="nav-item">
                            <a href="/manageEvents" class="nav-link link-dark rounded-3">
                                <i class="bi bi-list-ul me-2" aria-hidden="true"></i>
                                <span class="nav-link-text">Manage Events</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/addEvent" class="nav-link link-dark rounded-3">
                                <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
                                <span class="nav-link-text">Add Event</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
    
    <hr aria-hidden="true">
    
    <!-- User Dropdown -->
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" 
           id="dropdownUser1" 
           data-bs-toggle="dropdown" 
           aria-expanded="false">
            <i class="bi bi-person-circle me-2" aria-hidden="true"></i>
            <span class="nav-link-text"><?php echo htmlspecialchars($_SESSION['fname'] ?? 'Admin'); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="/profile">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/logout">Sign out</a></li>
        </ul>
    </div>
</aside>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleButton = document.querySelector('.toggle-btn');
    const toggleIcon = toggleButton.querySelector('i');
    
    const isCollapsing = !sidebar.classList.contains('collapsed');
    
    // Update classes
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
    
    // Update ARIA attributes
    toggleButton.setAttribute('aria-expanded', isCollapsing ? 'false' : 'true');
    
    // Update icon
    if (isCollapsing) {
        toggleIcon.classList.replace('bi-chevron-double-left', 'bi-chevron-double-right');
    } else {
        toggleIcon.classList.replace('bi-chevron-double-right', 'bi-chevron-double-left');
    }
    
    // Save state
    localStorage.setItem('sidebarCollapsed', isCollapsing);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        document.querySelector('.sidebar').classList.add('collapsed');
        document.querySelector('.main-content').classList.add('expanded');
        const icon = document.querySelector('.toggle-btn i');
        icon.classList.replace('bi-chevron-double-left', 'bi-chevron-double-right');
        document.querySelector('.toggle-btn').setAttribute('aria-expanded', 'false');
    }
});
</script>