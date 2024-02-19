<?php
require_once '../library/config_dyn.php';
require_once '../class/query.class.php';
require_once '../class/backlogin.php';
$commonback = new backenduser();
require_once('google-api/vendor/autoload.php');
use Google\Client;
use Google\Service\Drive;   
use Google\Service\Slides;
use Google\Service\Slides\Request;
$url='https://www.amazon.in/Samsung-Awesome-Graphite-Storage-Gorilla/dp/B0BXCZNH3B/ref=sr_1_3?dib=eyJ2IjoiMSJ9.AIfa6ytA5T-sVGtBV52kBLMSrIU7_VbBPDF-2uOQlzsr-lZOo7wGHsPJB59HnWNafud9m1lZePmH6VhoAjZz80h9gb1CMKLo8e4xsQ09g-tEWIXiSZS_WLPxHjO3DE0r8i5BQ3weh6E9fxr6KuB5rpWvJKF2yTbbraDw1WoCYKGnHuey6JdMnx0SkWSj8JMsDoMai19ymnHQOb7J_7RYkVFT7uUXRovh23CZQVVcmys.DMkee8lCTiRjpKA-AHd0QB49MCUBMAia6JC8FEVxiBQ&dib_tag=se&keywords=samsung%2Ba54%2B5g%2Bmobile%2Bphone&qid=1707988428&sr=8-3&th=1';
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
    
        $elements = $xpath->query('//*[@id="landingImage"]');
        if ($elements->length > 0) {
            $imgSrc = $elements[0]->getAttribute('src');
            // Now $imgSrc contains the value of the src attribute of the first matched element
        }
        return $imgSrc;

    curl_close($curl);
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
function insert_details($base64_image,$cmp_name,$img_path,$user_status=0,$img_type,$review_link){
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
        $insert="cmp_name='".$cmp_name."',img_path='".$img_path."',user_status='".$user_status."',insert_status=1,image_type='".$img_type."',review_link='".$review_link."'";
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
    $crop_y = 170; // Starting Y coordinate for crop
    $crop_width = 1340; // Width of the crop
    $crop_height = 600; // height of the crop
    $crop_height = 600; // height of the crop

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
function getrefreshtoken($refreshtoken, $clientid, $client_secret)
{
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=' . $clientid . '&client_secret=' . $client_secret . '&refresh_token=' . $refreshtoken . '&grant_type=refresh_token',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                'Accept: application/json'
            ),
        )
    );
    $response = curl_exec($curl);
    $response = json_decode($response, true);
    curl_close($curl);
    
    return $response;
}
function image_save_link($base64Image){
    $imageData = base64_decode($base64Image);

    // Specify the path where you want to save the image
    $image_name="image".time();
    $filePath = "images/".$image_name.".jpg"; // Change the file extension based on the actual image type

    // Save the binary data to a file
    file_put_contents($filePath, $imageData);
    return $filePath;
}
function emptyFolder($folderPath) {
    // Add a trailing slash if not present
    $folderPath = rtrim($folderPath, '/') . '/';

    // Get all files in the folder
    $files = glob($folderPath . '*');

    // Loop through each file and delete it
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    // Optionally, you can remove the folders as well
    // foreach ($files as $file) {
    //     if (is_dir($file)) {
    //         rmdir($file);
    //     }
    // }

    echo "Folder emptied successfully!";
}
function createNewPresentation($accessToken) {
    $url = "https://slides.googleapis.com/v1/presentations?fields=presentationId";

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken,
    ];

    $data = [
        'title' => 'New Presentation',  // You can set a different title
    ];

    $options = [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => json_encode($data),
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    $result = json_decode($response, true);

    // Check for errors in the API response
    if (isset($result['error'])) {
        echo "API Error: " . print_r($result['error'], true);
        return null;
    }

    return $result['presentationId'];
}
function addSlideToPresentation($accessToken, $presentationId) {
    $url = 'https://slides.googleapis.com/v1/presentations/'.$presentationId.':batchUpdate';

    $headers = [
        'Content-Type:  application/json',
        'Authorization: Bearer ' . $accessToken,
    ];

    $data = [
        'requests' => [
            [
                'createSlide' => [
                    'slideLayoutReference' => [
                        'predefinedLayout' => 'MAIN_POINT',
                    ],
                ],
            ],
        ],  
    ];

    $options = [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => json_encode($data),
    ];
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    $result = json_decode($response, true);

    // Check for errors in the API response
    if (isset($result['error'])) {
        echo "API Error: " . print_r($result['error'], true);
        return null;
    }

    // Return the objectId of the created slide
    return $result['replies'][0]['createSlide']['objectId'];
}
function addTextToSlide($accessToken, $presentationId, $slideObjectId,$a,$color,$short_url) {
    $url = 'https://slides.googleapis.com/v1/presentations/'.$presentationId.':batchUpdate';
    $textboxObjectId = 'textbox_' . uniqid();
    $headerObjectId = 'header_' . uniqid();
    $footerObjectId = 'footer_' . uniqid();
    $imageBase64=file_get_contents('viral_pitch_logo.png');
    $imageBase64=base64_encode($imageBase64);
    $centerImage=file_get_contents('ss.png');
    $centerImage=base64_encode($centerImage);


    $binaryData = base64_decode($imageBase64);
    $compressedData = gzcompress($binaryData);
    $compressedBase64 = base64_encode($compressedData);



    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken,
    ];

    $data = [
        'requests' => [
            [
                'createShape' => [
                    'objectId' => $textboxObjectId,
                    'shapeType' => 'TEXT_BOX',
                    'elementProperties' => [
                        'pageObjectId' => $slideObjectId,
                        'size' => [
                            'height' => [
                                'magnitude' => 200,
                                'unit' => 'PT',
                            ],
                            'width' => [
                                'magnitude' => 500,
                                'unit' => 'PT',
                            ],
                        ],
                        'transform' => [
                            'scaleX' => 1,
                            'scaleY' => 1,
                            'translateX' => 100,
                            'translateY' => 100,
                            'unit' => 'PT',
                        ],
                    ],
                ],
            ],
            [
                'createShape' => [
                    'objectId' => $headerObjectId,
                    'shapeType' => 'TEXT_BOX',
                    'elementProperties' => [
                        'pageObjectId' => $slideObjectId,
                        'size' => [
                            'height' => [
                                "magnitude"=> 2200000,
                                'unit' => 'EMU',
                            ],
                            'width' => [
                                "magnitude"=> 3000000,
                                'unit' => 'EMU',
                            ],
                        ],
                        'transform'=> [
                            'scaleX'=> 2.3672,
                            'scaleY'=> 0.3053,
                            'translateX'=> 48125,
                            'translateY'=> 69625,
                            'unit'=> 'EMU'
                        ],
                    ],
                ],
            ],
            [
                'createShape' => [
                    'objectId' => $footerObjectId,
                    'shapeType' => 'TEXT_BOX',
                    'elementProperties' => [
                        'pageObjectId' => $slideObjectId,
                        'size' => [
                            'height' => [
                                'magnitude' => 3000000,
                                'unit' => 'EMU',
                            ],
                            'width' => [
                                'magnitude' => 3000000,
                                'unit' => 'EMU',
                            ],
                        ],
                        'transform' => [
                            'scaleX' => 2.1841,
                            'scaleY' => 0.1128,
                            'translateX' => 101250,
                            'translateY' => 4706975,
                            'unit' => 'EMU',
                        ],
                    ],
                ],
            ],
            [
                'createImage' => [
                    'url' => 'https://viralpitch.co/demoadmin/Infls/assets/img/logo.png',
                    //'url' => 'https://rilstaticasset.akamaized.net/sites/default/files/2022-09/logo-scroll.png',
                    'elementProperties' => [
                        'pageObjectId' => $slideObjectId,
                        'size' => [
                            'height' => [
                                "magnitude"=> 1450,
                                "unit"=> "EMU",
                            ],
                            'width' => [
                                "magnitude"=> 5525,
                                "unit"=> "EMU",
                            ],
                        ],
                        'transform'=> [
                            'scaleX'=> 283.8914,
                            'scaleY'=> 317,
                            'translateX'=> 7516200.0100000007,
                            'translateY'=> 65025,
                            'unit'=> "EMU"
                        ],
                
                    ],
                ],
            ],
            [
                'createImage' => [
                    'url' => $a['url'],
                    'elementProperties' => [
                        'pageObjectId' => $slideObjectId,
                        'size' => [
                            "width"=> [
                                "magnitude"=> 25000,
                                "unit"=> "EMU"
                            ],
                              "height"=> [
                                "magnitude"=> 16675,
                                "unit"=> "EMU"
                              ]
                        ],
                        "transform"=> [
                            "scaleX"=>  354.08,
                            "scaleY"=> 221.7871,
                            "translateX"=> 147175,
                            "translateY"=> 741325,
                            "unit"=> "EMU"
                        ],
                
                    ],
                ],
            ],
            [
                'createImage' => [
                    'url' => $a['small_link'],
                    'elementProperties' => [
                        'pageObjectId' => $slideObjectId,
                        'size' => [
                            "width"=> [
                                "magnitude"=> 25000,
                                "unit"=> "EMU"
                            ],
                              "height"=> [
                                "magnitude"=> 16675,
                                "unit"=> "EMU"
                              ]
                        ],
                        "transform"=> [
                            "scaleX"=>  40.187,
                            "scaleY"=> 32.8401,
                            "translateX"=> 7994500.005,
                            "translateY"=> 4497770.805,
                            "unit"=> "EMU"
                        ],
                
                    ],
                ],
            ],
            [
                'insertText' => [
                    'objectId' => $headerObjectId,
                    'text' => substr($a['campaign_name'], 0, 182),
                ],
            ],
            [
                'insertText' => [
                    'objectId' => $footerObjectId,
                    'text' => $short_url,
                ],
            ],
            [
                'updateTextStyle' => [
                    'objectId' => $footerObjectId,
                    'textRange' => [
                        'type' => 'ALL',
                    ],
                    'style' => [
                        'bold'=>true,
                        'fontFamily' => 'Arial',  // Set the desired font family
                        'fontSize' => [
                            'magnitude' => 18,      // Set the desired font size
                            'unit' => 'PT',
                        ],
                        'foregroundColor' => [
                            'opaqueColor' => [
                                'rgbColor' => [
                                    'red' => 0.0,       // Set the desired red component of the color
                                    'green' => 0.0,     // Set the desired green component of the color
                                    'blue' => 1.0,      // Set the desired blue component of the color
                                ],
                            ],
                        ],
                        'weightedFontFamily' => [
                            'fontFamily' => 'Arial',
                            'weight' => 700,  // 700 corresponds to bold
                        ],            
                    ],
                    
                    'fields' => 'fontFamily,fontSize,foregroundColor,bold',  // Update only the specified fields
                ],
            ],
            [
                'updateTextStyle' => [
                    'objectId' => $headerObjectId,
                    'textRange' => [
                        'type' => 'ALL',
                    ],
                    'style' => [
                        'bold'=>true,
                        'fontFamily' => 'Arial',  // Set the desired font family
                        'fontSize' => [
                            'magnitude' => 18,      // Set the desired font size
                            'unit' => 'PT',
                        ],
                        'foregroundColor' => [
                            'opaqueColor' => [
                                'rgbColor' => $color,
                            ],
                        ],
                        'weightedFontFamily' => [
                            'fontFamily' => 'Arial',
                            'weight' => 700,  // 700 corresponds to bold
                        ],            
                    ],
                    
                    'fields' => 'fontFamily,fontSize,foregroundColor,bold',  // Update only the specified fields
                ],
            ],
        ],
    ]; 

    $options = [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => json_encode($data),
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    $result = json_decode($response, true);

    // Check for errors in the API response
    if (isset($result['error'])) {
        echo "API Error: " . print_r($result['error'], true);
        return false;
    }

    return true;
}

