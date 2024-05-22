<?php
include 'qrlib.php'; // Include QR library

// Generate QR code data (you can customize this as needed)
$text = "google.com";

// Generate QR code image
ob_start(); // Start output buffering
QRcode::png($text); // Generate QR code
$imageData = ob_get_contents(); // Get generated image data
ob_end_clean(); // Clean output buffer

// Display QR code image
header('Content-Type: image/png');
echo $imageData;
?>

