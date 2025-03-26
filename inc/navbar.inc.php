<!-- navbar.php -->
<?php
session_start();

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/home">
            <?php echo '<img src=' . dirname(__DIR__) . '/images/logo3.png alt="PetAdopt Logo" height="50">' ?>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left-aligned navigation links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/listings">Listings</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About Us</a></li>
                <?php
                if (isset($_SESSION["admin"]) && isset($_SESSION["login"])) {
                    echo "
                        <li class=nav-item>
                            <a class=nav-link href=/editSite>
                                Edit Site
                            </a>
                        </li>
                        <li class=nav-item>
                            <a class=nav-link href=/manageList>
                                Edit Listings
                            </a>
                        </li>
                        <li class=nav-item>
                            <a class=nav-link href=/manageUser>
                                Edit Users
                            </a>
                        </li>";
                }
                ?>
            </ul>

            <!-- Right-aligned icons -->
            <ul class="navbar-nav">
                <?php
                if (!isset($_SESSION["login"])) {
                    echo "<li class=nav-item>
                        <a class='nav-link' href=/login>
                            <i class='bi bi-door-open fs-3'></i> <!-- Login Icon -->
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a class=nav-link href=/register>
                            <i class='bi bi-person-plus fs-3'></i>
                        </a>
                    </li>";
                } else {
                    echo "
                    <li class=nav-item>
                        <a class=nav-link href=/profile>
                            <i class='bi bi-person-circle fs-3'></i>
                        </a>
                    </li>
                    <li class=nav-item>
                        <a class=nav-link href=/logout>
                            <i class='bi bi-box-arrow-in-right fs-3'></i>
                        </a>
                    </li>";
                }
                ?>
                <!-- <li class="nav-item">
                        <a class="nav-link" href="/checkout">
                            <i class="bi bi-cart fs-3"></i>  Checkout Icon 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">
                            <i class="bi bi-box-arrow-in-right fs-3"></i>  Login Icon 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">
                            <i class="bi bi-person-plus fs-3"></i>  Sign Up Icon 
                        </a>
                    </li> -->
            </ul>
        </div>
    </div>
</nav>