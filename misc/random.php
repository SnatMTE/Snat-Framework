<?php
/**
 * Snat's - Snat's PHP framework of commonly used functions.
 * This part is for randomness related functions.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License

*/ 

function snat_framework_generate_random_string($length) {
    // Define the set of characters to use in the random string.
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    // Initialize an empty string to hold the random characters.
    $random_string = '';

    // Generate a random character for the length of the string.
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $random_string;
}

function snat_framework_generate_unique_id($length) {
    // Define the set of characters to use in the unique ID.
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    // Initialize an empty string to hold the unique ID.
    $unique_id = '';

    // Generate a random character for the length of the string.
    for ($i = 0; $i < $length; $i++) {
        $unique_id .= $chars[rand(0, strlen($chars) - 1)];
    }

    // Append the current timestamp to ensure uniqueness.
    $unique_id .= time();

    return $unique_id;
}


?>