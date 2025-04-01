<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Error reporting -->
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>

    <script src="JS/main.js"></script>

    <?php
    include 'inc/head.inc.php';
    require_once 'backend/db.php'; // Secure database connection
    require_once 'backend/carousel.php'; // Load the function for fetching OG images 

    // Fetch articles
    $sql = "SELECT * FROM articles ORDER BY ID DESC";
    $result = $conn->query($sql);
    $articles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    }

    // Fetch events
    $sql = "SELECT * FROM events ORDER BY date ASC";
    $result = $conn->query($sql);
    $events = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    // Fetch featured pets
    $featuredPets = [];
    $sql = "SELECT pet_ID, pet_name, pet_type, description, image FROM pets ORDER BY pet_ID DESC LIMIT 3";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $featuredPets[] = $row;
        }
    }

    // Close the connection
    $conn->close();
    ?>

</head>

<body>
    <?php include 'inc/navbar.inc.php'; ?>
    <!-- Hero Section -->
    <header class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-3 fw-bold mb-3 animate__animated animate__fadeInUp">Find Your New <span class="hero-highlight">Best Friend</span></h1>
                    <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">Adopt a pet and give them a loving home today. You'll be changing their life — and yours!</p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="/listings" class="btn btn-primary btn-lg px-4 py-2">View Available Pets</a>
                        <a href="/about" class="btn btn-outline-light btn-lg px-4 py-2">Learn About Us</a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="position-relative mt-4">
                        <div class="circle-wrapper circle-large">
                            <img src="images/chihuahua_large.jpg" alt="Happy dog" class="circle-img">
                        </div>
                        <div class="circle-wrapper circle-small">
                            <img src="images/calico_large.jpg" alt="Happy dog" class="circle-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Why Adopt Section -->
    <section class="container my-5 py-4">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Why <span class="section-title-highlight">Adopt?</span></h2>
            <div class="d-flex justify-content-center">
                <hr class="mx-auto section-divider">
            </div>
            <p class="lead mt-3 mb-5 text-muted">Adopting a pet not only saves their life but also brings joy and companionship to your home.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 mb-3 d-inline-flex justify-content-center align-items-center icon-circle">
                            <i class="bi bi-heart-fill text-primary icon-primary"></i>
                        </div>
                        <h3 class="h4">Save a Life</h3>
                        <p class="text-muted mb-0">When you adopt, you're giving a deserving pet a second chance at a happy life.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 mb-3 d-inline-flex justify-content-center align-items-center icon-circle">
                            <i class="bi bi-house-heart-fill text-success icon-success"></i>
                        </div>
                        <h3 class="h4">Find Your Match</h3>
                        <p class="text-muted mb-0">We'll help you find the perfect companion to match your lifestyle and preferences.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 mb-3 d-inline-flex justify-content-center align-items-center icon-circle">
                            <i class="bi bi-people-fill text-warning icon-warning"></i>
                        </div>
                        <h3 class="h4">Join Our Community</h3>
                        <p class="text-muted mb-0">Become part of a caring community of pet lovers who support each other.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Pets Section -->
    <section class="container my-5">
        <h2 class="text-center">Featured Pets</h2>
        <div class="row">
            <?php
            // Use the pets data we fetched at the top of the file
            foreach ($featuredPets as $pet) {
                // Get a short description (first 100 characters)
                $shortDesc = substr($pet['description'], 0, 100);
                if (strlen($pet['description']) > 100) {
                    $shortDesc .= '...';
                }

                echo '<div class="col-md-4">
                <div class="card">
                    <div class="image-container">';

                // Check if image exists
                if (!empty($pet['image'])) {
                    echo '<img src="listingImages/' . htmlspecialchars($pet['image']) . '" class="card-img-top" alt="' . htmlspecialchars($pet['pet_name']) . '">';
                } else {
                    // Fallback to default images based on pet type
                    $defaultImage = 'poodle_large.jpg'; // Default to dog
                    if (strtolower($pet['pet_type']) == 'cat') {
                        $defaultImage = 'calico_large.jpg';
                    }
                    echo '<img src="images/' . $defaultImage . '" class="card-img-top" alt="' . htmlspecialchars($pet['pet_name']) . '">';
                }

                echo '</div>
                    <div class="card-body text-center">
                        <h5 class="card-title">' . htmlspecialchars($pet['pet_name']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($shortDesc) . '</p>
                        <a href="/listings?pet=' . $pet['pet_ID'] . '" class="btn btn-success">Adopt Now</a>
                    </div>
                </div>
            </div>';
            }
            ?>
        </div>
    </section>

    <!-- Featured On Carousel -->
    <section class="container my-5">
        <h2 class="text-center">Featured On</h2>

        <div id="featuredOnCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($articles as $index => $article): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <a href="<?= htmlspecialchars($article['url']) ?>" target="_blank">
                            <img src="<?= getOGImage($article['url']) ?>" class="d-block w-100 img-fluid featured-img" alt="<?= htmlspecialchars($article['title']) ?>">
                        </a>
                        <div class="carousel-caption bg-dark bg-opacity-75 rounded p-3">
                            <h5 class="featured-title"><?= htmlspecialchars($article['title']) ?></h5>
                            <a href="<?= htmlspecialchars($article['url']) ?>" target="_blank" class="btn btn-outline-light">Read Article</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#featuredOnCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#featuredOnCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>


    <!-- Upcoming Events Section -->
    <section class="container my-5">
        <h2 class="text-center">Upcoming Events</h2>

        <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <?php
                // foreach ($events as $index => $event): 
                $event = array_chunk($events, 3); // 3 items per slide
                foreach ($event as $index => $event):
                ?>
                    <!-- <div class="col-md-4"> -->
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row justify-content-center">
                            <?php foreach ($event as $item): ?>
                                <div class="col-md-4">
                                    <div class="event-card">
                                        <div class="card-inner">
                                            <div class="card-front">
                                                <h3><?= htmlspecialchars($item["title"]) ?></h3>
                                                <p>📅 <?= htmlspecialchars($item["date"]) ?></p>
                                                <p>⏰ <?= htmlspecialchars($item["time"]) ?></p>
                                                <p>📍<?= htmlspecialchars($item["venue"]) ?></p>
                                            </div>
                                            <div class="card-back">
                                                <p><?= htmlspecialchars($item["details"]) ?></p>
                                                <form method="POST" action=/event>
                                                    <input type="hidden" name="event_id" id=event value="<?= $item["id"] ?>">
                                                    <input type="hidden" name="action" value="register">
                                                    <button type=submit class="btn btn-outline-light">Register</button>
                                                </form>

                                            </div>
                                        </div>
                                        <div class="card-back">
                                            <p><?= htmlspecialchars($event["details"]) ?></p>
                                            <a href="<?= htmlspecialchars($event["link"]) ?>" class="btn btn-outline-light">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#eventsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#eventsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <?php include 'inc/footer.inc.php'; ?>

</body>

</html>