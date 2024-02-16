<?php
ini_set('error_reporting',0);
$url='https://www.flipkart.com/laptop-accessories/keyboards/pr?sid=6bo%2Cai3%2C3oe&sort=popularity&param=33&hpid=d971kH3yw0bIy1mjUhiVF6p7_Hsxr70nj65vMAAFKlc%3D&ctx=eyJjYXJkQ29udGV4dCI6eyJhdHRyaWJ1dGVzIjp7InZhbHVlQ2FsbG91dCI6eyJtdWx0aVZhbHVlZEF0dHJpYnV0ZSI6eyJrZXkiOiJ2YWx1ZUNhbGxvdXQiLCJpbmZlcmVuY2VUeXBlIjoiVkFMVUVfQ0FMTE9VVCIsInZhbHVlcyI6WyJGcm9tIOKCuTIyOSJdLCJ2YWx1ZVR5cGUiOiJNVUxUSV9WQUxVRUQifX0sImhlcm9QaWQiOnsic2luZ2xlVmFsdWVBdHRyaWJ1dGUiOnsia2V5IjoiaGVyb1BpZCIsImluZmVyZW5jZVR5cGUiOiJQSUQiLCJ2YWx1ZSI6IlRLWUdRRjJES1pITVdYUkIiLCJ2YWx1ZVR5cGUiOiJTSU5HTEVfVkFMVUVEIn19LCJ0aXRsZSI6eyJtdWx0aVZhbHVlZEF0dHJpYnV0ZSI6eyJrZXkiOiJ0aXRsZSIsImluZmVyZW5jZVR5cGUiOiJUSVRMRSIsInZhbHVlcyI6WyJUb3AgU2VsbGluZyBEZWxsIEtleWJvYXJkIl0sInZhbHVlVHlwZSI6Ik1VTFRJX1ZBTFVFRCJ9fX19fQ%3D%3D';
$url='https://www.flipkart.com/geonix-gxbk-01-wired-usb-multi-device-keyboard/p/itmb75574aa6f90c?pid=ACCGT52D2JZZGGET&lid=LSTACCGT52D2JZZGGETRHT8NN&marketplace=FLIPKART&store=6bo%2Fai3%2F3oe&srno=b_1_37&otracker=browse&fm=organic&iid=cd079278-bb5b-486e-9679-7476c12d5e20.ACCGT52D2JZZGGET.SEARCH&ppt=browse&ppn=browse&ssid=3c2ugwv44w0000001708066043413';
$url='https://www.amazon.in/Samsung-Awesome-Graphite-Storage-Gorilla/product-reviews/B0BXCZNH3B/ref=cm_cr_unknown?ie=UTF8&reviewerType=avp_only_reviews&pageNumber=1&filterByStar=five_star';
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
    echo 'XPath query failed';
    exit;
}

// Debug XPath results
var_dump($elements);
echo $elements[0]->getElementsByTagName('a')[1]->getAttribute('href');die;
echo $elements[0]->nodeValue;die;
// Output node values
foreach ($elements as $element) {
    echo $element->nodeValue . "<br>";
    die;
}
die();

    $elements = $xpath->query('//*[@id="customer_review-RTYL9L7R0WLDP"]');
    foreach ($elements as $element) {
        echo $element->nodeValue . "<br>";
    }
    die();
    print_r($elements);
    if (!empty($elements)) {
        // Get the href attribute value of the first matching element
        $href = (string) $elements[0]['href'];
        echo "Href: $href";
    } else {
        echo "Element not found.";
    }
    die();
    echo '<pre>';
    print_r($data);
    die();
    if ($data !== false && $data->length > 0) {
        // Extracted data found
        $nodeValue = $data->item(0)->nodeValue;
        echo 'Extracted data: ' . $nodeValue;
    } else {
        // No data found or XPath query failed
        echo 'No data found using XPath expression';
    }
    die;
    ///html/body/div[1]/div[2]/div/div[1]/div/div[1]/div[5]/div[3]/div/div[2]/div/div/div[1]/a/div[2]/span;
    
curl_close($curl);


?>