<?php 
ini_set('display_errors',0);

function checkprice($marketplace, $url)
{
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: "Mozilla/5.0 (Windows; U;  Windows NT 6.0; en-US; rv:1.9.0.15) Gecko/2009101601 Firefox/3.0.15 GTB6 (.NET CLR 3.5.30729) Google"']);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36");

    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    
    $html = curl_exec($ch);
    $html = mb_convert_encoding($html, 'UTF-8');
    $doc = new DOMDocument();
    $doc->loadHTML(mb_convert_encoding($html, "UTF-8","auto"));
    $doc->loadHTML($html);
 
    $xpath = new DOMXPath($doc);  
    if (strtolower($marketplace) == 'amazon') {
        $checkstock = strtolower(preg_replace("/[^\w\s]/", "", $xpath->query('//*[@id="availability"]/span')->item(0)->nodeValue));
        if (strpos($checkstock, 'unavailable') !== false) {
            $stockstatus = 'unavailable';
        } else {
            $stockstatus = 'in stock';
        }

        $data = $xpath->query('//div[3]/div[1]/span[2]/span[2]/span[2]')->item(0)->nodeValue;
        if ($data == '') {
            $data = $xpath->query('//tr[2]/td[2]/span[1]/span[1]')->item(0)->nodeValue;
        }
        if ($data == '') {
            $data = $xpath->query('//div[3]/div[1]/span[1]/span[2]/span[2]')->item(0)->nodeValue;
        }
        if ($data == '') {
            $data = $xpath->query('//div[3]/div[1]/span[1]/span[2]/span[1]')->item(0)->nodeValue;
        }
        $price = preg_replace("/[^\w\s]/", "", explode('.',$data)[0]);
        $array = array('stock' => $stockstatus, 'price' => str_replace("₹", "", $price));
    } else if (strtolower($marketplace) == 'flipkart') {
        $node = array();
        for ($i = 0; $i < 5; $i++) {
            $node[] = $xpath->query('//div[2]/div[2]/div/div[' . $i . ']/div[1]/div/div[1]')->item(0);
        }
        foreach ($node as $node) {
            if ($node) {
                $value = $node->nodeValue;
                if (strpos($value, '₹') !== false) {
                    $price = str_replace(",", "", trim(explode("₹", $value)[1]));
                    $array = array('stock' => 'in stock', 'price' => $price);
                    break;
                }
            }
        }
    } 
    else if(strtolower($marketplace) == 'purplle'){
        $scripts = $doc->getElementsByTagName('script');

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
        

        // Check if "InStock" is present in the input string
        $data = json_decode($element->textContent, true);
        
        // Check if the 'offers' key exists and it's an array
        if (isset($data['offers']) && is_array($data['offers'])) {
            // Access the 'price' key within the 'offers' array
            $price = $data['offers']['price'];
            $instock = $data['offers']['availability'];
            if (strpos($instock, 'InStock') !== false) {
                $array['stock']=1;
            }
            else{
                $array['stock']=0;
            }
            // Output or use the price value
            $array['price'] = $price;
        } 

        
    }
    else if(strtolower($marketplace) == 'myntra'){
        $scripts = $doc->getElementsByTagName('script');

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
        $element = $ldJsonScripts[1];
        
        // Decode the JSON content
        $data = json_decode($element->textContent, true);

        // Check if the 'offers' key exists and it's an array
        if (isset($data['offers']) && is_array($data['offers'])) {
            // Access the 'price' key within the 'offers' array
            $price = $data['offers']['price'];
            $instock = $data['offers']['availability'];
            if($instock=='InStock'){
                $array['stock']=1;
            }else {
                $array['stock']=0;
            }
            // Output or use the price value
            $array['price'] = $price;
            
        }
        
    }
    else {
        $array = array();
    }
    return $array;
}  
$url='https://www.amazon.in/Samsung-Awesome-Graphite-Storage-Gorilla/product-reviews/B0BXCZNH3B/ref=cm_cr_dp_d_show_all_btm?ie=UTF8&reviewerType=all_reviews';
checkprice($marketplace, $url);

?>