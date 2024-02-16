<?php
require_once 'query.class.php';
class frontuservalidation extends commonUsequery 
{
public function categoryList($selectField='',$path='') {
	$productSelect='category_id,category_parent,category_name, category_description, category_url, category_image, category_thumbnail, category_status, category_create_date, category_update_date, category_keyword, category_meta, category_meta_description';
	$producttable=TABLE_PREFIX."category ";
	$productwhere=" where category_status=1 and category_parent=0 ORDER BY category_id DESC";
	$productlist= $this->QueryFieldMultipleSelect($producttable, $productwhere,$productSelect);
	$contenthtml = '';
	$m=1;
	foreach($productlist as $row) {
		$chechdv = '';
		$ctid= $row['category_id'];
		if(in_array($ctid,$selectField)) {
			$chechdv = 'checked="checked"';
		}
				$contenthtml.='<div class="col-lg-2">
				<div class="card card-default text-center">
				<div class="card-body">
				<img src="'.$path.$row['category_image'].'"  class="cimg">
				
				<div class="checkbox check-success checkbox-circle">
				    <input type="checkbox" '.$chechdv.'  value="'.$row['category_id'].'"  class="checkBoxClass" name="image[]"   id="categoryslect'. $m.'">
				    <label for="categoryslect'.$m.'">'.ucfirst($row['category_name']).'</label>
				</div>
				</div>
				</div>
				</div>';
				$m++;
 
		
    }
    return $contenthtml;


}
 public function checkRequiredPost($requiredField) {
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
  public function EmailValidation($email)
	{ 

		$atTheRate=explode('@',$email);
		$totalAtTheRate_tmp = sizeof($atTheRate);
		$atCounter= intval($totalAtTheRate_tmp)-1;


		$dotArr_temp=explode('.',$atTheRate[1]);
		 $countDotTemp = sizeof($dotArr_temp)-1;



			
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

		if($atCounter ==1 && $isValid && !preg_match('/[\'^£$%&*!()}{@#~?><>,|=_+¬]/',$dotArr_temp[0]) && !preg_match('/[\'^£$%&*!()}{@#~?><>,|=_+¬-]/',substr($email,$start,1)) && strpos($email,"/") ==0 && strpos($email,"\"") == 0 ) //email verify	
		{ 
			$n=1;
			
		}
		return $n;

	}
	public function checkPincodenumric($pincode)
	{
		$success=1;
		if( strlen($pincode)!=6 ||  !is_numeric($pincode) )
		{
		 $success=0;
		}
		return $success;
	}

	public function checkPhonenumric($phone)
	{
		$success=1;
		
		
		if(strlen($phone)!=10 ||  !is_numeric($phone) )
		{
		
		  $success=0;
		}
		
		return $success;
	}
	public function getPasswrod()
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

}
	
?>