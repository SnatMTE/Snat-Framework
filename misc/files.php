<?php

function snat_framework_upload_file($file_input_name, $upload_dir) {
  // Check if the file was uploaded without errors
  if(isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === 0){
    // Sanitize the file name to remove any special characters
    $file_name = preg_replace("/[^A-Za-z0-9-_.]/", "", basename($_FILES[$file_input_name]['name']));
    $file_path = $upload_dir . '/' . $file_name;

    // Check if the file already exists in the upload directory
    if (file_exists($file_path)) {
      return false;
    }

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $file_path)) {
      return $file_name;
    }
  }

  // Return false if there was an error uploading the file
  return false;
}

function snat_framework_delete_file($file_name, $upload_dir) {
  $file_path = $upload_dir . '/' . $file_name;
  if (file_exists($file_path)) {
    return unlink($file_path);
  }
  return false;
}

/**
 * Moves a file from one directory to another.
 * 
 * @param string $source The source file.
 * @param string $destination The destination directory.
 * @return bool True if the file was moved successfully, false otherwise.
 */
function snat_framework_move_file($source, $destination) {
    return rename($source, $destination);
}

/**
 * Deletes a file.
 * 
 * @param string $filename The file to delete.
 * @return bool True if the file was deleted successfully, false otherwise.
 */
function snat_framework_delete_file($filename) {
    return unlink($filename);
}

function snat_framework_check_file_permission($filename)
{
    // Check if the file exists
    if (!file_exists($filename)) {
        return false;
    }

    // Check if the file is readable, writable and executable by the current user
    if (!is_readable($filename) || !is_writable($filename) || !is_executable($filename)) {
        return false;
    }

    // File permission check passed
    return true;
}


?>