<?php
// This file contains the listing display logic

// Function to build SQL query conditions from filters
function buildFilterConditions($conn) {
    $conditions = [];
    $params = [];
    $types = '';
    
    // Pet type filter
    $petType = isset($_GET['pet_type']) && $_GET['pet_type'] != 'all' ? $_GET['pet_type'] : null;
    if ($petType) {
        $conditions[] = "pet_type = ?";
        $params[] = $petType;
        $types .= 's';
    }
    
    // Gender filter
    $gender = isset($_GET['gender']) && $_GET['gender'] != 'all' ? $_GET['gender'] : null;
    if ($gender) {
        $conditions[] = "gender = ?";
        $params[] = $gender;
        $types .= 's';
    }
    
    // Price range
    $minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
    $maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000;
    $conditions[] = "adopt_cost BETWEEN ? AND ?";
    $params[] = $minPrice;
    $params[] = $maxPrice;
    $types .= 'ii';
    
    // Breeds filter
    $selectedBreeds = isset($_GET['breeds']) ? $_GET['breeds'] : [];
    if (!empty($selectedBreeds)) {
        $breedPlaceholders = str_repeat('?,', count($selectedBreeds) - 1) . '?';
        $conditions[] = "breed IN ($breedPlaceholders)";
        foreach ($selectedBreeds as $breed) {
            $params[] = $breed;
            $types .= 's';
        }
    }

    // Search filter (case insensitive)
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = "%{$_GET['search']}%";
        $conditions[] = "(LOWER(pet_name) LIKE LOWER(?) OR LOWER(description) LIKE LOWER(?))";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }
    
    // Build WHERE clause
    $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
    
    return [
        'whereClause' => $whereClause,
        'params' => $params,
        'types' => $types
    ];
}

// Function to determine sort order
function getSortOrder() {
    $sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
    
    switch ($sortOption) {
        case 'name_asc':
            return "ORDER BY pet_name ASC";
        case 'name_desc':
            return "ORDER BY pet_name DESC";
        case 'price_asc':
            return "ORDER BY adopt_cost ASC";
        case 'price_desc':
            return "ORDER BY adopt_cost DESC";
        case 'age_asc':
            return "ORDER BY age ASC";
        case 'age_desc':
            return "ORDER BY age DESC";
        default:
            return "ORDER BY pet_name ASC";
    }
}

// Function to get pagination info
function getPagination() {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $itemsPerPage = 9;
    $offset = ($page - 1) * $itemsPerPage;
    
    return [
        'page' => $page,
        'itemsPerPage' => $itemsPerPage,
        'offset' => $offset
    ];
}

// Function to display pet listings
function displayListings($conn) {
    // Get filter conditions
    $filterData = buildFilterConditions($conn);
    $whereClause = $filterData['whereClause'];
    $params = $filterData['params'];
    $types = $filterData['types'];
    
    // Get sort order
    $orderBy = getSortOrder();
    
    // Get pagination info
    $pagination = getPagination();
    $page = $pagination['page'];
    $itemsPerPage = $pagination['itemsPerPage'];
    $offset = $pagination['offset'];
    
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
            $plainDescription = htmlspecialchars($row['description']);
            $truncatedText = substr($plainDescription, 0, 110);
            $showMoreLink = '<span class="show-more-text" data-id="'.$row['pet_ID'].'">(show more)</span>';

            if (strlen($plainDescription) > 110) {
                $displayText = nl2br($truncatedText) . '... ' . $showMoreLink;
            } else {
                $displayText = nl2br($plainDescription);
            }

            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card">';
            echo '<img src="' . dirname(__DIR__).'/listingImages/'.htmlspecialchars($row['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['pet_name']) . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($row['pet_name']) . '</h5>';
            echo '<h4 class="card-title">' . htmlspecialchars($row['pet_name']) . '</h5>';
            echo '<p class="card-text">';
            echo '<strong>Breed:</strong> ' . htmlspecialchars($row['breed']) . '<br>';
            echo '<strong>Type:</strong> ' . htmlspecialchars($row['pet_type']) . '<br>';
            echo '<strong>Age:</strong> ' . htmlspecialchars($row['age']) . '<br>';
            echo '<strong>Gender:</strong> ' . htmlspecialchars($row['gender']) . '<br>';
            echo '<strong>Adoption Cost:</strong> $' . htmlspecialchars($row['adopt_cost']) . '<br>';
            echo '<strong>Description:</strong> ' . $displayText;
            echo '</p>';
            echo '<form action="backend/add_to_cart.php" method="post" class="d-inline">';
            echo '<input type="hidden" name="pet_id" value="' . htmlspecialchars($row['pet_ID']) . '">';
            echo '<input type="hidden" name="pet_name" value="' . htmlspecialchars($row['pet_name']) . '">';
            echo '<input type="hidden" name="pet_price" value="' . htmlspecialchars($row['adopt_cost']) . '">';
            echo '<input type="hidden" name="pet_image" value="' . htmlspecialchars($row['image']) . '">'; 
            echo '<button type="submit" class="btn btn-primary">Adopt Now</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-12"><p>No pets match your filter criteria. Please try different filters.</p></div>';
    }
    
    // Return pagination info for the pagination function
    return [
        'totalPages' => $totalPages,
        'currentPage' => $page
    ];
}

// Function to render pagination links
function renderPagination($paginationInfo) {
    $totalPages = $paginationInfo['totalPages'];
    $currentPage = $paginationInfo['currentPage'];
    
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
            echo '<a href="' . $baseUrl . 'page=' . $i . '" class="btn btn-light mx-1 ' . ($currentPage == $i ? 'active' : '') . '">' . $i . '</a>';
        }
        
        echo '</div>';
    }
}
?>
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
</head>
<body>
    
    <header>
    <h1 class="visually-hidden">Pet Adoption Center</h1>
    <?php include 'inc/navbar.inc.php'; ?>
    </header>

    <main>
    <section class="container my-5">
        <div class="row">
            <!-- Include the filter sidebar -->
            <?php include 'backend/filter.php'; ?>
            
            <!-- Listings Content HTML -->
            <div class="col-md-9">
                <div class="sort-container">
                    <h2>Pet Listings</h2>
                    <div class="d-flex align-items-center">
                        <span class="sort-label">Sort by:</span>
                    <div class="d-flex align-items-center">  
                        <label for="sort-select" class="sort-label">Sort by:</label>
                        <select id="sort-select" class="form-select" name="sort">
                            <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Name (A-Z)</option>
                            <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                            <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price (Low to High)</option>
                            <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
                            <option value="age_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'age_asc') ? 'selected' : ''; ?>>Age (Young to Old)</option>
                            <option value="age_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'age_desc') ? 'selected' : ''; ?>>Age (Old to Young)</option>
                        </select>
                    </div>
                </div>

                <h3 class="visually-hidden">Individual Pets</h3>
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
                            // Display listings and get pagination info
                            $paginationInfo = displayListings($conn);
                            
                            // Render pagination
                            renderPagination($paginationInfo);
                            
                            $conn->close();
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    </main>
    <?php include "inc/footer.inc.php"; ?>
    <!-- Include the popup functionality -->
    <?php include 'backend/popup.php'; ?>
    
    <script>
    $(document).ready(function() {
        // Sort change handler
        $('#sort-select').change(function() {
            var sortVal = $(this).val();
            
            // Get current URL and parameters
            var url = new URL(window.location.href);
            url.searchParams.set('sort', sortVal);
            
            // Navigate to the new URL
            window.location.href = url.toString();
        });
    });
    </script>
</body>
</html>