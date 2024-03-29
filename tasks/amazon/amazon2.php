<?php
ini_set('error_reporting',0);
ini_set('max_execution_time', 120);
require_once '../../library/config_dyn.php';
require_once '../../class/query.class.php';
require_once '../../class/backlogin.php';
$commonback = new backenduser();
$url='https://www.amazon.in/Samsung-Awesome-Graphite-Storage-Gorilla/dp/B0BXCZNH3B/ref=sr_1_3?dib=eyJ2IjoiMSJ9.AIfa6ytA5T-sVGtBV52kBLMSrIU7_VbBPDF-2uOQlzsr-lZOo7wGHsPJB59HnWNafud9m1lZePmH6VhoAjZz80h9gb1CMKLo8e4xsQ09g-tEWIXiSZS_WLPxHjO3DE0r8i5BQ3weh6E9fxr6KuB5rpWvJKF2yTbbraDw1WoCYKGnHuey6JdMnx0SkWSj8JMsDoMai19ymnHQOb7J_7RYkVFT7uUXRovh23CZQVVcmys.DMkee8lCTiRjpKA-AHd0QB49MCUBMAia6JC8FEVxiBQ&dib_tag=se&keywords=samsung%2Ba54%2B5g%2Bmobile%2Bphone&qid=1707988428&sr=8-3&th=1';
function check_unique_link($url){
    $commonback = new backenduser();
    $table="deals_review";
    $select='review_lnk';
    $where=" where review_lnk='".$url."'";
    $result=$commonback->QueryFieldMultipleSelect($table,$where,$select);
    if(empty($result)){
        return 1;
    }else{
        return 0;
    }
}

function get_url_base64($url){
    
    //takes url of image and return base64 of it 
    $apiUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';

        // API key obtained from Google Cloud Console
        $apiKey = 'AIzaSyAdTwswfLnHbUZFnteAzSvWOZoLK8pDsNM';

        // URL of the web page you want to analyze  

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
        curl_close($ch);
}
function insert_details($base64_image,$cmp_name,$user_status=0,$img_type,$review_link){
    $commonback = new backenduser();
    // Base64-encoded image data
    
    // Decode base64 data
    $image_data = base64_decode($base64_image);

    // Create image resource from data
    $image = imagecreatefromstring($image_data);

    if ($image !== false) {
        // Set the path for saving the JPG file
        $file_path = "images/".time().".jpg"; // Change the path as needed
        
        // Save the image as JPG
        imagejpeg($image, $file_path);

        // Free up memory
        imagedestroy($image);
        $table='deals_review';
        $insert="cmp_name='".$cmp_name."',img_path='".$file_path."',user_status='".$user_status."',insert_status=1,image_type='".$img_type."',review_link='".$review_link."'";
        $commonback->Queryinsert($table,$insert,'sdf');
    } 
}

function crop_base64_img($base64_image){
        // this will take base64 image and return croped base 64 image
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
    $crop_x = 10; // Starting X coordinate for crop
    $crop_y = 150 ; // Starting Y coordinate for crop
    $crop_width = 1020; // Width of the crop
    $crop_height = 400; // Height of the crop

    // Create a new image with the specified dimensions
    $dst_img = imagecreatetruecolor($crop_width, $crop_height);

    // Perform the crop
    imagecopy($dst_img, $src_img, 0, 0, $crop_x, $crop_y, $crop_width, $crop_height);

    // Save or output the cropped image
    ob_start(); // Start output buffering
    imagejpeg($dst_img, null);
    $cropped_image_data = ob_get_clean(); // Capture the output buffer contents and end output buffering

    // Convert the cropped image data to base64
    $cropped_image_base64 = 'data:image/jpeg;base64,' . base64_encode($cropped_image_data);

    // Output the cropped image base64 data
    $cropped_image_base64=$cropped_image_base64;

    return $cropped_image_base64;
}

function get_all_reviewlink($url){
    
    $path=explode('/',$url);
    $allreview=$path[0]."//".$path[1]."/".$path[2]."/product-reviews/".$path[5]."/ref=cm_cr_dp_d_show_all_btm?ie=UTF8&reviewerType=all_reviews";
    return $allreview;
}

function get_data($url){
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',   
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      
    ));
    
    $html = curl_exec($curl);
    $html = mb_convert_encoding($html, 'UTF-8');
    
        $doc = new DOMDocument();
        $doc->loadHTML(mb_convert_encoding($html, "UTF-8","auto"));
        $doc->loadHTML($html);   
        $xpath = new DOMXPath($doc);
    
        $elements = $xpath->query("//div[@data-hook='review']");
        
    // Check for errors in XPath query
    if ($elements === false) {
        
        // echo 'XPath query failed';
        exit;
    }
    
    // Debug XPath results
    // Output node values
    foreach ($elements as $element) {
        
        $reviewr_name=$element->getElementsByTagName('a')[1]->nodeValue."<br>";
        $link=$element->getElementsByTagName('a')[1]->getAttribute('href')."<br>";
        $link="https://www.amazon.in".$link;
        
        if(check_unique_link($link)){
            
            $base64=get_url_base64($link);
            
            $crop_base64=crop_base64_img($base64);
            $crop_base64=str_replace('data:image/jpeg;base64,', '', $crop_base64);
            $cmp_name='cadbury';
            $user_status=0;
            insert_details($crop_base64,$cmp_name,$user_status,'review',$link);
            echo $base64."<br>";
        }else{
            //
        }

    }
    

    curl_close($curl);
}
get_data($url);




?>