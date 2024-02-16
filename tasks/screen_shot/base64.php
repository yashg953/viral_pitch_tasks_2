<?php 
 
// Google API key 
$apiUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
$key = "AIzaSyAdTwswfLnHbUZFnteAzSvWOZoLK8pDsNM";
// API key obtained from Google Cloud Console
$apiKey = 'AIzaSyAdTwswfLnHbUZFnteAzSvWOZoLK8pDsNM';

// URL of the web page you want to analyze
$url = 'https://www.purplle.com/product/ny-bae-3-in-1-primer-foundation-serum-warm-cashew-03-30-ml-82-46-19-91-13';
$url='https://www.codexworld.com/capture-screenshot-website-url-php-google-api/';
$url='https://www.amazon.in/gp/customer-reviews/RQLR264W1G9V3/ref=cm_cr_arp_d_rvw_ttl?ie=UTF8&ASIN=B0BXCZNH3B';


// Construct the API request URL
$requestUrl = $apiUrl . '?url=' . urlencode($url) . '&key=' . $apiKey . '&screenshot=true';

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// fullPageScreenshot
// Get the screenshot data
$screenshot = $data['lighthouseResult']['fullPageScreenshot']['screenshot']['data'];
$base64_image = $screenshot;
echo $screenshot;die;
// Remove data URI scheme and save the image data
$base64_image = str_replace('data:image/webp;base64,', '', $base64_image);

$base64_image = str_replace(' ', '+', $base64_image);
$image_data = base64_decode($base64_image);

// Create an image resource from the image data
$src_img = imagecreatefromstring($image_data);

// Get the dimensions of the source image
$src_width = imagesx($src_img);
$src_height = imagesy($src_img);

// Set the coordinates and dimensions for the crop
$crop_x = 100; // Starting X coordinate for crop
$crop_y = 100; // Starting Y coordinate for crop
$crop_width = 200; // Width of the crop
$crop_height = 200; // Height of the crop

// Create a new image with the specified dimensions
$dst_img = imagecreatetruecolor($crop_width, $crop_height);

// Perform the crop
imagecopy($dst_img, $src_img, 0, 0, $crop_x, $crop_y, $crop_width, $crop_height);

// Save or output the cropped image
$cropped_image_path = 'cropped_image.jpg';
imagejpeg($dst_img, $cropped_image_path);

// Free up memory
imagedestroy($src_img);
imagedestroy($dst_img);

echo 'Image cropped successfully!';
// Base64 encode the screenshot data
$screenshotData = 'data:image/jpeg;base64,' . $screenshot;
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
    <h1>image is </h1>
    <div style='margin:auto;background-color: Red; width:1200px;height:800px;'>
    <?php echo '<img  src="' . $dst_img . '" />'; ?>
    <!-- <img src="https://pbs.twimg.com/profile_images/1701878932176351232/AlNU3WTK_400x400.jpg" alt="" style=''> -->
    </div>
</body>
</html>