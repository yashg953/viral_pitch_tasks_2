<?php 

//echo $_SERVER['HTTP_HOST'];

//die("oo");

//ini_set('display_errors',0);







//ini_set('display_errors',1);



if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) 

{

   // last request was more than 30 minutes ago

   session_unset();     // unset $_SESSION variable for the run-time

   session_destroy();   // destroy session data in storage

}

$_SESSION['LAST_ACTIVITY'] = time();



   $dbHost = 'localhost';

  $dbUser = 'root';

  $dbPass = '';

  $dbName = 'viral_pitch';



// setting up the web root and server root for

// this shopping cart application



session_cache_expire(30);

session_start();

$timezone = "Asia/Calcutta";

ini_set('max_execution_time', 0);

date_default_timezone_set($timezone);

$dirName='/';

$thisFile = str_replace('\\', '/', __FILE__);

$docRoot = $_SERVER['DOCUMENT_ROOT'].$dirName."blimg/";

$docRoot2 = $_SERVER['DOCUMENT_ROOT'].$dirName."public/";





$superadmindomain='https://'.$_SERVER['HTTP_HOST'].$dirName.'admin/';

$staticPath='https://'.$_SERVER['HTTP_HOST'].$dirName;



define('SUPER_DOMINOS_PATH_NAME',$superadmindomain);

define('SUPER_DOMINOS_PATH_NAME_ADMIN',$superadmindomain.'dashboard/');

define('DOMINOS_PATH',$staticPath.'');

define('INFLUNCER_PATH','https://'.$_SERVER['HTTP_HOST'].$dirName."");

define('STATIC_PATH',$staticPath);

define('UPLOAD_PATH',$docRoot);

define('UPLOAD_PATH1',$docRoot2);

define('TABLE_PREFIX','payment_');

define('STATIC_URL','https://tracking.payoom.com/aff_c?offer_id=');

define('DOMINOS_PATH_NAME',$superadmindomain);







//823 808 7555

$dddatefor=date("Y-m-d H:i:s");

define('DB_DATE_FORMAT', $dddatefor);

$order_date=date("dmy");

define('DB_DATE_FORMAT_ORDER', $order_date);



//$mem = new Memcache();

//$mem->addServer("127.0.0.1", 11211);









require_once 'common.php';

function number_format_short( $n, $precision = 1 ) {

  if ($n < 900) {

    // 0 - 900

    $n_format = number_format($n, $precision);

    $suffix = '';

  } else if ($n < 900000) {

    // 0.9k-850k

    $n_format = number_format($n / 1000, $precision);

    $suffix = 'K';

  } else if ($n < 900000000) {

    // 0.9m-850m

    $n_format = number_format($n / 1000000, $precision);

    $suffix = 'M';

  } else if ($n < 900000000000) {

    // 0.9b-850b

    $n_format = number_format($n / 1000000000, $precision);

    $suffix = 'B';

  } else {

    // 0.9t+

    $n_format = number_format($n / 1000000000000, $precision);

    $suffix = 'T';

  }



  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"

  // Intentionally does not affect partials, eg "1.50" -> "1.50"

  if ( $precision > 0 ) {

    $dotzero = '.' . str_repeat( '0', $precision );

    $n_format = str_replace( $dotzero, '', $n_format );

  }



  return $n_format . $suffix;

}



function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {

    $sort_col = array();

 

    foreach ($arr as $key=> $row) {

        $sort_col[$key] = $row[$col];

    }



     array_multisort($sort_col, $dir, $arr);

    return $arr;

}



function outrsprice($followers_count,$tteng1){



    $amount =1;

  if($followers_count >100000 && $followers_count<1000000){

    $amount =1.25;

  }else if($followers_count >1000000 && $followers_count<3500000){

    $amount =1.5;

  }

  else if($followers_count >3500000 && $followers_count<7000000){

    $amount =2;

  }

  else if($followers_count >7000000 ){

    $amount =3;

  }

  $pprice =  $amount*5;

  $avgpricepr =  number_format_short((($tteng1 * $followers_count*$pprice))/70);

  return $avgpricepr;

}

//$m = new MongoClient();

$domainurl = 'https://'.$_SERVER['HTTP_HOST'].'/';



// select a database

//$db = $m->influncer;



