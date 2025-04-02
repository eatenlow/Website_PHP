<?php
// This file contains the filter sidebar and related PHP code

// Database connection - moved to top since we need it for configuration
function getDatabaseConnection() {
    $config = parse_ini_file('/var/www/private/db-config.ini');
    if ($config) {
        return new mysqli(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['dbname']
        );
    }
    return null;
}

// Configuration values that could be moved to a settings file
$config = [
    'priceRange' => [
        'min' => 0,
        'max' => 1000,
        'step' => 10
    ]
];

// Get pet types dynamically from database
function getPetTypes($conn) {
    $petTypes = [];
    $sql = "SELECT DISTINCT pet_type FROM pets ORDER BY pet_type";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $petTypes[] = $row['pet_type'];
        }
    }
    
    return $petTypes;
}

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

// Function to get filter value with default
function getFilterValue($paramName, $default = '') {
    return isset($_GET[$paramName]) ? htmlspecialchars($_GET[$paramName]) : $default;
}

// Function to render a radio button option
function renderRadioOption($name, $id, $value, $label, $isDefault = false) {
    $checked = (!isset($_GET[$name]) && $isDefault) || 
               (isset($_GET[$name]) && $_GET[$name] == $value) ? 'checked' : '';
    
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="radio" name="' . $name . '" id="' . $id . '" value="' . $value . '" ' . $checked . '>';
    echo '<label class="form-check-label" for="' . $id . '">' . $label . '</label>';
    echo '</div>';
}

// Function to build the breeds checkboxes dynamically by pet type
function renderBreedCheckboxes($conn) {
    // Get all breeds grouped by pet type
    $sql = "SELECT DISTINCT pet_type, breed FROM pets ORDER BY pet_type, breed";
    $result = $conn->query($sql);
    
    $currentType = null;
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $petType = $row['pet_type'];
            $breed = $row['breed'];
            
            // Add a header for each new pet type
            if ($currentType !== $petType) {
                if ($currentType !== null) {
                    echo '</div>'; // Close previous breed list container
                }
                $currentType = $petType;
                echo '<div class="breed-category" data-pet-type="' . $petType . '">' . $petType . 's</div>';
                echo '<div class="breed-list-container" data-pet-type="' . $petType . '">';
            }
            
            renderBreedCheckbox($breed, $petType);
        }
        
        if ($currentType !== null) {
            echo '</div>'; // Close the last breed list container
        }
    } else {
        echo '<p>No breeds available</p>';
    }
}

// Helper function to render a single breed checkbox
function renderBreedCheckbox($breed, $petType) {
    $breedId = 'breed-' . preg_replace('/\s+/', '-', strtolower($breed));
    $checked = isset($_GET['breeds']) && in_array($breed, $_GET['breeds']) ? 'checked' : '';
    
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="checkbox" name="breeds[]" id="' . $breedId . '" value="' . $breed . '" ' . $checked . ' data-pet-type="' . $petType . '">';
    echo '<label class="form-check-label" for="' . $breedId . '">' . htmlspecialchars($breed) . '</label>';
    echo '</div>';
}

// Get price range values
$minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : $config['priceRange']['min'];
$maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : $config['priceRange']['max'];
$priceStep = $config['priceRange']['step'];

// Get current pet type filter
$currentPetType = isset($_GET['pet_type']) ? $_GET['pet_type'] : 'all';

// Get database connection
$conn = getDatabaseConnection();
?>

<!-- Filter Sidebar HTML -->
<aside class="col-md-3 filter-sidebar" aria-label="Filter options">
    <form id="filter-form" method="GET">

        <div class="filter-card">
            <div class="filter-title">Search</div>
            <div class="filter-section">
                <div class="form-group">
                    <label for="search-input" class="visually-hidden">Search Pet Names</label>
                    <input type="text" class="form-control" name="search" id="search-input" 
                           placeholder="Search pet names..." 
                           value="<?php echo getFilterValue('search'); ?>">
                </div>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Pet Type</div>
            <div class="filter-section">
                <?php 
                // Render the "All Pets" option
                renderRadioOption('pet_type', 'all-pets', 'all', 'All Pets', true);
                
                // Render options for each pet type dynamically from database
                if ($conn && !$conn->connect_error) {
                    $petTypes = getPetTypes($conn);
                    foreach ($petTypes as $petType) {
                        renderRadioOption(
                            'pet_type', 
                            strtolower($petType) . 's-only', 
                            $petType, 
                            $petType . 's Only'
                        );
                    }
                }
                ?>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Gender</div>
            <div class="filter-section">
                <?php
                renderRadioOption('gender', 'all-genders', 'all', 'All', true);
                renderRadioOption('gender', 'male', 'Male', 'Male');
                renderRadioOption('gender', 'female', 'Female', 'Female');
                ?>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Price Range</div>
            <div class="filter-section">
                <div id="price-slider" class="price-slider" role="group" aria-labelledby="price-range-label" aria-describedby="price-range-description"> </div>
                <div id="price-range-label" class="visually-hidden">Price Range Selection</div>
                <div id="price-range-description" class="visually-hidden">Use slider to select minimum and maximum price</div>
                <div class="price-range-values">
                    <span>$<span id="price-min"><?php echo $minPrice; ?></span></span>
                    <span>$<span id="price-max"><?php echo $maxPrice; ?></span></span>
                </div>
                <input type="hidden" name="min_price" id="min-price-input" value="<?php echo $minPrice; ?>">
                <input type="hidden" name="max_price" id="max-price-input" value="<?php echo $maxPrice; ?>">
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Breeds</div>
            <div class="filter-section">
                <?php
                if ($conn && !$conn->connect_error) {
                    renderBreedCheckboxes($conn);
                } else {
                    echo '<p>Could not load breeds. Please try again later.</p>';
                }
                ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 filter-button">Apply Filters</button>
        <a href="/listings" class="btn btn-outline-secondary w-100 mt-2">Reset Filters</a>
    </form>
</aside>

<script>
$(document).ready(function() {
    // Initialize price slider
    var priceSlider = document.getElementById('price-slider');
    
    if (priceSlider) {
        noUiSlider.create(priceSlider, {
            start: [<?php echo $minPrice; ?>, <?php echo $maxPrice; ?>],
            connect: true,
            range: {
                'min': <?php echo $config['priceRange']['min']; ?>,
                'max': <?php echo $config['priceRange']['max']; ?>
            },

            step: <?php echo $priceStep; ?>,

            format: {
                to: function (value) {
                    return Math.round(value);
                },
                from: function (value) {
                    return Number(value);
                }
            }
            });

        const handles = priceSlider.querySelectorAll('.noUi-handle');
        handles[0].setAttribute('aria-label', 'Minimum price');
        handles[1].setAttribute('aria-label', 'Maximum price');
            
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
        
        if (selectedType === 'all') {
            // Show all breed categories and checkboxes
            $('.breed-category, .breed-list-container').show();
        } else {
            // Hide all breed categories and containers first
            $('.breed-category, .breed-list-container').hide();
            
            // Then show only the selected pet type's breeds
            $('.breed-category[data-pet-type="' + selectedType + '"]').show();
            $('.breed-list-container[data-pet-type="' + selectedType + '"]').show();
        }
    });
    
    // Initialize pet type filter on page load
    $('input[name="pet_type"]:checked').trigger('change');
});
</script>
<?php
// Close the database connection if it was opened
if ($conn && !$conn->connect_error) {
    $conn->close();
}
?>