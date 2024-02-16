<?php
$url = 'https://www.myntra.com/tshirts/stormborn/stormborn-graphic-printed-drop-shoulder-sleeves-pure-cotton-oversized-t-shirt/25072';
$url='https://fast.com/';

$html = file_get_contents($url); // Fetch HTML content (you may need to use cURL for more complex scenarios)

// Now you can use a tool or browser extension to inspect the elements on the page and generate their XPath expressions.
// For example, if you want to get the XPath of a specific element with id="myElement", you can use a browser extension to do so.

$xpath = '/html/body/div[2]/div/div[7]/div[39]/div/div/div/div/div[2]/div/div[2]/span[1]/div/div/div[3]/div[3]/div/div[1]/div/div/div[1]/a/div[2]/span'; // Paste the XPath expression you obtained here

// Now you can use the XPath to locate the element in your code (e.g., using DOMXPath in PHP)

// Example usage:
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpathObj = new DOMXPath($dom);
$elements = $xpathObj->query($xpath);

foreach ($elements as $element) {
    // Do something with the element
    echo $element->nodeValue;
}
?>