<?php
// This file contains the filter sidebar and related PHP code

// Function to get all available breeds from the database
function getBreeds($conn, $petType = null) {
    $where = $petType ? "WHERE pet_type = '$petType'" : "";
    $sql = "SELECT DISTINCT breed, pet_type FROM pets $where ORDER BY pet_type, breed";
    return $conn->query($sql);
}

// Function to check if a filter is active
function isFilterActive($paramName, $value = null) {
    if (!isset($_GET[$paramName])) {
        return false;
    }
    
    if ($value !== null) {
        return $_GET[$paramName] == $value;
    }
    
    return true;
}

// Function to build the breeds checkboxes
function renderBreedCheckboxes($conn) {
    // Get dog breeds
    $dogBreedsSql = "SELECT DISTINCT breed FROM pets WHERE pet_type = 'Dog' ORDER BY breed";
    $dogBreedsResult = $conn->query($dogBreedsSql);
    
    echo '<div class="breed-category">Dogs</div>';
    if ($dogBreedsResult->num_rows > 0) {
        while ($row = $dogBreedsResult->fetch_assoc()) {
            $breed = htmlspecialchars($row['breed']);
            $breedId = 'breed-' . preg_replace('/\s+/', '-', strtolower($row['breed']));

            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="breeds[]" id="' . $breedId . '" value="' . $breed . '"';

            // Check if this breed is selected in the filter
            if (isset($_GET['breeds']) && in_array($row['breed'], $_GET['breeds'])) {
                echo ' checked';
            }
            
            echo '>';
            echo '<label class="form-check-label" for="' . $breedId . '">' . $breed . '</label>';
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
            $breed = htmlspecialchars($row['breed']);
            $breedId = 'breed-' . preg_replace('/\s+/', '-', strtolower($row['breed']));

            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="breeds[]" id="' . $breedId . '" value="' . $breed . '"';

            // Check if this breed is selected in the filter
            if (isset($_GET['breeds']) && in_array($row['breed'], $_GET['breeds'])) {
                echo ' checked';
            }
            
            echo '>';
            echo '<label class="form-check-label" for="' . $breedId . '">' . $breed . '</label>';
            echo '</div>';
        }
    } else {
        echo '<p>No cat breeds available</p>';
    }
}
?>

<!-- Filter Sidebar HTML -->
<div class="col-md-3 filter-sidebar">
    <form id="filter-form" method="GET">

    <div class="filter-card">
            <div class="filter-title">Search</div>
            <div class="filter-section">
                <div class="form-group">
                    <input type="text" class="form-control" name="search" id="search-input" placeholder="Search pet names..." 
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Pet Type</div>
            <div class="filter-section">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pet_type" id="all-pets" value="all" <?php echo (!isset($_GET['pet_type']) || $_GET['pet_type'] == 'all') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="all-pets">All Pets</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pet_type" id="dogs-only" value="Dog" <?php echo (isset($_GET['pet_type']) && $_GET['pet_type'] == 'Dog') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="dogs-only">Dogs Only</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pet_type" id="cats-only" value="Cat" <?php echo (isset($_GET['pet_type']) && $_GET['pet_type'] == 'Cat') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="cats-only">Cats Only</label>
                </div>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Gender</div>
            <div class="filter-section">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="all-genders" value="all" <?php echo (!isset($_GET['gender']) || $_GET['gender'] == 'all') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="all-genders">All</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="Female" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Price Range</div>
            <div class="filter-section">
                <div id="price-slider" class="price-slider"></div>
                <div class="price-range-values">
                    <span>$<span id="price-min"><?php echo isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0; ?></span></span>
                    <span>$<span id="price-max"><?php echo isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000; ?></span></span>
                </div>
                <input type="hidden" name="min_price" id="min-price-input" value="<?php echo isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0; ?>">
                <input type="hidden" name="max_price" id="max-price-input" value="<?php echo isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000; ?>">
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
                            renderBreedCheckboxes($conn);
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
    
    // Initialize pet type filter
    var petType = "<?php echo isset($_GET['pet_type']) ? $_GET['pet_type'] : 'all'; ?>";
    if (petType && petType !== 'all') {
        $(`input[name="pet_type"][value="${petType}"]`).trigger('change');
    }
});
</script>