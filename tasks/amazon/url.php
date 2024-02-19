<?php 
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


    curl_close($curl);
}
get_data($url);

?>