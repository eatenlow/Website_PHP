<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | PetAdopt</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <?php include 'inc/navbar.inc.php'; ?>

    <!-- Introduction -->
    <section class="container my-5">
        <h2 class="text-center">About Us</h2>
        <div class="row justify-content-center">
            <!-- Mission Card -->
            <div class="col-md-5">
                <div class="card mission-vision-card text-center">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fa-solid fa-shield-dog"></i> <!-- FontAwesome Icon -->
                        </div>
                        <h3 class="card-title">Who We Are</h3>
                        <p class="card-text">PetAdopt is a dedicated adoption center that provides a temporary, loving home for animals before they find their forever families. We ensure every pet receives the care, support, and medical attention they need while they wait to be adopted. </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="container my-5">
        <h2 class="text-center">Mission & Vision</h2>
        <div class="row justify-content-center">
            <!-- Mission Card -->
            <div class="col-md-5">
                <div class="card mission-vision-card text-center">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fa-solid fa-bullseye"></i> <!-- FontAwesome Icon -->
                        </div>
                        <h3 class="card-title">MISSION</h3>
                        <p class="card-text">To promote kindness and to prevent cruelty to animals through education, advocacy, and action.</p>
                    </div>
                </div>
            </div>

            <!-- Vision Card -->
            <div class="col-md-5">
                <div class="card mission-vision-card text-center">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fa-solid fa-eye"></i> <!-- FontAwesome Icon -->
                        </div>
                        <h3 class="card-title">VISION</h3>
                        <p class="card-text">A compassionate world where all animals are treated with kindness and respect.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- How Pet Adoption Works -->
    <section class="container my-5">
        <h2 class="text-center">How Pet Adoption Works</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <i class="bi bi-search-heart display-4"></i>
                <h4>Step 1: Browse Pets</h4>
                <p>Explore available pets on our website and find your perfect match.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-chat-square-text display-4"></i>
                <h4>Step 2: Apply & Connect</h4>
                <p>Submit an adoption application and get in touch with the pet’s foster home or shelter.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-house-heart display-4"></i>
                <h4>Step 3: Bring Them Home</h4>
                <p>Once approved, welcome your new pet into your home and family!</p>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="container my-5">
        <h2 class="text-center">Get In Touch</h2>
        <p class="text-center">Have questions? Feel free to reach out to us.</p>
        <div class="row">
            <div class="col-md-6">
                <h4><i class="bi bi-envelope"></i> Email</h4>
                <p>contact@petadopt.com</p>
                <h4><i class="bi bi-telephone"></i> Phone</h4>
                <p>+65 9123 4567</p>
            </div>
            <div class="col-md-6">
                <h4><i class="bi bi-instagram"></i> Social Media</h4>
                <div class="social-icons">
                    <a href="https://facebook.com" target="_blank">
                        <i class="fa-brands fa-facebook fa-2xl"></i> <!-- FontAwesome Icon -->
                    </a>
                    <a href="https://instagram.com" target="_blank">
                        <i class="fa-brands fa-instagram fa-2xl"></i> <!-- FontAwesome Icon -->
                    </a>
                </div>

                <h4><i class="bi bi-geo-alt"></i> Visit Us</h4>
                <p>11 New Punggol Rd, Singapore 828616</p>
            </div>
        </div>
    </section>

    <!-- Google Map Embed -->
    <section class="container my-5">
        <h2 class="text-center">Our Location</h2>
        <div class="map-container text-center">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.6031349806262!2d103.90816577515022!3d1.4141430985725234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da15ac1f854ff5%3A0x681080ba33c348ca!2sSingapore%20Institute%20of%20Technology%20(Campus%20Heart)%20(U%2FC)!5e0!3m2!1sen!2ssg!4v1742288281769!5m2!1sen!2ssg"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </section>

    <?php include 'inc/footer.inc.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>