<?php 


function checkRequiredPost($requiredField) {
	$numRequired = count($requiredField);
	$keys        = array_keys($_POST);
	$allFieldExist  = true;
	for ($i = 0; $i < $numRequired && $allFieldExist; $i++) {
		
		
		if (!in_array($requiredField[$i], $keys) || StopSqlInjection($_POST[$requiredField[$i]]) == '') {
			$allFieldExist = false;
		}
	}
	return $allFieldExist;
}


function displayAmount($amount)
{
	global $shopConfig;
	return number_format($amount);
}

/*
	Join up the key value pairs in $_GET
	into a single query string
*/
function queryString()
{
	$qString = array();
	
	foreach($_GET as $key => $value) {
		if (trim($value) != '') {
			$qString[] = $key. '=' . trim($value);
		} else {
			$qString[] = $key;
		}
	}
	
	$qString = implode('&', $qString);
	
	return $qString;
}

/*
	Put an error message on session 
*/
function setError($errorMessage)
{
	if (!isset($_SESSION['plaincart_error'])) {
		$_SESSION['plaincart_error'] = array();
	}
	
	$_SESSION['plaincart_error'][] = $errorMessage;

}

/*
	print the error message
*/
function displayError()
{
	if (isset($_SESSION['plaincart_error']) && count($_SESSION['plaincart_error'])) {
		$numError = count($_SESSION['plaincart_error']);
		
		echo '<table id="errorMessage" width="550" align="center" cellpadding="20" cellspacing="0"><tr><td>';
		for ($i = 0; $i < $numError; $i++) {
			echo '&#8226; ' . $_SESSION['plaincart_error'][$i] . "<br>\r\n";
		}
		echo '</td></tr></table>';
		
		// remove all error messages from session
		$_SESSION['plaincart_error'] = array();
	}
}

function updatetimercoke($orderid)
{
	$sid = $_SESSION['pancart_user_id'];
	//gettimerval();
	//$gettimer = $_SESSION['cartminit'];
	$gettimer = gettimervalcoke($orderid);
	if($gettimer>0){
	$updatetimer123=$gettimer-1;
			// update product quantity
			$sql = "UPDATE WEB_ROOTtimer_cart
					SET tm_timer = $updatetimer123
					WHERE ct_session_id = $sid and ct_session_ord_id='$orderid'";
			dbQuery($sql);
	//$_SESSION['cartminit']=$updatetimer;
	}else{
	//$_SESSION['cartminit']=0;
	$updatetimer123=0;
	}
	return $updatetimer123;

}
function gettimervalcoke($orderid)
{
	if($_SESSION['pancart_user_id'])
	{

	$sid = $_SESSION['pancart_user_id'];
	$sql = "SELECT tm_timer from WEB_ROOTtimer_cart WHERE ct_session_id = $sid and ct_session_ord_id=$orderid";
	$result = dbQuery($sql);
	$row = dbFetchAssoc($result);
		if($row['tm_timer']){	
			$updatetimer123=$row['tm_timer'];
		}else{
			$updatetimer123=0;		
		}
	}else{

		$updatetimer123=0;
	}
	return $updatetimer123;

}
function gettimerval()
{
	if($_SESSION['userId'])
	{
	$sid = $_SESSION['userId'];
	
	$sql = "SELECT tm_timer from WEB_ROOTtimer_cart WHERE ct_session_id = '$sid'";
	$result = dbQuery($sql);

	$row = dbFetchAssoc($result);

	/*if($row['tm_timer']){	
		$_SESSION['cartminit']=$row['tm_timer'];
	}else{
		$_SESSION['cartminit']=0;
	}
	*/
	if($row['tm_timer']){	
		$updatetimer123=$row['tm_timer'];
	}else{
		$updatetimer123=0;
		
	}
	if($updatetimer123===0){
		deleteAbandonedCartAtNow();
	}
	}else{
		$updatetimer123=0;
	}
	return $updatetimer123;

}
function gettimervalmobile($uid)
{
	
	$sid = $uid;
	
	$sql = "SELECT tm_timer from WEB_ROOTtimer_cart WHERE ct_session_id = $sid";
	$result = dbQuery($sql);

	$row = dbFetchAssoc($result);

	/*if($row['tm_timer']){	
		$_SESSION['cartminit']=$row['tm_timer'];
	}else{
		$_SESSION['cartminit']=0;
	}
	*/
	if($row['tm_timer']){	
		$updatetimer123=$row['tm_timer'];
	}else{
		$updatetimer123=0;
		
	}
	if($updatetimer123===0){
		deleteAbandonedCartAtNowmobile($uid);
	}
	
	return $updatetimer123;

}
function deleteAbandonedCartAtNowmobile($uid)

