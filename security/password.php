<?php

/**
 * Snat's - Snat's PHP framework of commonly used functions.
 * This part is for password related functions.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License

*/ 

function snat_framework_generate_password($length) {
    // Define the set of characters to use in the password.
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}[]|;:<>,.?/';

    // Initialize an empty string to hold the password.
    $password = '';

    // Generate a random character for the length of the string.
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $password;
}

function snat_framework_encrypt($string, $key) {
    // Generate a random initialization vector.
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

    // Encrypt the string using the AES-256-CBC encryption algorithm.
    $encrypted_string = openssl_encrypt($string, 'aes-256-cbc', $key, 0, $iv);

    // Concatenate the initialization vector and the encrypted string.
    $result = base64_encode($iv . $encrypted_string);

    return $result;
}

function snat_framework_decrypt($string, $key) {
    // Decode the base64-encoded string.
    $data = base64_decode($string);

    // Get the length of the initialization vector.
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');

    // Extract the initialization vector and the encrypted string.
    $iv = substr($data, 0, $iv_length);
    $encrypted_string = substr($data, $iv_length);

    // Decrypt the string using the AES-256-CBC encryption algorithm.
    $decrypted_string = openssl_decrypt($encrypted_string, 'aes-256-cbc', $key, 0, $iv);

    return $decrypted_string;
}


?>