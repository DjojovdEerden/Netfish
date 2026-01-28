<?php
// TMDB API Configuration
define('TMDB_API_KEY', 'YOUR_TMDB_API_KEY'); // Gratis API key van https://www.themoviedb.org/settings/api
define('TMDB_BASE_URL', 'https://api.themoviedb.org/3');
define('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p/w500');
define('TMDB_IMAGE_ORIGINAL', 'https://image.tmdb.org/t/p/original');

// YouTube API Configuration (optioneel voor extra functionaliteit)
define('YOUTUBE_API_KEY', 'YOUR_YOUTUBE_API_KEY'); // Van https://console.developers.google.com/

// Functie om TMDB API call te maken
function tmdbApiCall($endpoint, $params = []) {
    $params['api_key'] = TMDB_API_KEY;
    $url = TMDB_BASE_URL . $endpoint . '?' . http_build_query($params);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Functie om YouTube video's te zoeken
function searchYouTube($query) {
    $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=" . urlencode($query) . "&type=video&key=" . YOUTUBE_API_KEY;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>
