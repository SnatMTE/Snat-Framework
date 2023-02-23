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

function snat_framework_post_tweet($consumer_key, $consumer_secret, $access_token, $access_token_secret, $status) {
    require_once('TwitterOAuth/autoload.php');

    $connection = new Abraham\TwitterOAuth\TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    $post = $connection->post('statuses/update', array('status' => $status));

    if ($connection->getLastHttpCode() == 200) {
        return true;
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