function unique_id() {
    // Generate 16 random bytes
    $data = random_bytes (16);
    // Set the version to 0100
    $data [6] = chr (ord ($data [6]) & 0x0f | 0x40);
    // Set the variant to 10
    $data [8] = chr (ord ($data [8]) & 0x3f | 0x80);
    // Format the output as hexadecimal groups
    return vsprintf ('%s%s-%s-%s-%s-%s%s%s', str_split (bin2hex ($data), 4));
};
$wherecond = " where domain_name='gmail.com' and id=12";
$nameselect = " user_id,refresh_token ";
$checkduplicate = $commonback->QueryFieldMultipleSelect("token_auth", $wherecond, $nameselect);
$clientid = '487103307087-kluq06hhfk79vn23keajoqvc7t6r0ns4.apps.googleusercontent.com';
$client_secret = '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';
$key = "AIzaSyAdTwswfLnHbUZFnteAzSvWOZoLK8pDsNM";
$refreshToken=$checkduplicate[0]['refresh_token'];
$response = getrefreshtoken($refreshToken, $clientid, $client_secret);
$response['access_token'];
 
$a=json_decode('[
    {
    "url":"https://images.unsplash.com/photo-1575936123452-b67c3203c357?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW1hZ2V8ZW58MHx8MHx8fDA%3D",
    "link":"https://www.apple.com/",
    "small_link":"https://images.unsplash.com/photo-1575936123452-b67c3203c357?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW1hZ2V8ZW58MHx8MHx8fDA%3D",
    "campaign_name":"Cadbury"
},
    {
    "url":"https://buffer.com/cdn-cgi/image/w=1000,fit=contain,q=90,f=auto/library/content/images/size/w1200/2023/10/free-images.jpg",
    "link":"https://www.apple.com/",
    "small_link":"https://buffer.com/cdn-cgi/image/w=1000,fit=contain,q=90,f=auto/library/content/images/size/w1200/2023/10/free-images.jpg",
    "campaign_name":"Cadbury"
}
]',true);



