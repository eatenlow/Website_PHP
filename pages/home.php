<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | PetAdopt</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <?php
    include 'inc/carousel.php'; // Load the function for fetching OG images

    $articles = [
        ["title" => "Nparks", "url" => "https://www.nparks.gov.sg/publications-resources/articles/getting-a-pet--adopt-or-purchase"],
        ["title" => "Interview with The Pet Gazette", "url" => "https://www.straitstimes.com/singapore/drop-in-pet-adoption-rates-as-more-people-return-to-office-animal-shelters"],
        ["title" => "Trending on Social Media", "url" => "https://twitter.com/trendingadoption"]
    ];
    ?>

</head>

<body>
    <?php include 'inc/navbar.inc.php'; ?>
    <!-- Hero Section -->
    <header class="hero-section text-center text-white">
        <div class="container">
            <h1>Find Your New Best Friend</h1>
            <p>Adopt a pet and give them a loving home today.</p>
            <a href="listings.php" class="btn btn-primary">View Available Pets</a>
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
                        <a href="listings.php" class="btn btn-success">Adopt Now</a>
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
                        <a href="listings.php" class="btn btn-success">Adopt Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="image-container">
                        <img src="images/chihuahua_large.jpg" class="card-img-top" alt="Dog">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Charlie</h5>
                        <p class="card-text">An energetic beagle who loves to play.</p>
                        <a href="listings.php" class="btn btn-success">Adopt Now</a>
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
                            <img src="<?= getOGImage($article['url']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($article['title']) ?>">
                        </a>
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded p-3">
                            <h5><?= htmlspecialchars($article['title']) ?></h5>
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

        <!-- Events Carousel -->
        <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <!-- Slide 1 - Event -->
                <div class="carousel-item active">
                    <img src="assets/event1.jpg" class="d-block w-100" alt="Event 1">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded p-3">
                        <h5>Pet Adoption Drive - April 2025</h5>
                        <p>Join us for a special adoption event at the city park!</p>
                        <a href="events.php" class="btn btn-outline-light">View Details</a>
                    </div>
                </div>

                <!-- Slide 2 - Event -->
                <div class="carousel-item">
                    <img src="assets/event2.jpg" class="d-block w-100" alt="Event 2">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded p-3">
                        <h5>Free Pet Health Check-Up</h5>
                        <p>Come and get a free health check for your pet at our center.</p>
                        <a href="events.php" class="btn btn-outline-light">View Details</a>
                    </div>
                </div>

                <!-- Slide 3 - Event -->
                <div class="carousel-item">
                    <img src="assets/event3.jpg" class="d-block w-100" alt="Event 3">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded p-3">
                        <h5>Pet Training Workshop</h5>
                        <p>Learn how to train your pet with the help of experts.</p>
                        <a href="events.php" class="btn btn-outline-light">View Details</a>
                    </div>
                </div>

            </div>

            <!-- Carousel Controls for Events -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>