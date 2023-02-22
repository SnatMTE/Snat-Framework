<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is for user stuff.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License
*/

function snat_framework_hash_password($password, $cost = 10)
{
    // Generate a salt using a cryptographically secure pseudo-random number generator
    $salt = sprintf('$2y$%02d$%s', $cost, base64_encode(random_bytes(16)));

    // Hash the password using bcrypt with the generated salt
    $hash = password_hash($password, PASSWORD_BCRYPT, ['salt' => $salt]);

    return $hash;
}

?>