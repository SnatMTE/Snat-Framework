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

/**
 * Send an email using the mail() function
 * 
 * @param string $to      The recipient email address
 * @param string $subject The email subject
 * @param string $message The email message
 * @param string $headers Additional headers (optional)
 * 
 * @return bool Returns true if the email was sent successfully, false otherwise
 */
function snat_framework_send_email($to, $subject, $message, $headers = '')
{
    // Additional headers
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

    // Additional headers (optional)
    // $headers .= "From: " . $from . "\r\n";
    // $headers .= "Cc: " . $cc . "\r\n";
    // $headers .= "Bcc: " . $bcc . "\r\n";

    // Send email
    return mail($to, $subject, $message, $headers);
}

if (snat_framework_send_email($to, $subject, $message, $headers)) {
    echo 'Email sent successfully.';
} else {
    echo 'Email could not be sent.';
}
?>
