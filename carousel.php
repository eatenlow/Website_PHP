<?php
function getOGImage($url)
{
    $html = @file_get_contents($url); // Fetch HTML content

    if ($html === false) {
        error_log("Failed to fetch URL: " . $url); // Log the error
        return 'images/tabby_large.jpg'; // Fallback image
    }

    // Debugging: Log the HTML content fetched
    file_put_contents("debug.html", $html); // Save HTML for debugging

    preg_match('/<meta property="og:image" content="(.*?)"/', $html, $matches);

    if (isset($matches[1])) {
        return $matches[1]; // Return OG image URL
    } else {
        error_log("No OG image found for: " . $url); // Log missing OG images
        return 'images/tabby_large.jpg'; // Fallback
    }
}
