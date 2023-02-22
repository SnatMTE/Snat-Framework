<?php

function snat_framework_submit_soundmouse_track($api_key, $api_secret, $track_data) {
    // Set the endpoint URL for submitting tracks
    $url = 'https://api.soundmouse.com/v1/tracks';

    // Encode the track data as JSON
    $json_data = json_encode($track_data);

    // Generate the required authentication headers
    $date = gmdate('D, d M Y H:i:s T');
    $signature = base64_encode(hash_hmac('sha256', $url . $date . $json_data, $api_secret, true));
    $headers = array(
        'Content-Type: application/json',
        'X-Soundmouse-Api-Key: ' . $api_key,
        'X-Soundmouse-Date: ' . $date,
        'X-Soundmouse-Signature: ' . $signature
    );

    // Set up the CURL request to submit the track
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // Send the request and retrieve the response
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Check for any errors
    if ($http_code != 200) {
        throw new Exception('Error submitting track to Soundmouse: ' . $response);
    }

    // Close the CURL request and return the response
    curl_close($curl);
    return $response;
}

?>