<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Error reporting -->
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | PetAdopt</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

    <?php
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
            <div class="col-md-4">
                <div class="card">
                    <div class="image-container">
                        <img src="images/poodle_large.jpg" class="card-img-top" alt="Dog">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Buddy</h5>
                        <p class="card-text">A playful golden retriever looking for a home.</p>
                        <a href="/listings" class="btn btn-success">Adopt Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="image-container">
                        <img src="images/calico_large.jpg" class="card-img-top" alt="Cat">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Mittens</h5>
                        <p class="card-text">A cute and cuddly kitten waiting for a family.</p>
                        <a href="/listings" class="btn btn-success">Adopt Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="image-container">
                        <img src="images/tabby_large.jpg" class="card-img-top" alt="Dog">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Charlie</h5>
                        <p class="card-text">An energetic beagle who loves to play.</p>
                        <a href="/listings" class="btn btn-success">Adopt Now</a>
                    </div>
                </div>
            </div>
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
                <?php foreach ($events as $index => $event): ?>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="event-card">
                                <div class="card-inner">
                                    <div class="card-front">
                                        <h3><?= htmlspecialchars($event["title"]) ?></h3>
                                        <p><?= htmlspecialchars($event["date"]) ?></p>
                                        <p><?= htmlspecialchars($event["time"]) ?></p>
                                        <p><?= htmlspecialchars($event["venue"]) ?></p>
                                    </div>
                                    <div class="card-back">
                                        <p><?= htmlspecialchars($event["details"]) ?></p>
                                        <a href="<?= htmlspecialchars($event["link"]) ?>" class="btn btn-outline-light">View Details</a>
                                    </div>
                                </div>
                            </div>
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