<?php
require './vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

// Data to be encoded in the QR code
function qr($data): void
{

// Set QR code options
$options = new QROptions([
    'eccLevel' => QRCode::ECC_L,    // Error correction level
    'outputType' => QRCode::OUTPUT_IMAGE_PNG, // Output type
    'imageBase64' => true,         // Output as base64
]);

// Create a new QRCode instance
$qrcode = new QRCode($options);

// Generate the QR code image
$image = $qrcode->render($data);

// Output the image to be used in img
header('Content-Type: image/png');
echo $image;
}
