<!-- navbar.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo3.png" alt="PetAdopt Logo" height="50">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left-aligned navigation links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="listings.php">Listings</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
            </ul>

            <!-- Right-aligned icons -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="checkout.php">
                        <i class="bi bi-cart fs-3"></i> <!-- Checkout Icon -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <i class="bi bi-box-arrow-in-right fs-3"></i> <!-- Login Icon -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">
                        <i class="bi bi-person-plus fs-3"></i> <!-- Sign Up Icon -->
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>