{

	$userid=$uid;

	$sqlc = "DELETE FROM WEB_ROOTcart where ct_session_id= $userid";
	dbQuery($sqlc);
	$sql = "DELETE FROM WEB_ROOTtimer_cart where ct_session_id= $userid";

	dbQuery($sql);		

}
function deleteAbandonedCartAtNow()

{

	$userid=$_SESSION['userId'];

	$sqlc = "DELETE FROM WEB_ROOTcart where 	ct_session_id= $userid";
	dbQuery($sqlc);
	$sql = "DELETE FROM WEB_ROOTtimer_cart where ct_session_id= $userid";

	dbQuery($sql);		

}

function EmailValidation($email)
{ 
	
	$atTheRate=explode('@',$email);
	$totalAtTheRate_tmp = sizeof($atTheRate);
	$atCounter= intval($totalAtTheRate_tmp)-1;
	
	
	$dotArr_temp=explode('.',$atTheRate[1]);
	 $countDotTemp = sizeof($dotArr_temp)-1;
	
	
	
		/*if($countDotTemp == 1)
		{
		//check domain name like .com, .net, .info, .edu etc
			$val1="select id from WEB_ROOTdomain_name where domain='$dotArr_temp[1]'";
			$query=dbQuery($val1);
			 $isValid=dbNumRows($query);
		}
		else if($countDotTemp == 2)
		{
			//check country domain name like .in, .at, .pk
			 $val1="select id from WEB_ROOTcountry_domain_name where domain='$dotArr_temp[2]'";
			$query=dbQuery($val1);
			$isValid=dbNumRows($query);
		}
		*/
		if(sizeof($dotArr_temp) <=3)
		{
			$isValid=1;
		}
		else
		{
			// if email id contain more than 2 dots  after the '@',  means no need to execute query. its not valid format
			$isValid=0;
		}
		//count the char before at the rate
		// $CountCharBeforeAtRate=strlen($atTheRate[0]);
		
		$start=intval(strpos($email,"@"));
		$start=$start-1;
//echo"<br>".substr($email,$start,1).' === '.$email;;
		
	$n=0;
	
	if($atCounter ==1 && $isValid && !preg_match('/[\'^�$%&*!()}{@#~?><>,|=_+�]/',$dotArr_temp[0]) && !preg_match('/[\'^�$%&*!()}{@#~?><>,|=_+�-]/',substr($email,$start,1)) && strpos($email,"/") ==0 && strpos($email,"\"") == 0 ) //email verify	
	{ 
		$n=1;
		
	}
	return $n;

}

function removesepcharadmin($wrongdescval)
{
$rigtval=str_replace("'"," ",str_replace('"',' ',str_replace("�","",str_replace("�","",str_replace("�","",str_replace("�","",strip_tags($wrongdescval)))))));
return $rigtval;
}

function isLoginUserOnRoot()
{	
	$urlx= $_SERVER['PHP_SELF'];
	$pageArray=explode('/',$urlx);
	$page_name=$pageArray[sizeof($pageArray)-1];
		 
	if((empty($_SESSION['pancart_user_id']) || empty($_SESSION['user_city_id'])) && empty($_SESSION['temp_register_id']))
	{
		session_destroy();
		?><script type="text/javascript">
		window.location.href="<?php echo 'getUserCityInfo.php'; ?>";
		</script>
        <?php
        
	} else if((empty($_SESSION['pancart_user_id']) || empty($_SESSION['user_city_id'])) && !empty($_SESSION['temp_register_id']) && $page_name != 'products.php'){
		
		?><script type="text/javascript">
		window.location.href="<?php echo 'login_register.php'; ?>";
		</script>
        <?php    
	}
}

