<!-- adminSidebar.inc.php -->
<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 250px; min-height: 100vh; transition: all 0.3s;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/adminDashboard" class="d-flex align-items-center text-decoration-none">
            <i class="bi bi-speedometer2 fs-4 me-2"></i>
            <span class="fs-4 nav-link-text">Admin Panel</span>
        </a>
        <button class="btn btn-link toggle-btn p-0" type="button" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="bi bi-chevron-double-left fs-5"></i>
        </button>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/adminDashboard" class="nav-link link-dark rounded-3">
                <i class="bi bi-speedometer2 me-2"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#usersSubmenu" class="nav-link link-dark rounded-3" data-bs-toggle="collapse">
                <i class="bi bi-people-fill me-2"></i>
                <span class="nav-link-text">Users</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse show" id="usersSubmenu">
                <ul class="nav flex-column ms-3">
                    <li>
                        <a href="/manageUser" class="nav-link link-dark rounded-3">
                            <i class="bi bi-list-ul me-2"></i>
                            <span class="nav-link-text">Manage Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="/addUser" class="nav-link link-dark rounded-3">
                            <i class="bi bi-person-plus me-2"></i>
                            <span class="nav-link-text">Add User</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a href="#petsSubmenu" class="nav-link link-dark rounded-3" data-bs-toggle="collapse">
                <i class="bi bi-heart-fill me-2"></i>
                <span class="nav-link-text">Pets</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse show" id="petsSubmenu">
                <ul class="nav flex-column ms-3">
                    <li>
                        <a href="/manageList" class="nav-link link-dark rounded-3">
                            <i class="bi bi-list-ul me-2"></i>
                            <span class="nav-link-text">Manage Listings</span>
                        </a>
                    </li>
                    <li>
                        <a href="/addList" class="nav-link link-dark rounded-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span class="nav-link-text">Add Listing</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a href="#eventsSubmenu" class="nav-link link-dark rounded-3" data-bs-toggle="collapse">
                <i class="bi bi-heart-fill me-2"></i>
                <span class="nav-link-text">Events</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse show" id="petsSubmenu">
                <ul class="nav flex-column ms-3">
                    <li>
                        <a href="/manageEvents" class="nav-link link-dark rounded-3">
                            <i class="bi bi-list-ul me-2"></i>
                            <span class="nav-link-text">Manage Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="/addEvent" class="nav-link link-dark rounded-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span class="nav-link-text">Add Event</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle me-2"></i>
            <span class="nav-link-text"><?php echo htmlspecialchars($_SESSION['fname'] ?? 'Admin'); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="/profile">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/logout">Sign out</a></li>
        </ul>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
    
    // Update toggle icon
    const toggleIcon = document.querySelector('.toggle-btn i');
    if (sidebar.classList.contains('collapsed')) {
        toggleIcon.classList.remove('bi-chevron-double-left');
        toggleIcon.classList.add('bi-chevron-double-right');
    } else {
        toggleIcon.classList.remove('bi-chevron-double-right');
        toggleIcon.classList.add('bi-chevron-double-left');
    }
    
    // Save state in localStorage
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

// Check saved state on page load
document.addEventListener('DOMContentLoaded', function() {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        document.querySelector('.sidebar').classList.add('collapsed');
        document.querySelector('.main-content').classList.add('expanded');
        document.querySelector('.toggle-btn i').classList.remove('bi-chevron-double-left');
        document.querySelector('.toggle-btn i').classList.add('bi-chevron-double-right');
    }
});
</script>