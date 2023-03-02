<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is for coinbase stuff.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License
*/

function snat_framework_coinbase_create_checkout($api_key, $api_secret, $name, $description, $amount, $currency, $redirect_url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.coinbase.com/v2/checkouts');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
        'name' => $name,
        'description' => $description,
        'pricing_type' => 'fixed_price',
        'local_price' => array(
            'amount' => $amount,
            'currency' => $currency
        ),
        'redirect_url' => $redirect_url
    )));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['data']['id'];
}

function snat_framework_coinbase_get_checkout($api_key, $api_secret, $checkout_id) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.coinbase.com/v2/checkouts/' . $checkout_id);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['data'];
}

function snat_framework_coinbase_create_charge($api_key, $api_secret, $name, $description, $amount, $currency) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.coinbase.com/v2/charges');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
        'name' => $name,
        'description' => $description,
        'pricing_type' => 'fixed_price',
        'local_price' => array(
            'amount' => $amount,
            'currency' => $currency
        )
    )));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['data']['id'];
}

function snat_framework_coinbase_get_charge($api_key, $api_secret, $charge_id) {
    $url = "https://api.coinbase.com/v2/charges/$charge_id";
    $headers = array(
      "Content-Type: application/json",
      "Authorization: Bearer $api_key",
      "CB-VERSION: 2022-01-19"
    );
  
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  
    // Set up HMAC signature for authentication
    $timestamp = time();
    $message = "$timestamp" . "GET" . "/v2/charges/$charge_id";
    $signature = hash_hmac('sha256', $message, $api_secret);
    $auth_header = "CB-ACCESS-SIGN: $signature\r\n" .
                   "CB-ACCESS-TIMESTAMP: $timestamp\r\n" .
                   "CB-ACCESS-KEY: $api_key\r\n";
  
    curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, array($auth_header)));
  
    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
  
    if ($http_status == 200) {
      $json_response = json_decode($response, true);
      return $json_response['data'];
    } else {
      return null;
    }
  }
  

?>