function getBrand()
{
	$val1="select brand_name, brand_url from WEB_ROOTbrand where brand_active=1 and brand_sub_brand_id = 0 order by brand_shorting asc";				 
    $result = dbQuery($val1);
 
    while($row = dbFetchAssoc($result))
	{	
		extract($row);
		$data[]=array(
		'name'=>$brand_name,
		'url'=>$brand_url
		);	
		
	}	
	return $data;
	
}	
function checkPincodenumric($pincode)
{
   $success=0;
	if( strlen($pincode)!=6 ||  !is_numeric($pincode) )
	{
	 $success=1;
	}
	return $success;
}

function checkPhonenumric($phone)
{
$success=0;
	if(  !is_numeric($phone) )
	{
	 $success=1;
	}
	return $success;
}
function checkPincode($pincode)
{
$sql = "SELECT * FROM WEB_ROOTpincode WHERE pin_code='$pincode'";
    $result = dbQuery($sql);
	return dbNumRows($result);
}

function sendMessage($text,$mobile,$name)
{
	$username='richpurple';
	$password='12870';
	
	// Initialize the sender variable
	$sender=urlencode($name);
	//$txt_shipping_phone=9458455381;
	// Initialize the URL variable
	$URL="http://www.sms19.info/ComposeSMS.aspx?";
	// Create and initialize a new cURL resource
	$ch = curl_init();
	// Set URL to URL variable
	curl_setopt($ch, CURLOPT_URL,"http://$URL");
	// Set URL HTTP post to 1
	curl_setopt($ch, CURLOPT_POST, 1);
	// Set URL HTTP post field values
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	"username=".$username."&password=".$password."&sender=".$sender."&to=".$mobile."&message=".$text."&priority=1&dnd=1&unicode=0");
	// Set URL return value to True to return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// The URL session is executed and passed to the browser
	 $curl_output =curl_exec($ch);
	
}


function IVRCall($mobile)
{
	$username='demo';
	$password='demo';
	$today_date=date('m/d/Y');
	$time=date('h:m:s');
	// Initialize the sender variable
	$sender=urlencode($name);
	//$txt_shipping_phone=9458455381;
	// Initialize the URL variable
	$URL='http://nowconnect.in/SendVoice.aspx?';
	// Create and initialize a new cURL resource
	$ch = curl_init();
	// Set URL to URL variable
	curl_setopt($ch, CURLOPT_URL,$URL);
	// Set URL HTTP post to 1
	curl_setopt($ch, CURLOPT_POST, 1);
	// Set URL HTTP post field values
	curl_setopt($ch, CURLOPT_POSTFIELDS,
		'UserName='.$username.'&Password='.$password.'&VoiceFile=Fin.wav&MobileNos='.$mobile.'&ScheduleDateFrom='.$today_date.' 00:00:00 AM&ScheduleDateTo='.$today_date.' 00:00:00 AM&ScheduleTimeFrom=2/28/2013 06:35:15 PM&ScheduleTimeTo=5/26/2013 06:40:00 PM');
	// Set URL return value to True to return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// The URL session is executed and passed to the browser
	 $curl_output =curl_exec($ch);
	
}



function getCityName($condt)
{
	$val1="select ct_id,ct_dep_id ,ct_name from WEB_ROOTcity where $condt";
	$res=dbQuery($val1);
	$row=dbFetchAssoc($res);
	return $row; 
	
}

function getstate($state='')
{
	
	$val1="select st_id,st_name from WEB_ROOTstate where st_status=1  order by st_name asc";
	$res=dbQuery($val1);
	while($row=dbFetchAssoc($res))
	{
		if($state == $row['st_id'])
		{
			$tag.='<option value="'.$row['st_id'].'" selected="selected">'.$row['st_name'].'</option>';
			continue;
		}
		$tag.='<option value="'.$row['st_id'].'">'.$row['st_name'].'</option>';
	}
	return $tag;

}




function stratdate(){
	
$datev=DB_DATE_FORMAT;
$datenew=explode(" ",$datev);

$dday=$datev['mday'];

$mday=$datev['mon'];

if(strlen($mday)==1){

$mday="0".$mday;

}

if(strlen($dday)==1){

$dday="0".$dday;

}

$startdate=$datenew[0];
return $startdate;
}


