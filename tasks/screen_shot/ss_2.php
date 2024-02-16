<?php
// require_once '../library/config_dyn.php';
// require_once '../class/query.class.php';
// require_once '../class/backlogin.php';
// $commonback = new backenduser();
// require_once 'google-api/vendor/autoload.php';
// function getrefreshtoken($refreshtoken, $clientid, $client_secret)
// {
//     $curl = curl_init();
//     curl_setopt_array(
//         $curl,
//         array(
//             CURLOPT_URL => 'https://oauth2.googleapis.com/token',
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'POST',
//             CURLOPT_POSTFIELDS => 'client_id=' . $clientid . '&client_secret=' . $client_secret . '&refresh_token=' . $refreshtoken . '&grant_type=refresh_token',
//             CURLOPT_HTTPHEADER => array(
//                 "Content-Type: application/x-www-form-urlencoded",
//                 'Accept: application/json',
//             ),
//         )
//     );
//     $response = curl_exec($curl);
//     $response = json_decode($response, true);
//     curl_close($curl);

//     return $response;
// }

// $wherecond = " where domain_name='gmail.com' and id=12";
// $nameselect = " user_id,refresh_token ";
// $checkduplicate = $commonback->QueryFieldMultipleSelect("token_auth", $wherecond, $nameselect);
// $clientid = '487103307087-kluq06hhfk79vn23keajoqvc7t6r0ns4.apps.googleusercontent.com';
// $client_secret = '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';
// $key = "AIzaSyAdTwswfLnHbUZFnteAzSvWOZoLK8pDsNM";
// $refreshToken = $checkduplicate[0]['refresh_token'];
// $response = getrefreshtoken($refreshToken, $clientid, $client_secret);


// Website url
$siteURL = "https://www.myntra.com/bath-robes";

// Google API key
// $googleApiKey = $key;

// Call Google PageSpeed Insights API
$googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$siteURL&screenshot=true");

// Decode json data
$googlePagespeedData = json_decode($googlePagespeedData, true);

// Retrieve screenshot image data
$screenshot_data = $googlePagespeedData['lighthouseResult']['audits']['final-screenshot']['details']['data'];

// Display screenshot image

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
    <div style='margin:auto' width=300px>
    <?php echo '<img style="margin:auto;" src="' . $screenshot_data . '" />'; ?>
    </div>
</body>
</html>