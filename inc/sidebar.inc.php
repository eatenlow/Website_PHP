<?php
// /inc/sidebar.inc.php
?>
<div class="sidebar" id="sidebar">
    <div class="p-3">
        <button class="toggle-btn" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item">
            <a class="nav-link active" href="/editSite">
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
                <ul class="nav flex-column ps-4">
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
                <ul class="nav flex-column ps-4">
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
                <ul class="nav flex-column ps-4">
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
<script src="/js/main.js"></script>
