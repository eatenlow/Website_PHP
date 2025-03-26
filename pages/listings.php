<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings | PetAdopt</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/listings.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nouislider@14.6.3/distribute/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@14.6.3/distribute/nouislider.min.css">
    <script src="js/main.js"></script>
</head>

<body>
    <?php include 'inc/navbar.inc.php'; ?>

    <section class="container my-5">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-md-3 filter-sidebar">
                <form id="filter-form" method="GET" action="">
                    <div class="filter-card">
                        <div class="filter-title">Pet Type</div>
                        <div class="filter-section">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pet_type" id="all-pets" value="all" checked>
                                <label class="form-check-label" for="all-pets">All Pets</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pet_type" id="dogs-only" value="Dog">
                                <label class="form-check-label" for="dogs-only">Dogs Only</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pet_type" id="cats-only" value="Cat">
                                <label class="form-check-label" for="cats-only">Cats Only</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter-card">
                        <div class="filter-title">Gender</div>
                        <div class="filter-section">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="all-genders" value="all" checked>
                                <label class="form-check-label" for="all-genders">All</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter-card">
                        <div class="filter-title">Price Range</div>
                        <div class="filter-section">
                            <div id="price-slider" class="price-slider"></div>
                            <div class="price-range-values">
                                <span>$<span id="price-min">0</span></span>
                                <span>$<span id="price-max">1000</span></span>
                            </div>
                            <input type="hidden" name="min_price" id="min-price-input" value="0">
                            <input type="hidden" name="max_price" id="max-price-input" value="1000">
                        </div>
                    </div>

                    <div class="filter-card">
                        <div class="filter-title">Breeds</div>
                        <div class="filter-section">
                            <div class="breed-list">
                                <?php
                                $config = parse_ini_file('/var/www/private/db-config.ini');
                                if ($config) {
                                    $conn = new mysqli(
                                        $config['servername'],
                                        $config['username'],
                                        $config['password'],
                                        $config['dbname']
                                    );
                                    
                                    if (!$conn->connect_error) {
                                        // Get dog breeds
                                        $dogBreedsSql = "SELECT DISTINCT breed FROM pets WHERE pet_type = 'Dog' ORDER BY breed";
                                        $dogBreedsResult = $conn->query($dogBreedsSql);
                                        
                                        echo '<div class="breed-category">Dogs</div>';
                                        if ($dogBreedsResult->num_rows > 0) {
                                            while ($row = $dogBreedsResult->fetch_assoc()) {
                                                echo '<div class="form-check">';
                                                echo '<input class="form-check-input" type="checkbox" name="breeds[]" id="breed-' . htmlspecialchars($row['breed']) . '" value="' . htmlspecialchars($row['breed']) . '">';
                                                echo '<label class="form-check-label" for="breed-' . htmlspecialchars($row['breed']) . '">' . htmlspecialchars($row['breed']) . '</label>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<p>No dog breeds available</p>';
                                        }
                                        
                                        // Get cat breeds
                                        $catBreedsSql = "SELECT DISTINCT breed FROM pets WHERE pet_type = 'Cat' ORDER BY breed";
                                        $catBreedsResult = $conn->query($catBreedsSql);
                                        
                                        echo '<div class="breed-category">Cats</div>';
                                        if ($catBreedsResult->num_rows > 0) {
                                            while ($row = $catBreedsResult->fetch_assoc()) {
                                                echo '<div class="form-check">';
                                                echo '<input class="form-check-input" type="checkbox" name="breeds[]" id="breed-' . htmlspecialchars($row['breed']) . '" value="' . htmlspecialchars($row['breed']) . '">';
                                                echo '<label class="form-check-label" for="breed-' . htmlspecialchars($row['breed']) . '">' . htmlspecialchars($row['breed']) . '</label>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<p>No cat breeds available</p>';
                                        }
                                        
                                        $conn->close();
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 filter-button">Apply Filters</button>
                    <a href="/listings" class="btn btn-outline-secondary w-100 mt-2">Reset Filters</a>
                </form>
            </div>

            <!-- Listings Content -->
            <div class="col-md-9">
                <div class="sort-container">
                    <h2>Pet Listings</h2>
                    <div class="d-flex align-items-center">
                        <span class="sort-label">Sort by:</span>
                        <select id="sort-select" class="form-select" name="sort" onchange="this.form.submit()">
                            <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Name (A-Z)</option>
                            <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                            <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price (Low to High)</option>
                            <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
                            <option value="age_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'age_asc') ? 'selected' : ''; ?>>Age (Young to Old)</option>
                            <option value="age_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'age_desc') ? 'selected' : ''; ?>>Age (Old to Young)</option>
                        </select>
                    </div>
                </div>

                <div id="listings-container" class="row">
                    <?php
                    $config = parse_ini_file('/var/www/private/db-config.ini');
                    if (!$config) {
                        echo "<p>Failed to read database config file.</p>";
                    } else {
                        $conn = new mysqli(
                            $config['servername'],
                            $config['username'],
                            $config['password'],
                            $config['dbname']
                        );
                        
                        if ($conn->connect_error) {
                            echo "<p>Connection failed: " . $conn->connect_error . "</p>";
                        } else {
                            // Parse filters
                            $petType = isset($_GET['pet_type']) && $_GET['pet_type'] != 'all' ? $_GET['pet_type'] : null;
                            $gender = isset($_GET['gender']) && $_GET['gender'] != 'all' ? $_GET['gender'] : null;
                            $minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
                            $maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000;
                            $selectedBreeds = isset($_GET['breeds']) ? $_GET['breeds'] : [];
                            $sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
                            
                            // Build the query
                            $conditions = [];
                            $params = [];
                            $types = '';
                            
                            // Pet type filter
                            if ($petType) {
                                $conditions[] = "pet_type = ?";
                                $params[] = $petType;
                                $types .= 's';
                            }
                            
                            // Gender filter
                            if ($gender) {
                                $conditions[] = "gender = ?";
                                $params[] = $gender;
                                $types .= 's';
                            }
                            
                            // Price range
                            $conditions[] = "adopt_cost BETWEEN ? AND ?";
                            $params[] = $minPrice;
                            $params[] = $maxPrice;
                            $types .= 'ii';
                            
                            // Breeds filter
                            if (!empty($selectedBreeds)) {
                                $breedPlaceholders = str_repeat('?,', count($selectedBreeds) - 1) . '?';
                                $conditions[] = "breed IN ($breedPlaceholders)";
                                foreach ($selectedBreeds as $breed) {
                                    $params[] = $breed;
                                    $types .= 's';
                                }
                            }
                            
                            // Build WHERE clause
                            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
                            
                            // Sort
                            $orderBy = '';
                            switch ($sortOption) {
                                case 'name_asc':
                                    $orderBy = "ORDER BY pet_name ASC";
                                    break;
                                case 'name_desc':
                                    $orderBy = "ORDER BY pet_name DESC";
                                    break;
                                case 'price_asc':
                                    $orderBy = "ORDER BY adopt_cost ASC";
                                    break;
                                case 'price_desc':
                                    $orderBy = "ORDER BY adopt_cost DESC";
                                    break;
                                case 'age_asc':
                                    $orderBy = "ORDER BY age ASC";
                                    break;
                                case 'age_desc':
                                    $orderBy = "ORDER BY age DESC";
                                    break;
                                default:
                                    $orderBy = "ORDER BY pet_name ASC";
                            }
                            
                            // Pagination
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $itemsPerPage = 9;
                            $offset = ($page - 1) * $itemsPerPage;
                            
                            // Count total matching items
                            $countSql = "SELECT COUNT(*) FROM pets $whereClause";
                            $stmt = $conn->prepare($countSql);
                            
                            if (!empty($params)) {
                                $stmt->bind_param($types, ...$params);
                            }
                            
                            $stmt->execute();
                            $totalItems = $stmt->get_result()->fetch_row()[0];
                            $totalPages = ceil($totalItems / $itemsPerPage);
                            
                            // Get the listings
                            $listingSql = "SELECT * FROM pets $whereClause $orderBy LIMIT ? OFFSET ?";
                            $stmt = $conn->prepare($listingSql);
                            
                            // Add pagination parameters
                            $params[] = $itemsPerPage;
                            $params[] = $offset;
                            $types .= 'ii';
                            
                            $stmt->bind_param($types, ...$params);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<div class="col-md-4 mb-4">';
                                    echo '<div class="card">';
                                    echo '<img src="' . dirname(__DIR__).'/listingImages/'.htmlspecialchars($row['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['pet_name']) . '">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . htmlspecialchars($row['pet_name']) . '</h5>';
                                    echo '<p class="card-text">';
                                    echo '<strong>Breed:</strong> ' . htmlspecialchars($row['breed']) . '<br>';
                                    echo '<strong>Type:</strong> ' . htmlspecialchars($row['pet_type']) . '<br>';
                                    echo '<strong>Age:</strong> ' . htmlspecialchars($row['age']) . '<br>';
                                    echo '<strong>Gender:</strong> ' . htmlspecialchars($row['gender']) . '<br>';
                                    echo '<strong>Adoption Cost:</strong> $' . htmlspecialchars($row['adopt_cost']) . '<br>';
                                    echo '<strong>Description:</strong> ' . htmlspecialchars($row['description']);
                                    echo '</p>';
                                    echo '<a href="/pet/' . htmlspecialchars($row['pet_id']) . '" class="btn btn-primary">Adopt Now</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="col-12"><p>No pets match your filter criteria. Please try different filters.</p></div>';
                            }
                            
                            // Display pagination
                            if ($totalPages > 1) {
                                echo '<div class="col-12 text-center pagination-container">';
                                
                                // Create the base URL with all current GET parameters except 'page'
                                $queryParams = $_GET;
                                unset($queryParams['page']);
                                $baseUrl = '?' . http_build_query($queryParams);
                                if (!empty($queryParams)) {
                                    $baseUrl .= '&';
                                }
                                
                                for ($i = 1; $i <= $totalPages; $i++) {
                                    echo '<a href="' . $baseUrl . 'page=' . $i . '" class="btn btn-light mx-1 ' . ($page == $i ? 'active' : '') . '">' . $i . '</a>';
                                }
                                
                                echo '</div>';
                            }
                            
                            $conn->close();
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include "inc/footer.inc.php"; ?>

    <div class="image-popup" style="display: none;">
        <button id="close-popup" style="position: absolute; top: 20px; right: 20px; background: rgba(128, 128, 128, 0.7); border: none; color: white; font-size: 24px; padding: 8px; border-radius: 50%; cursor: pointer; line-height: 1;">&times;</button>
        <img id="popup-image" src="" alt="Popup Image">
    </div>

    <script>
        $(document).ready(function() {
            // Initialize price slider
            var priceSlider = document.getElementById('price-slider');
            
            if (priceSlider) {
                noUiSlider.create(priceSlider, {
                    start: [
                        <?php echo isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0 ?>, 
                        <?php echo isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000 ?>
                    ],
                    connect: true,
                    range: {
                        'min': 0,
                        'max': 1000
                    },
                    step: 10
                });
                
                // Update price display and hidden inputs when slider values change
                priceSlider.noUiSlider.on('update', function(values, handle) {
                    var value = Math.round(values[handle]);
                    
                    if (handle === 0) {
                        document.getElementById('price-min').textContent = value;
                        document.getElementById('min-price-input').value = value;
                    } else {
                        document.getElementById('price-max').textContent = value;
                        document.getElementById('max-price-input').value = value;
                    }
                });
            }
            
            // Image popup functionality
            $('.card-img-top').click(function() {
                var imageUrl = $(this).attr('src');
                $('#popup-image').attr('src', imageUrl);
                $('.image-popup').fadeIn();
            });
            
            $('.image-popup').click(function(event) {
                if (event.target === this) { // Only close if the background is clicked
                    $(this).fadeOut();
                }
            });
            
            $('#close-popup').click(function() {
                $('.image-popup').fadeOut();
            });
            
            $(document).keydown(function(event) {
                if (event.key === "Escape") {
                    $('.image-popup').fadeOut();
                }
            });
            
            // Sort change handler
            $('#sort-select').change(function() {
                var sortVal = $(this).val();
                
                // Get current URL and parameters
                var url = new URL(window.location.href);
                url.searchParams.set('sort', sortVal);
                
                // Navigate to the new URL
                window.location.href = url.toString();
            });
            
            // Pet type radio change handler - show/hide relevant breeds
            $('input[name="pet_type"]').change(function() {
                var selectedType = $(this).val();
                
                if (selectedType === 'Dog') {
                    $('.breed-category:contains("Dogs")').show();
                    $('.breed-category:contains("Cats")').hide();
                    $('.breed-category:contains("Cats")').nextUntil('.breed-category').hide();
                } else if (selectedType === 'Cat') {
                    $('.breed-category:contains("Dogs")').hide();
                    $('.breed-category:contains("Dogs")').nextUntil('.breed-category').hide();
                    $('.breed-category:contains("Cats")').show();
                } else {
                    $('.breed-category').show();
                    $('.breed-category').nextUntil('.breed-category').show();
                }
            });
            
            // Initialize filters based on URL parameters
            var urlParams = new URLSearchParams(window.location.search);
            
            // Pet type
            var petType = urlParams.get('pet_type');
            if (petType) {
                $(`input[name="pet_type"][value="${petType}"]`).prop('checked', true).trigger('change');
            }
            
            // Gender
            var gender = urlParams.get('gender');
            if (gender) {
                $(`input[name="gender"][value="${gender}"]`).prop('checked', true);
            }
            
            // Breeds
            var breeds = urlParams.getAll('breeds[]');
            if (breeds.length) {
                breeds.forEach(function(breed) {
                    $(`input[name="breeds[]"][value="${breed}"]`).prop('checked', true);
                });
            }
        });
    </script>
</body>

</html>