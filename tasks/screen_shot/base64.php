<?php 
$url='https://www.amazon.in/gp/customer-reviews/RQLR264W1G9V3/ref=cm_cr_arp_d_rvw_ttl?ie=UTF8&ASIN=B0BXCZNH3B';
 function get_url_base64($url){
    //takes url of image and return base64 of it 
    $apiUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';

        // API key obtained from Google Cloud Console
        $apiKey = 'AIzaSyAdTwswfLnHbUZFnteAzSvWOZoLK8pDsNM';

        // URL of the web page you want to analyze
        $url = 'https://www.purplle.com/product/ny-bae-3-in-1-primer-foundation-serum-warm-cashew-03-30-ml-82-46-19-91-13';
        $url='https://www.codexworld.com/capture-screenshot-website-url-php-google-api/';



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
        return $base64_image;
 }
 $base64_image=get_url_base64($url);
 echo $base64_image;die;

 
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