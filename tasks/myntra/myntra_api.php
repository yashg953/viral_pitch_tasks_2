<?php
require_once '../../library/config_dyn.php';
require_once '../../class/query.class.php';
require_once '../../class/backlogin.php';
$commonback = new backenduser();
$url='https://www.myntra.com/tshirts/stormborn/stormborn-graphic-printed-drop-shoulder-sleeves-pure-cotton-oversized-t-shirt/25072178/buy';
function myntra_price($url){
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
      CURLOPT_HTTPHEADER => array(
        'Cookie: _abck=1E00EF18DC073F76D4CCF400098B11FC~-1~YAAQfc8uF0LBdX+NAQAA9QO9ggsQNhWV7m1mAdLoK2icuhApg9Ij9vBp2HJo6OGOn58f6zxphyFBV23GEAjmuKRiVb9yiJ1/9c1ZWtlI+mn38RWUX0xLYUyUz1q12y2LQlfcspj4aRMWp5B55GOcwlxb/OnvZyU0EzTLTT3COIU/FaJxmSKhomD1YVj6Hb37gRuSjaJnp7Oh/JuStMNl6zaALS/IUgy+rG6NmWH1jI/yP8MlaBTKhQlXVcCQU/rLGn0TMkGfOVj7mqx0qRrgpRplGdyPJiISHU7AMe5e4ao6c4zIrSb3/H5Hg9295wZut9teEBtWqJIqDOxOlk3d/NMPpXlT2ndLHo22LJUwwJ+p5i5lYZvNZ28=~-1~-1~-1; _d_id=12b1bc87-2156-45d8-8860-567905553309; _mxab_=; _pv=default; at=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJbXRwWkNJNklqRWlMQ0owZVhBaU9pSktWMVFpZlEuZXlKdWFXUjRJam9pTUdGa05qUTNZalV0WXpVNU5TMHhNV1ZsTFRrMU1URXRaVFpsTURrME1qSmtOR00ySWl3aVkybGtlQ0k2SW0xNWJuUnlZUzB3TW1RM1pHVmpOUzA0WVRBd0xUUmpOelF0T1dObU55MDVaRFl5WkdKbFlUVmxOakVpTENKaGNIQk9ZVzFsSWpvaWJYbHVkSEpoSWl3aWMzUnZjbVZKWkNJNklqSXlPVGNpTENKbGVIQWlPakUzTWpJNE5EYzBOREVzSW1semN5STZJa2xFUlVFaWZRLm9kQzBCbEh1eThaM0dkMGo0YndVZlFIN0RaX1ZlOUszRVlvak5iVU5xWWM=; bc=true; bm_sz=42A032D14C37688B6490CAA42BB5A4BA~YAAQfc8uF0PBdX+NAQAA9QO9ghbR5ADbczUS13compUyY7mvpkIy/Hc8/pP/ilpZlyrkmc3gSZyzCvmVA52g693qCsJhlPmWSFv7dcPNoYbcAntbwJLRKjclMyppKPN7xIdY13POzN33srpODIGUn8Sh1of+AYf34tPf09eX7M023pujYEVYuLbskG31NNOL9A2MSQCAFb+ae66qABIqIqXRs4r488Hup03W8j8Bs8BLJwKw4G2zjRluzGg2NFYw3czX3FcWs3TOOvkfc6AV0zhMO6kpGDPUmqnif2m9nQNtTCx8zV32t3BsKU2GKqZ5oYJJiAHrTTWJH3CZXLxm7Q==~3490099~3359025; dp=d; lt_session=1; lt_timeout=1; utm_track_v1=%7B%22utm_source%22%3A%22direct%22%2C%22utm_medium%22%3A%22direct%22%2C%22trackstart%22%3A1707295441%2C%22trackend%22%3A1707295501%7D; utrid=C2BYQHwGVkRBXVB0DQBAMCMxMzA2ODY0NjYwJDI%3D.d466e837f7a17e8bf970979fb7c90b4f'
      ),
    ));
    
    $response = curl_exec($curl);
    $arr=(explode("<script>",$response));
    
    echo "<pre>";
    print_r($arr[0]);
    echo "</pre>";die;
    curl_close($curl);
    echo $response;
}
myntra_price($url);
?>
