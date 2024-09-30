<?php
session_start();

// Generate a random CAPTCHA code
$captcha_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
$_SESSION['captcha'] = $captcha_code;

// Create the CAPTCHA image
$width = 150;
$height = 50;
$image = imagecreate($width, $height);

// Define colors
$background_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 0, 0); // Black text
$line_color = imagecolorallocate($image, 64, 64, 64); // Gray lines
$pixel_color = imagecolorallocate($image, 100, 100, 100); // Random pixel color

// Fill the background
imagefilledrectangle($image, 0, 0, $width, $height, $background_color);

// Add random lines to the image for complexity
for ($i = 0; $i < 10; $i++) {
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
}

// Add random dots for additional complexity
for ($i = 0; $i < 1000; $i++) {
    imagesetpixel($image, rand(0, $width), rand(0, $height), $pixel_color);
}

// Add the CAPTCHA code to the image
$font_size = 20;
$font = __DIR__ . '/OpenSans-CondBold.ttf'; // Ensure the font file exists in the same directory as captcha.php
if (!file_exists($font)) {
    die("Font file not found! Please provide a valid .ttf font.");
}
imagettftext($image, $font_size, 0, 15, 35, $text_color, $font, $captcha_code);

// Set the content type and output the image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>