foreach($a as $b){
    $where=" where campaign_name='".$b['campaign_name']."'";
$presentation=$commonback->QueryFieldMultipleSelect('presentation',$where,'presentation_id');  
    if(empty($presentation)){
        $color=[
            'red' => 0.0,       // Set the desired red component of the color
            'green' => 0.0,     // Set the desired green component of the color
            'blue' => 0.0,      // Set the desired blue component of the color
        ];
        $presentationId = createNewPresentation($response['access_token']);
        $table_set=" presentation_id='".$presentationId."',campaign_name='".$b['campaign_name']."'";
        $message = "not inserted";
        $commonback->Queryinsert('presentation',$table_set,$message);
        $object=addSlideToPresentation($response['access_token'], $presentationId);
        $base64=get_url_base64($url);
        $crop_base64=crop_base64_img($base64);
        
        // Base64-encoded image data
        
        // Decode base64 data
        $image_data = base64_decode($crop_base64);
    
        // Create image resource from data
        $image = imagecreatefromstring($image_data);
    
        if ($image !== false) {
            // Set the path for saving the JPG file
            $file_path = "images/".time().".jpg"; // Change the path as needed
            
            // Save the image as JPG
            imagejpeg($image, $file_path);
            $small_img=get_data($url);
    
            // Free up memory
            imagedestroy($image);
            $table='deals_reviews';
            $insert="cmp_name='".$cmp_name."',img_path='".$img_path."',insert_status=1,image_type='main'";
            $commonback->Queryinsert($table,$insert,'sdf');
            $table='deals_reviews';
            $insert="cmp_name='".$cmp_name."',img_path='".$small_img."',insert_status=1,image_type='small_image'";
            $commonback->Queryinsert($table,$insert,'sdf');
        
        addTextToSlide($response['access_token'], $presentationId, $object,$b,$color,$short_url);
        
        
    }else{
        $color=[
            'red' => 1.0,       // Set the desired red component of the color
            'green' => 0.0,     // Set the desired green component of the color
            'blue' => 0.0,      // Set the desired blue component of the color
        ];
        $color=[
            'red' => 0.0,       // Set the desired red component of the color
            'green' => 0.0,     // Set the desired green component of the color
            'blue' => 0.0,      // Set the desired blue component of the color
        ];

        $object=addSlideToPresentation($response['access_token'], $presentation[0]['presentation_id']);
        addTextToSlide($response['access_token'], $presentation[0]['presentation_id'], $object,$b,$color,$short_url);
    }
}

?>