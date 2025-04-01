<?php
// This file contains the popup-related functionality

// Function to generate description popups for all listings
function generateDescriptionPopups($conn) {
    // Get filter conditions
    $filterData = buildFilterConditions($conn);
    $whereClause = $filterData['whereClause'];
    $params = $filterData['params'];
    $types = $filterData['types'];
    
    // Get sort order
    $orderBy = getSortOrder();
    
    // Get all listings (no pagination for popups)
    $listingSql = "SELECT * FROM pets $whereClause $orderBy";
    $stmt = $conn->prepare($listingSql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $plainDescription = htmlspecialchars($row['description']);
            
            // Only generate popups for pets with descriptions longer than 110 characters
            if (strlen($plainDescription) > 110) {
                echo '
                <div class="description-popup" id="popup-'.$row['pet_ID'].'">
                    <div class="popup-content">
                        <button class="close-popup">&times;</button>
                        <img src="' . dirname(__DIR__).'/listingImages/'.htmlspecialchars($row['image']) . '" class="popup-card-img" alt="' . htmlspecialchars($row['pet_name']) . '">
                        <div class="popup-card-body">
                            <h3 class="popup-card-title">' . htmlspecialchars($row['pet_name']) . '</h3>
                            <p class="popup-card-text"><strong>Breed:</strong> ' . htmlspecialchars($row['breed']) . '</p>
                            <p class="popup-card-text"><strong>Type:</strong> ' . htmlspecialchars($row['pet_type']) . '</p>
                            <p class="popup-card-text"><strong>Age:</strong> ' . htmlspecialchars($row['age']) . '</p>
                            <p class="popup-card-text"><strong>Gender:</strong> ' . htmlspecialchars($row['gender']) . '</p>
                            <p class="popup-card-text"><strong>Adoption Cost:</strong> $' . htmlspecialchars($row['adopt_cost']) . '</p>
                            <p class="popup-card-text"><strong>Full Description:</strong> ' . nl2br($plainDescription) . '</p>
                            <a href="/pet/' . htmlspecialchars($row['pet_ID']) . '" class="btn btn-primary">Adopt Now</a>
                        </div>
                    </div>
                </div>';
            }
        }
    }
}
?>

<!-- Image Popup HTML -->
<div class="image-popup" style="display: none;">
    <button id="close-popup" style="position: absolute; top: 20px; right: 20px; background: rgba(128, 128, 128, 0.7); border: none; color: white; font-size: 24px; padding: 8px; border-radius: 50%; cursor: pointer; line-height: 1;">&times;</button>
    <img id="popup-image" src="" alt="Popup Image">
</div>

<!-- Description Popups Container -->
<div id="description-popups-container">
    <?php
    // Include this where you want to generate the description popups
    $config = parse_ini_file('/var/www/private/db-config.ini');
    if ($config) {
        $conn = new mysqli(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['dbname']
        );
        
        if (!$conn->connect_error) {
            // Only generate popups if required functions are available
            if (function_exists('buildFilterConditions') && function_exists('getSortOrder')) {
                generateDescriptionPopups($conn);
            }
            
            $conn->close();
        }
    }
    ?>
</div>

<script>
$(document).ready(function() {
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
    
    // Description popup functionality
    $('.show-more-text').click(function() {
        var petId = $(this).data('id');
        $(`#popup-${petId}`).fadeIn();
    });
    
    $('.close-popup').click(function() {
        $(this).closest('.description-popup').fadeOut();
    });
    
    $('.description-popup').click(function(event) {
        if (event.target === this) {
            $(this).fadeOut();
        }
    });
    
    // Close popups with Escape key
    $(document).keydown(function(event) {
        if (event.key === "Escape") {
            $('.image-popup, .description-popup').fadeOut();
        }
    });
});
</script>