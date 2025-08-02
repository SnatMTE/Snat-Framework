<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is for general social media stuff.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License
*/

// Ensure Composer's autoloader is included
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Posts a tweet to Twitter using the provided credentials and status message.
 *
 * @param string $consumer_key Twitter API consumer key.
 * @param string $consumer_secret Twitter API consumer secret.
 * @param string $access_token Twitter API access token.
 * @param string $access_token_secret Twitter API access token secret.
 * @param string $status The status message to post as a tweet.
 * @return bool Returns true on success, false on failure.
 */

function snat_framework_post_tweet($consumer_key, $consumer_secret, $access_token, $access_token_secret, $status) {
    require_once('TwitterOAuth/autoload.php');

    // Optionally log the response for debugging
    if ($connection->getLastHttpCode() == 200) {
        // Success, you can log $post if needed
        // error_log(print_r($post, true));
        return true;
    } else {
        // Log error response for debugging
        error_log('Twitter API error: ' . print_r($post, true));
        return false;
    }
    } else {
        return false;
    }
}

function snat_framework_post_to_facebook($access_token, $message, $link = null, $image_url = null) {
    $url = 'https://graph.facebook.com/me/feed';
    $fields = array(
        'message' => $message,
        'access_token' => $access_token
    );
    if ($link !== null) {
        $fields['link'] = $link;
    }
    if ($image_url !== null) {
        $fields['picture'] = $image_url;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result);
    if (isset($response->id)) {
        return true;
    } else {
        return false;
    }
}


?>