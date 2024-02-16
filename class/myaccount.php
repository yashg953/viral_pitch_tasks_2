<?php
require_once 'query.class.php';
class frontaccount extends commonUsequery
{
public function userinfomation($user)
{
$condition=" WHERE reg_id = $user";		
			$check_pro=$this->QueryFieldSelect('dri_registration',$condition,' * ');
			return $check_pro;
}
public function userinfomationupdate($user)
{
 print_r($_POST);
 $data=array('uemail', 'upass','umobile','ugender');
 if($validationObj->checkRequiredPost($data))
	{
	}
 die();
}
public function myorderlist($start=0,$end)
{
 $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE od_user_id= ".$user_Id." and (od_status = 'Confirmed' or od_status = 'Delivered' or od_status ='Shipped' or od_status ='Completed')  order by od_date desc limit $start,$end ";
		      $check_qty=$this->QueryFieldMultipleSelect('dri_order_final',$conditioncart,' * ');
			

	}
	return $check_qty;
}
public function myorderlistcount()
{
 $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE od_user_id= ".$user_Id." and (od_status = 'Confirmed' or od_status = 'Delivered' or od_status ='Shipped' or od_status ='Completed')";
		      $check_qty=$this->QueryRowFieldSelect('dri_order_final',$conditioncart,' * ');
			

	}
	return $check_qty;
}
public function subuserlist($email)
{

             $subval=0;
	  		$conditioncart=" WHERE sb_email='".$email."'";
		      $check_sub=$this->QueryFieldSelect('dri_subscribers',$conditioncart,' sb_newsletter');
			  if($check_sub['sb_newsletter']==1)
			  {
			  $subval=1;
			  }

	
	return $subval;
}
public function mycreditlist($m=0)
{
   $user_Id=$_SESSION['user_id'];
   if($m==0)
   {
   $odqry=" and order_id=0";
   }else
   {
    $odqry=" and order_id!=''";
   }
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE user_id= ".$user_Id.$odqry;
		      $check_list=$this->QueryFieldMultipleSelect('dri_user_credit',$conditioncart,' * ');
			

	}
	return $check_list;
}
public function mycreditremainamount()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE user_id= ".$user_Id;
		      $check_list=$this->QueryNameSelect('dri_credit',$conditioncart,'credit_value');
			

	}
	return $check_list;
}

public function mypointlist()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
 
	  		$conditioncart=" WHERE dur.user_id= ".$user_Id." and dur.order_id=0 and dp.pt_id=dur.point_name";
		      $check_list=$this->QueryFieldMultipleSelect('dri_user_point_rel dur,dri_point dp',$conditioncart,' * ');
			

	}
	return $check_list;
}
public function mypointlistuse()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
 
	  		$conditioncart=" WHERE user_id= ".$user_Id." and order_id!=0";
		      $check_list=$this->QueryFieldMultipleSelect('dri_user_point_rel ',$conditioncart,' * ');
			

	}
	return $check_list;
}
public function mypointremainamount()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE user_id= ".$user_Id;
		      $check_list=$this->QueryNameSelect('dri_user_point',$conditioncart,'credit_value');
			

	}
	return $check_list;
}

public function emptytempcredit()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE user_id= ".$user_Id;
		      $check_list=$this->QueryDelete('dri_credit_temp','Not Deletee', $conditioncart);
			

	}
	}
public function emptytemppoint()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE user_id= ".$user_Id;
		      $check_list=$this->QueryDelete('dri_point_temp','Not Deletee', $conditioncart);
			

	}
	}
public function mypointtemp()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE user_id= ".$user_Id;
		      $check_list=$this->QueryNameSelect('dri_point_temp',$conditioncart,'amount');
			

	}
	return $check_list;
}
public function mycredittemp()
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	  		$conditioncart=" WHERE user_id= ".$user_Id;
		      $check_list=$this->QueryNameSelect('dri_credit_temp',$conditioncart,'amount');
			

	}
	return $check_list;
}	
function mycoupantemp($user_id, $code)
	{
		$user_Id=$_SESSION['user_id'];
		if(is_numeric($user_Id))
		{
		$conditioncart=" WHERE user_id= ".$user_Id;
		$check_list=$this->QueryNameSelect('dri_coupon_temp_status',$conditioncart,'discount_amount');

		}
		return $check_list;
	   
	}


public function mycreditUpdate($orderId)
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	
	         $cramount=$this->mycredittemp() ;  
              $varquertCAT="user_id='$user_Id', order_id='$orderId', credit_name='$orderId', price='$cramount', create_date='".DB_DATE_FORMAT."'";
	     $usercreditin= $this->Queryinsert('dri_user_credit', $varquertCAT, 'Catogry Not Insert');			 
	  		$conditioncart=" WHERE user_id= ".$user_Id;
			
			$upval="  credit_value= (credit_value-".$cramount.")";
		      $check_list=$this->Queryupdate('dri_credit',$upval,'NOT Update',$conditioncart);
			$this->emptytempcredit();

	}

}
public function myPointUpdate($orderId)
{
   $user_Id=$_SESSION['user_id'];
  if(is_numeric($user_Id))
	{
	
	         $pamount=$this->mypointtemp() ;  
              $varquertCAT="order_id=".$orderId.", user_id='".$user_Id."', price='".$pamount."', create_date='".DB_DATE_FORMAT."' , exp_date='".$expiryDate."'";
	          $usercreditin= $this->Queryinsert('dri_user_point_rel', $varquertCAT, 'Catogry Not Insert');			 
	  		
			
			$upval="  credit_value= (credit_value-".$pamount.")";
		      $usercreditup= $this->Queryupdate('dri_user_point', "credit_value='".$upval."', credit_entry_date='".DB_DATE_FORMAT."'", 'Credit Not Update',"WHERE user_id=$user_Id");
			$this->emptytemppoint();

	}
	
}
}
	
?>