function clean($string) {

   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.



   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

}





 function toprow($arrayuser,$catfilter,$datashortfilter = array(),$categoryarr=array(),$col=4,$col2=3,$imgrs=0){



 

      foreach($arrayuser as $result) {



    $hreflink=DOMINOS_PATH.'profilesc.php?id='. $result['id'] .'&typ='. $result['sc'];

    if(!empty($result['date']) && $result['followers_count']>0 && $result['sc']=='instagram'){

    $cenvertedTime = date('Y-m-d H:i:s',strtotime('-14 hour',strtotime(DB_DATE_FORMAT)));

    $profile = date("Y-m-d h:i:s",strtotime($result['date']));

    if($profile>=$cenvertedTime){

    $hreflink=DOMINOS_PATH.'profile.php?user='.$result['username'].'&'. $result['id'] .'&typ='. $result['sc'];

    }

    }

    $hreflink=DOMINOS_PATH.'profile.php?user='.$result['username'].'&'. $result['id'] .'&typ='. $result['sc'];

        $namesend = $result['username'];

        $urlsenDer = $result['sc'].'/'.$namesend;

        if($result['sc']=='youtube'){

        $namesend = $result['id'];

        $nameUser = clean($result['username']);

        $urlsenDer = $result['sc'].'/'.strtolower($nameUser).'/'.$namesend;

        }

        $hreflink=DOMINOS_PATH.$urlsenDer;

        

 

      $categoryname = explode(",",$result['category']);



     

      $rtydy = array_intersect($categoryname,$categoryarr);



      if(empty($categoryarr) || !empty($rtydy) || $result['sc']!=''){

            $listcat ='';

            $idrest = '';

      if(!empty($categoryname)){

        $categoryname = array_unique($categoryname);

        $mm=0;

        foreach($categoryname as $catnamelist){

          if(!empty($catnamelist) && $mm<4){

          if(!in_array($catnamelist,$datashortfilter)){

          $datashortfilter[] = $catnamelist;

          }



           $listcat.='<span class="inf_cntn"><a href="'.DOMINOS_PATH.'category/'.$catfilter[$catnamelist][2].'/" target="_blank">'.$catfilter[$catnamelist][1].'</a></span>';

          $idrest = empty($idrest) ? $result['sc']."_".strtolower($catfilter[$catnamelist][1]) : $idrest.'_'.$result['sc']."_".strtolower($catfilter[$catnamelist][1]);

          }

          $mm++;

        }

      }

      if(empty($idrest)){

        $idrest = $result['sc'];

      }

$loadImageKey = $result['image'];

       if($result["sc"]=='instagram'){

      $loadImageKey = dateDiffInstaImage(strtotime($result['date']),$result['image'],$result["id"]);

      }



       $listadd.= '<div class="col-12 col-sm-6 col-md-'.$col.' col-lg-'.$col2.' px-2" data-id="'.$result['followers_count'].'" id="take_'. $idrest .'">

    <div class="boxss">

    <div class="boxsec" id="topscroll" data-id="0">

   



     <i class="fab fa-'. $result['sc'] .'"></i>

      <a href="'.$hreflink.'/" target="_blank">';

      if($imgrs==1){

      $listadd.='<div class="boximg btimg" id="topbox_image" data-id="'.$loadImageKey.'" >';

      }else if($imgrs>=2){

      $listadd.='<div class="boximg" > <img  src="'. $loadImageKey .'" class="lazy btimg" alt="'.$result['name'].'"/>';

      }else{

    $listadd.='<div class="boximg" > <img  src=""  data-src="'. $loadImageKey .'" class="lazy btimg" alt="'.$result['name'].'"/>';



}

$engv = $result['eng'];

if($result['eng']<=0 || empty($engv)){

  $engv =0;

}

   

    

      $listadd.='   

    </div>

    </a>

    <div class="boxs-right">

    <i class="fas fa-ellipsis-h"></i>

    </div>

    </div>

    <div class="boxsec">';



    $listadd.="<div class='ld' >".$result['name']."</div>" ;



    if($result['verified']) {

      $listadd.='<i class="fas fa-check-circle"></i>'; 

    }

    $listadd.='<div class="inf_ctg">'.$listcat.'</div>

      

    </div>

    <div class="inf_eng">';

     if($result['followers_count']) {

    $listadd.='<div class="passetss"><span>'. number_format_short($result['followers_count']) .'</span> Followers</div>

    <div class="passetss"><span>'. number_format_short($result['post']) .'</span> Posts</div>

    <div class="passetss" style="border-right:none;"><span>'. number_format($engv,3) .'</span> Eng. Rate</div>';

    

    }else{

      $listadd.='<span class="ld-img_profile" /></span>';

    }

    $id = "'".$result['id']."'";

    $type = "'".$result['sc']."'";

     $name = "'".$result['name']."'";

     $listadd.='<div class="clearfix"></div>

  

    </div>

       

    </div>

  </div>';

      }}

      return array($listadd,$datashortfilter);



  }



function dateDiffInstaImage($date1,$image,$uniquecode ,$m=0)  

{ 

    // Calculating the difference in timestamps 

    $diff = strtotime(DB_DATE_FORMAT) - $date1; 

    $frimk = $image;

      

    // 1 day = 24 hours 

    // 24 * 60 * 60 = 86400 seconds 

    $daycount =  abs(round($diff / 86400)); 

    if($daycount>7 ){

      $imagekey = md5($uniquecode);

      //echo $imagekey."--unq";

      //echo $image."--unqimg";

      $extimagesc = explode("?",$image);

      $extimage = end(explode(".",$extimagesc[0]));

      $imagevalue = $imagekey.'.'.$extimage;

      $image = 'https://storage.googleapis.com/livepitch/'.$imagevalue;



      $picture_name = $image;

      $dfgg = get_headers($picture_name);





      if(strpos($dfgg[0],'404') !== false){

        if($m==1){

         // echo $image

        //$image = $frimk;

      }else{

      $image = '';

    }



      }

      



    }

    return $image;

} 



?>