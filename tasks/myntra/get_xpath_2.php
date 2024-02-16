<?php

// URL of the website you want to fetch
$url='https://www.myntra.com/jeans/h%26m/hm-women-blue-skinny-high-jeans/17575912/buy';
$url='https://www.myntra.com/towel-set/rangoli/rangoli-unisex-set-of-4-beige-appliqued-450-gsm-towels-/13650748/buy';
$url='https://www.myntra.com/face-wash-and-cleanser/biotique/biotique-pure--natural-fruit-brightening-face-wash---200-ml/9314855/buy';
$url='https://www.myntra.com/flats/anouk/anouk-beige-ethnic-embellished-open-toe-flats/24670018/buy';
$url='https://www.myntra.com/bath-rugs/nautica/nautica-brown-solid-2800-gsm-bath-rugs/19551480/buy';
$url='https://www.purplle.com/product/lakme-peach-milk-intense-moisturizer-lotion-120-ml-1';
$url='https://www.purplle.com/product/faces-canada-weightless-matte-finish-lipstick-suede-brown-p07-4-g';







// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt($curl, CURLOPT_URL, $url); // Set the URL to fetch
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification (for simplicity)


// Execute cURL session
$response = curl_exec($curl);

// Check for errors
if(curl_errno($curl)){
    echo 'Curl error: ' . curl_error($curl);
}

// Close cURL session
curl_close($curl);

// Initialize DOMDocument
$dom = new DOMDocument();

// Load HTML content into DOMDocument
$dom->loadHTML($response);

// Get all script tags
$scripts = $dom->getElementsByTagName('script');

// Initialize an empty array to store scripts with type 'application/ld+json'
$ldJsonScripts = [];

// Iterate through each script element
foreach ($scripts as $script) {
    // Check if the script element has a type attribute
    if ($script->hasAttribute('type')) {
        // Get the value of the type attribute
        $type = $script->getAttribute('type');
        // Check if the type is 'application/ld+json'
        if ($type === 'application/ld+json') {
            // Add the script element to the array
            $ldJsonScripts[] = $script;
        }
    }
}
$element = $ldJsonScripts[0];

// Decode the JSON content
$data = json_decode($element->textContent, true);

// Check if the 'offers' key exists and it's an array
if (isset($data['offers']) && is_array($data['offers'])) {
    // Access the 'price' key within the 'offers' array
    $price = $data['offers']['price'];
    // Output or use the price value
    echo $price;
} else {
    echo "Price not found";
}


?>
