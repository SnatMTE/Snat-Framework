# Snat's Framework
Everything here is complied together from websites I have created and re-use for many projects I do. Snat's Framework is a PHP framework that provides commonly used functions for web development.

Installation

To use Snat's Framework, simply include the framework file in your PHP project:

require_once 'snat_framework.php';

# Database functions #
* snat_framework_connect_to_database($host, $username, $password, $database_type): Connect to a database.
* snat_framework_query($connection, $sql): Execute a SQL query and return the result.
* snat_framework_get_last_insert_id($connection): Get the ID of the last inserted row.
* snat_framework_escape_string($connection, $string): Escape a string for use in a SQL query.

# User authentication functions #
* snat_framework_register_user($connection, $username, $password): Register a new user.
* snat_framework_login($connection, $username, $password): Log in a user.

# Image functions #
* snat_framework_create_thumbnail($source_image_path, $output_image_path, $thumbnail_width, $thumbnail_height): Create a thumbnail image from a source image.
* snat_framework_resize_image($source_image_path, $output_image_path, $new_width, $new_height): Resize an image to a specified width and height.
* snat_framework_crop_image($source_image_path, $output_image_path, $crop_width, $crop_height, $start_x, $start_y): Crop an image to a specified width, height, and starting point.
* snat_framework_rotate_image($source_image_path, $output_image_path, $degrees): Rotate an image by a specified number of degrees.
* snat_framework_convert_image($source_image_path, $output_image_path, $output_type): Convert an image to a different file format.

# Audio functions #
* snat_framework_get_duration($audio_file_path): Get the duration of an audio file.
* snat_framework_get_bitrate($audio_file_path): Get the bitrate of an audio file.
