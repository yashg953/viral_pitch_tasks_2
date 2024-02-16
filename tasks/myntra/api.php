<?php
// URL of the web page to scrape
$url='https://www.myntra.com/tshirts/stormborn/stormborn-graphic-printed-drop-shoulder-sleeves-pure-cotton-oversized-t-shirt/25072178/buy';

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session
$html = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Create a DOMDocument instance and load the HTML content
$doc = new DOMDocument();
$doc->loadHTML($html);

// Use DOMXPath to query elements
$xpath = new DOMXPath($doc);

// Extract data using XPath query
$title = $xpath->query('/html/body/div[2]/div/div[1]/main/div[2]/div[2]/div[1]/div/p[1]/span[1]')->item(0)->textContent;

// Print the extracted title
echo "Title: $title";
?>
