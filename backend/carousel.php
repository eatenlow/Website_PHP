<?php
function getOGImage($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

    $html = curl_exec($ch);
    curl_close($ch);

    if ($html === false) {
        return 'images/calico_large.jpg';
    }

    // Save fetched HTML to debug file
    file_put_contents('debug_last_fetched.html', $html);

    // Improved regex for extracting og:image
    preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $matches);
    if (!empty($matches[1])) {
        return $matches[1]; // Return OG image if found
    }

    // If no OG image, try extracting first <img> tag
    preg_match('/<img.*?src=["\'](.*?)["\']/', $html, $imgMatches);
    if (!empty($imgMatches[1])) {
        return $imgMatches[1]; // Return first image found
    }

    // Final fallback
    return 'images/calico_large.jpg';
}
