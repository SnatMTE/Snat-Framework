<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is 2checkout.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License
*/
function snat_framework_2co_create_sale($params, $seller_id, $secret_key) {
    // Set up the API URL and headers
    $url = "https://api.2checkout.com/rest/6.0/sales/";
    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode("$seller_id:$secret_key")
    );

    // Make the API call
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Check the API response
    $result = json_decode($response, true);
    if (isset($result['sale'])) {
        return $result['sale'];
    } else {
        return false;
    }
}

function snat_framework_2co_get_sale($sale_id, $seller_id, $secret_key) {
    // Set up the API URL and headers
    $url = "https://api.2checkout.com/rest/6.0/sales/$sale_id";
    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode("$seller_id:$secret_key")
    );

    // Make the API call
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Check the API response
    $result = json_decode($response, true);
    if (isset($result['sale'])) {
        return $result['sale'];
    } else {
        return false;
    }
}

function snat_framework_2co_create_subscription($sid, $private_key, $product_id, $subscription_data, $coupon_code = '') {
    // Build the request parameters
    $params = array(
        'sid' => $sid,
        'mode' => '2CO',
        'li_0_product_id' => $product_id,
        'card_holder_name' => $subscription_data['name'],
        'street_address' => $subscription_data['address'],
        'city' => $subscription_data['city'],
        'state' => $subscription_data['state'],
        'zip' => $subscription_data['zip'],
        'country' => $subscription_data['country'],
        'email' => $subscription_data['email'],
        'phone' => $subscription_data['phone'],
        'currency_code' => $subscription_data['currency'],
        'quantity' => 1,
        'price' => $subscription_data['price'],
        'interval_unit' => $subscription_data['interval_unit'],
        'interval_length' => $subscription_data['interval_length'],
        'duration' => $subscription_data['duration'],
        'startup_fee' => $subscription_data['startup_fee'],
        'tangible' => 'N',
        'lang' => 'en',
        'skip_landing' => 1,
    );

    if (!empty($coupon_code)) {
        $params['coupon'] = $coupon_code;
    }

    // Build the request URL
    $url = 'https://secure.2checkout.com/order/checkout.php?' . http_build_query($params);

    // Make the API request
    $response = file_get_contents($url);

    // Parse the response
    $response_params = array();
    parse_str($response, $response_params);

    // Check if the API call was successful
    if ($response_params['response_code'] == 'Success') {
        // Return the 2Checkout order number
        return $response_params['order_number'];
    } else {
        // Throw an exception with the error message
        throw new Exception($response_params['response_message']);
    }
}

?>