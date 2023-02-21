<?php
/**
 * Snat's - Snat's PHP framework of commonly used functions.
 * This part is for all images related functions.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License

*/ 

function snat_framework_create_thumbnail($source_image_path, $output_image_path, $thumbnail_width, $thumbnail_height) {
    list($width, $height) = getimagesize($source_image_path);
    $image_type = exif_imagetype($source_image_path);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_image_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_image_path);
            break;
        default:
            return false;
    }
    $thumbnail_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
    imagecopyresampled($thumbnail_image, $source_image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width, $height);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            return imagejpeg($thumbnail_image, $output_image_path, 100);
        case IMAGETYPE_PNG:
            return imagepng($thumbnail_image, $output_image_path, 9);
        case IMAGETYPE_GIF:
            return imagegif($thumbnail_image, $output_image_path);
        default:
            return false;
    }
}

function snat_framework_resize_image($source_image_path, $output_image_path, $new_width, $new_height) {
    list($width, $height) = getimagesize($source_image_path);
    $image_type = exif_imagetype($source_image_path);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_image_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_image_path);
            break;
        default:
            return false;
    }
    $new_image = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            return imagejpeg($new_image, $output_image_path, 100);
        case IMAGETYPE_PNG:
            return imagepng($new_image, $output_image_path, 9);
        case IMAGETYPE_GIF:
            return imagegif($new_image, $output_image_path);
        default:
            return false;
    }


function snat_framework_crop_image($source_image_path, $output_image_path, $crop_x, $crop_y, $crop_width, $crop_height) {
    list($width, $height) = getimagesize($source_image_path);
    $image_type = exif_imagetype($source_image_path);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_image_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_image_path);
            break;
        default:
            return false;
    }
    $cropped_image = imagecrop($source_image, ['x' => $crop_x, 'y' => $crop_y, 'width' => $crop_width, 'height' => $crop_height]);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            return imagejpeg($cropped_image, $output_image_path, 100);
        case IMAGETYPE_PNG:
            return imagepng($cropped_image, $output_image_path, 9);
        case IMAGETYPE_GIF:
            return imagegif($cropped_image, $output_image_path);
        default:
            return false;
    }
}

function snat_framework_rotate_image($source_image_path, $output_image_path, $angle) {
    list($width, $height) = getimagesize($source_image_path);
    $image_type = exif_imagetype($source_image_path);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_image_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_image_path);
            break;
        default:
            return false;
    }
    $rotated_image = imagerotate($source_image, $angle, 0);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            return imagejpeg($rotated_image, $output_image_path, 100);
        case IMAGETYPE_PNG:
            return imagepng($rotated_image, $output_image_path, 9);
        case IMAGETYPE_GIF:
            return imagegif($rotated_image, $output_image_path);
        default:
            return false;
    }
}

function snat_framework_convert_image($source_image_path, $output_image_path, $output_image_type, $quality = 100) {
    list($width, $height) = getimagesize($source_image_path);
    $image_type = exif_imagetype($source_image_path);
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_image_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_image_path);
            break;
        default:
            return false;
    }
    switch (strtolower($output_image_type)) {
        case 'jpeg':
        case 'jpg':
            return imagejpeg($source_image, $output_image_path, $quality);
        case 'png':
            return imagepng($source_image, $output_image_path, 9);
        case 'gif':
            return imagegif($source_image, $output_image_path);
        default:
            return false;
    }
}
    
function snat_framework_add_watermark($src_path, $dst_path, $watermark_path, $x, $y) {
    // Create a new image resource based on the type of the source image.
    $src_image = null;
    switch (exif_imagetype($src_path)) {
        case IMAGETYPE_JPEG:
            $src_image = imagecreatefromjpeg($src_path);
            break;
        case IMAGETYPE_PNG:
            $src_image = imagecreatefrompng($src_path);
            break;
        case IMAGETYPE_GIF:
            $src_image = imagecreatefromgif($src_path);
            break;
        default:
            throw new InvalidArgumentException('Unsupported image type');
    }

    // Create a new image resource based on the type of the watermark image.
    $watermark_image = null;
    switch (exif_imagetype($watermark_path)) {
        case IMAGETYPE_JPEG:
            $watermark_image = imagecreatefromjpeg($watermark_path);
            break;
        case IMAGETYPE_PNG:
            $watermark_image = imagecreatefrompng($watermark_path);
            break;
        case IMAGETYPE_GIF:
            $watermark_image = imagecreatefromgif($watermark_path);
            break;
        default:
            throw new InvalidArgumentException('Unsupported image type');
    }

    // Get the dimensions of the watermark image.
    $watermark_width = imagesx($watermark_image);
    $watermark_height = imagesy($watermark_image);

    // Copy the watermark onto the source image.
    imagecopy($src_image, $watermark_image, $x, $y, 0, 0, $watermark_width, $watermark_height);

    // Save the output image to the specified path.
    imagepng($src_image, $dst_path);

    // Free up memory by destroying the image resources.
    imagedestroy($src_image);
    imagedestroy($watermark_image);
}

function snat_framework_apply_filter($src_path, $dst_path, $filter_name) {
    // Create a new image resource based on the type of the source image.
    $src_image = null;
    switch (exif_imagetype($src_path)) {
        case IMAGETYPE_JPEG:
            $src_image = imagecreatefromjpeg($src_path);
            break;
        case IMAGETYPE_PNG:
            $src_image = imagecreatefrompng($src_path);
            break;
        case IMAGETYPE_GIF:
            $src_image = imagecreatefromgif($src_path);
            break;
        default:
            throw new InvalidArgumentException('Unsupported image type');
    }
  
    // Apply the specified filter to the source image.
    switch ($filter_name) {
        case 'grayscale':
            imagefilter($src_image, IMG_FILTER_GRAYSCALE);
            break;
        case 'sepia':
            imagefilter($src_image, IMG_FILTER_GRAYSCALE);
            imagefilter($src_image, IMG_FILTER_COLORIZE, 90, 60, 30);
            break;
        case 'negative':
            imagefilter($src_image, IMG_FILTER_NEGATE);
            break;
        default:
            throw new InvalidArgumentException('Unsupported filter');
    }
  
    // Save the output image to the specified path.
    imagepng($src_image, $dst_path);
  
    // Free up memory by destroying the image resources.
    imagedestroy($src_image);
  }

function snat_framework_get_gravatar_url($email, $size = 80, $default = 'mp', $rating = 'g') {
    // Hash the email address using MD5.
    $hashed_email = md5(strtolower(trim($email)));

    // Construct the URL for the Gravatar image.
    $url = "https://www.gravatar.com/avatar/{$hashed_email}?s={$size}&d={$default}&r={$rating}";

    return $url;
}


?>