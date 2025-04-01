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
    <header class="hero-section text-center text-black">
        <div class="container">
            <h1>Find Your New Best Friend</h1>
            <p>Adopt a pet and give them a loving home today.</p>
            <a href="/listings" class="btn btn-primary">View Available Pets</a>
        </div>
    </header>

    <!-- Why Adopt Section -->
    <section class="container text-center my-5">
        <h2>Why Adopt?</h2>
        <p>Adopting a pet not only saves their life but also brings joy to your home.</p>
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
                        <?php foreach($event as $item): ?>
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
                                                <input type="hidden" name="event_id" id=event value="<?= $item["id"]?>">
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