//use in success page, backend -category
function createThumbnail($srcFile, $destFile, $width, $quality = 100)
{
	$thumbnail = '';
	
	if (file_exists($srcFile)  && isset($destFile) && $width != 180)
	{
		$size        = getimagesize($srcFile);
		$w           = number_format($width, 0, ',', '');
		$h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');
		
		/*if($h>$w)
		{
			$heighth=$h;
			$h           = number_format($width, 0, ',', '');
			$w           = number_format(($size[0] / $size[1]) * $heighth, 0, ',', '');	
		}*/

		
	}
	else
	{
		$h=330;
		$w=$width;
	}

	
	$thumbnail =  copyImage($srcFile, $destFile, $w, $h, $quality);

	
	
	// return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}
function createThumbnailByCondition($srcFile, $destFile, $width, $quality = 100,$condt)
{
	$thumbnail = '';
	
	if($condt=='width')
	{
		if (file_exists($srcFile)  && isset($destFile))
		{
			$size        = getimagesize($srcFile);
			$w           = number_format($width, 0, ',', '');
			$h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');		
		}
		
	}
	else
	{
		//by height
		$height=$width;
		if (file_exists($srcFile)  && isset($destFile))
		{
			$size        = getimagesize($srcFile);
			$h           = number_format($height, 0, ',', '');
			$w           = number_format(($size[0] / $size[1]) * $height, 0, ',', '');		
		}
	}	
	$thumbnail =  copyImage($srcFile, $destFile, $w, $h, $quality);
	// return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}

function createSquareThumbnail($srcFile, $destFile, $height, $width, $quality = 100)
{
	$thumbnail = '';	
	$thumbnail =  copyImage($srcFile, $destFile,$width, $height, $quality);	
	// return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}


function copyImage($srcFile, $destFile, $w, $h, $quality = 100)
{
   $tmpSrc     = pathinfo(strtolower($srcFile));
    $tmpDest    = pathinfo(strtolower($destFile));
    $size       = getimagesize($srcFile);

    if (strtolower($tmpDest['extension']) == "gif" )
    {
       $destFile  = substr_replace($destFile, 'gif', -3);
       $dest      = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    }
	else if(strtolower($tmpDest['extension']) == "jpg")
	{
		 $destFile  = substr_replace($destFile, 'jpg', -3);
        $dest      = imagecreatetruecolor($w, $h);
        imageantialias($dest, TRUE);
	}
	else if (strtolower($tmpDest['extension']) == "png")
	{
       $dest = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    } 
	else
	{
      return false;
    }

    switch($size[2])
    {
       case 1:       //GIF
           $src = imagecreatefromgif($srcFile);
           break;
       case 2:       //JPEG
           $src = imagecreatefromjpeg($srcFile);
           break;
       case 3:       //PNG
           $src = imagecreatefrompng($srcFile);
           break;
       default:
           return false;
           break;
    }

   imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
   

    switch($size[2])
    {
       case 1:
       case 2:
           imagejpeg($dest,$destFile, $quality);
           break;
       case 3:
           imagepng($dest,$destFile);
    }
	
    return $destFile;

}

function getOfferDetail($offer_type,$offer_value,$order_type)
{
	if($order_type=='product_offer'){
		if($offer_type=='percent'){
			return $offer_value.'% ';
		}else if($offer_type=='fixed'){
			return 'Rs.'.$offer_value;
		}
	}else if($order_type=='price_offer'){
		if($offer_type=='percent'){
			return $offer_value.'% ';
		}else if($offer_type=='fixed'){
			return 'Rs.'.$offer_value;
		}
	}else {
		return 'Minimum Order amount Rs.'.$offer_value;
	}

}

function getOrderAmount($city_id, $prod_id)
{

	$val1="select * from WEB_ROOTproduct_price where pr_product_id=$prod_id and pr_city_id=$city_id and pr_status=1";
	$res=dbQuery($val1);
	$row=dbFetchAssoc($res);
	$data=array(
			'mrp'=>$row['pr_product_mrp_price'],
			'selling_price'=>$row['pr_product_selling_price'],
			'pr_id'=>$row['pr_id']
	);
	
return $data;
}


function pageTracking()
{
$url_x= $_SERVER['PHP_SELF'];
$pageName=explode('/',$url_x);
$totalfoldercount=count($pageName);
$path=$pageName[$totalfoldercount-1];
	
	$user_type=0;
	$campaign_id	=	$_SESSION['campaign_code_id'];
	$cc_code		=	$_SESSION['campaign_code'];	
	$ip_address		=	$_SERVER['REMOTE_ADDR'];
	$user_id		=	$_SESSION['pancart_user_id'];

	if(!empty($campaign_id))
	{
		$user_type=1;//campagin user only
	}
	
	if(empty($campaign_id))
	{
		$campaign_id=0;
	}
	
	if(empty($user_id))
	{
		$user_id=0;
	}
	
	if(empty($cc_code))
	{
		$cc_code=0;
	}
	

	$val1="select url_id from WEB_ROOTurl_master where url_path ='".$path."'";
	$res=dbQuery($val1);
	if(dbNumRows($res)){
		$row=dbFetchAssoc($res);
		$url_value=$row['url_id'];
	}else{
	
		$val2="insert into WEB_ROOTurl_master(url_path, url_user_type, campaign_id) values('$path', $user_type, '$campaign_id')";
		$res=dbQuery($val2);
		$url_value=dbInsertId();		
	}
	
	$val3="insert into WEB_ROOTipaddress(ip_url_value, ip_entry_date, ip_address, ip_user_id, ip_user_type,  ip_cc_code ) values($url_value, '".DB_DATE_FORMAT."','$ip_address', $user_id, $user_type,  '$cc_code')";
	dbQuery($val3);

}


function getCopuonAmountForSuccess($od_id)
{
		$val2="select * from WEB_ROOTproduct where pd_id=".$od_id;
		$res2=dbQuery($val2);
		$row2=dbFetchAssoc($res2);
		return $row2;
	
}

function findUserDevice()
{	
	$geturlpathvalue=$_SERVER['PHP_SELF'];
	$explodeurlvalue=explode("/",$_SERVER['PHP_SELF']);
	$counturlvalue=count($explodeurlvalue);
	if($counturlvalue==4){
		if($_SERVER['HTTP_HOST']=='192.168.1.100' && $explodeurlvalue[2]=='mobile'){
			$usertypevalue=1;
		}else if($_SERVER['HTTP_HOST']=='www.coke2home.com' && $explodeurlvalue[2]=='mobile'){
			$usertypevalue=2;
		}else{
			$usertypevalue=0;
		}
	}else{
		if($_SERVER['HTTP_HOST']=='www.coke2home.com' && $explodeurlvalue[1]=='mobile'){
			$usertypevalue=3;
		}else if($_SERVER['HTTP_HOST']=='www.coke2home.com' && $explodeurlvalue[1]=='testcoke'){
			$usertypevalue=4;
		}else if($_SERVER['HTTP_HOST']=='www.coke2home.com' && $explodeurlvalue[1]=='testcoke' && $explodeurlvalue[2]=='mobile'){
			$usertypevalue=5;
		}else{
			$usertypevalue=0;
		}
	}


	$useragent=$_SERVER['HTTP_USER_AGENT'];
	  if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	{ 
		if($usertypevalue==1){ 
			header('Location:http://192.168.1.100/coke2home/mobile/home.php');
		}else if($usertypevalue==2){
			header('Location:http://www.coke2home.com/testcoke/mobile/home.php');
		}else if($usertypevalue==5){
			header('Location:http://m.coke2home.com/testcoke/home.php');
		}else if($usertypevalue==3){
			header('Location:http://m.coke2home.com/home.php');
		}else{
			header('Location:http://m.coke2home.com/home.php');
		}
	}
	else
	{
		if($usertypevalue==1){ 
			header('Location:http://192.168.1.100/coke2home/mobile/home.php');
		}else if($usertypevalue==2){
			header('Location:http://www.coke2home.com/testcoke/mobile/home.php');
		}else if($usertypevalue==3){
			header('Location:http://m.coke2home.com/home.php');
		}else if($usertypevalue==4){
			header('Location:http://www.coke2home.com/testcoke/home.php');
		}else if($usertypevalue==5){
			header('Location:http://www.coke2home.com/testcoke/mobile/home.php');
		}else{
			header('Location:'.DOMINOS_PATH_NAME.'home.php');
		}
	}
}

function getPasswrod()
	{
	
		$code='';
	$smallChar=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','u','r','s','t','x','y','z');
	$bigChar=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','U','R','S','T','X','Y','Z');
	$numberChar=array('1','2','3','4','5','6','7','8','9','0');
	$specialchar=array('@','#','$','%','','*','-','?');
	
		for($i=1 ; $i <= 10; $i++)
		{
			if($i== 2 || $i == 1)
			{					
		  		$code.= $smallChar[array_rand($smallChar)];	
			}
			if($i ==4 || $i == 3)
			{
				$code.= $bigChar[array_rand($bigChar)];	
			}
			if($i ==6 || $i ==5 )
			{
				$code.= $numberChar[array_rand($numberChar)];	
			}
			if($i ==8 || $i == 7)
			{
				$code.= $specialchar[array_rand($specialchar)];	
			}
			if($i ==9 || $i == 10)
			{
				$code.= $specialchar[array_rand($specialchar)];	
			}
				
		}
		
		return $code;
	}

function getCouponcodestring() 
{
    $length = 5;
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $coupancode = '';    

    for ($p = 0; $p < $length; $p++)
	{
        $coupancode .= $characters[mt_rand(0, strlen($characters))];
    }

	$val1="select gift_coupun_code from WEB_ROOTgift where gift_coupun_code='$coupancode'";
	$result=dbQuery($val1);
	if(dbNumRows($result)> 0)
	{
		getCouponcodestring();
	}
	else
	{
		return $coupancode;
	}
}



function passwordValidation($password)
{
		$n=0;
		if(preg_match('/[\'^�$%&*()}{@#~?><>,|=_+�-]/', $password) && strlen($password) >= 8 && strlen($password) >= 8)
		{	
			 if(ereg_replace("[^0-9]", "",$password))	
			 {	
			 
				if(preg_match('/[A-Z]/',$password) && preg_match('/[a-z]/',$password))
				{		 
					$n=1;
				}	
			}
		}
		return $n;
}


function StopSqlInjection($db,$str)
{	
		$messval=strip_tags($str);
		$messval=str_replace('/','-',$messval);	
		$messval=preg_replace('/[^a-zA-Z0-9._,@ d&-]/s', '', $messval);
		$messval=str_replace('\t','',$messval);	
		$messval=str_replace('alert','',$messval);		
		$messval=str_replace(array('&amp;','&lt;','&gt;','&'), array('&amp;amp;','&amp;lt;','&amp;gt;',''), $messval);
		$messval=str_replace('"','',$messval);		
		$messval=htmlspecialchars(mysql_real_escape_string($messval));	
		return $messval;
}

function StopSqlInjection11($data)
{
	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
	
	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
	
	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
	
	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
	
	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
	
	do
	{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);
	
	// we are done...
	return $data;
}

function isMobile_Device() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}


function mobileValidation($number)
{
	$n=0;
	if(strlen($number) == 10 && is_numeric($number))
	{
		$n=1;
	}
	return $n;

}


function errorMsgList($str)
{
	switch($str){
	
		case '1':
			$msg='Your registration is successfull.';
		break;
		
		case '2':
			$msg='*All fields are mandatory.';
		break;
		case '3':
			$msg='You are already registered.';
		break;
		case '4':
			$msg='Sorry!! Your '.PAGE_TITLE.' account is temporarily blocked.';
		break;
		case '5':
			$msg='*All fields are mandatory.';
		break;
		
		case '6':
			$msg='Invalid mobile number.';
		break;
		case '7':
			$msg='Please enter valid email id.';
		break;
		
		case '9':
			$msg='Invalid mobile number.';
		break;	
		
		case '10':
			$msg='Please select city.';
		break;	
		
		case '11':
			$msg='Please select locality.';
		break;	
		
		case '12':
			$msg='Please enter house/flat number';
		break;	
		case '13':
			$msg='Please enter area/road/sector.';
		break;	
		
		case '14':
			$msg='Please enter landmark.';
		break;	
		
		
		case '15':
			$msg='Facing trouble in registering with us? Please take the help of your parents/guardians.';
		break;
		case '16':
			$msg="Invalid captcha code.";
		break;	
		
		case '17':
			$msg='Please select valid date of birth.';
		break;
		
		case '18':
			$msg='Sorry!! Your '.PAGE_TITLE.' account is temporarily inactive.';
		break;
	
		default:
			$msg='All fields are mandatory';
		break;
		
		}
		

	return $msg;

}

function checkDob($birthDate)
{

	   $birthDate = explode("/", $birthDate);
         //get age from date or birthdate
		$age=0;
	if(is_numeric($birthDate[0]) && is_numeric($birthDate[1]) && is_numeric($birthDate[2])) 
	{		 
         $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y")-$birthDate[2])-1):(date("Y")-$birthDate[2]));
		 }


	return $age;
}


function checkSession() {
    if ( !empty($_SESSION['pancart_user_id']) && !is_numeric($_SESSION['pancart_user_id'])) {
        //header('location:'.DOMINOS_PATH_NAME.'404.php');
        die();
    }
}

checkSession();

function getOrderNumber()
{
	//$end_date=date('Y-m-d',strtotime('+90 days'));
	$flag=0;
	$val1="select * from WEB_ROOTgenerate_order_no where od_status = 1";	
	$res=dbQuery($val1);
	
	 if(dbNumRows($res))
	 {
	 	$row=dbFetchAssoc($res);
		$newOrderNo	=	$row['od_order_no'];	
		$id=$row['od_id'];
		$newOrderNo+=1;
		if($newOrderNo <= ORDER_LIMIT_EXCEED)
		{
			 $val3="update WEB_ROOTgenerate_order_no set od_order_no=$newOrderNo where od_id=".$id;
			dbQuery($val3);
		}
		else
		{
			//inactive last order series
			$val3="update WEB_ROOTgenerate_order_no set od_status=0 where od_id=".$id;
			
			dbQuery($val3);			
			$flag=1;
		}
			
	 }
	 else
	 {
	 	$flag=1;	 	
	 }
	 
	 
	 if($flag==1)
	 {
	 	$newOrderNo=1;
		
	 	$val2="insert into WEB_ROOTgenerate_order_no(od_date, od_order_no, od_status) values('".DB_DATE_FORMAT."',$newOrderNo, 1)";
		dbQuery($val2);	
		
		
		
		 
	 }
	 
	 $len=strlen($newOrderNo);
	 if($len ==1)
	 {
	 	$newOrderNo='000'.$newOrderNo;
	 }else if($len==2){
	 	$newOrderNo='00'.$newOrderNo;
	 }else if($len ==3){
	 	$newOrderNo='0'.$newOrderNo;
	 }
	 
	 return $newOrderNo;
	
	
}

function getPaymentMethod($mode_type)
{
	$paymode='';
	if($mode_type=='NETBANKING')
		{
		 $paymode=  'Net Banking';
		}
		else if($mode_type=='DEBITCARD')
		{
			$paymode=  'Debit Card';
		}
		else if($mode_type=='CREDITCARD')
		{
			$paymode=  'Credit Card';
		 }
		 else if($mode_type=='CASHCARD')
		 {
			$paymode= 'Cash Card';
		 }else if($mode_type=='COD')
		 {
		 	$paymode='Cash On Delivery';
		 }
		 
		 return $paymode;
}

function getUserId()
{
	$user_id=0;
	if(is_numeric($_SESSION['pancart_user_id']))
	{
		$user_id=$_SESSION['pancart_user_id'];
	}else if(is_numeric($_SESSION['temp_register_id'])){
	
		$user_id=$_SESSION['temp_register_id'];
	}else
	{
		header('location:index.php');
		exit();
	}
	
	return $user_id;
}
function getPackType($pack,$qty=0,$pack_type)
{
	if($qty==0)
	{
		if($pack>1){
			return $pack_type.'s';	
		}else{
			return $pack_type;
		}
	}else{
		if($qty>1){
			return $pack_type.'s';	
		}else{
			return $pack_type;
		}
	}
}
function getNetQty($qty)
{
	if($qty>999)
	{
		$returvalue=($qty/1000);
		return $returvalue.'l';	
	}else{
		return $qty.'ml';	
	}
}
function getSellingDiscount($mrp, $selling)
{
	$differ=$mrp-$selling;
	$discount=round(($differ/$mrp)*100,0);
	$data=array($discount.'%','OFF');
	return $data;
}
function HtmlCharRepalce($chr)
{
	$chr=str_replace("'","'",$chr);
	$chr=str_replace("‘'",'"',$chr);
	$chr=str_replace("&","&",$chr);
	$chr=str_replace("–","-",$chr);
	$chr=str_replace("_","_",$chr);
	$chr=str_replace("’","'",$chr);
	
	$chr=str_replace("'","&#39;",$chr);
	$chr=str_replace("’","&#39;",$chr);
	$chr=str_replace("'","&#39;",$chr);
	return $chr;
	
}
?>