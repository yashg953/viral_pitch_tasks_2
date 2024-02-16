<?php
require_once 'query.class.php';
class frontcheckout extends commonUsequery
{
public function usercart($sid,$productId)
{
  if(is_numeric($productId) || is_numeric($sid) )
	{
	 // $prsize = "3";
		//  $pqtval=explode(',',$prsize);
	      //$addqty=count($pqtval);
		 		//$addqty= "1";  
		 // for($m=0;$m<$addqty;$m++)
		  // {
			$condition=" WHERE dps.product_id = $productId and dpp.product_id=dps.product_id";		
			$check_pro=$this->QueryFieldSelect('dri_product dpp,dri_product_size dps',$condition,'dps.product_qty');
			
		  $product_qty_remaning=$check_pro['product_qty'];
		  if($product_qty_remaning>0)
		  {
		$conditioncart=" WHERE pd_id = $productId AND ct_session_id = '$sid' ";
		  $check_qty=$this->QueryFieldSelect('dri_cart',$conditioncart,'ct_qty');
		$prqty=$check_qty['ct_qty']+1;
		
		if(empty($check_qty))
		{					
		if($product_qty_remaning >= $prqty)
		{
		 $inserval="ct_qty = 1,ct_session_id = '$sid',pd_id = $productId,ct_date='".DB_DATE_FORMAT."'";
		$result123 = $this->Queryinsert('dri_cart',$inserval,"not insert");
		}
		}
		else
		{	
		if($product_qty_remaning >=$prqty)
		{
		 $whercon="WHERE ct_session_id = '$sid' AND pd_id = $productId ";
         $inserval="ct_qty = ct_qty+1 ,ct_date='".DB_DATE_FORMAT."'";
		$result123 = $this->Queryupdate('dri_cart',$inserval,"not insert",$whercon);		
		}
		}
		}
		 //}
				

		}
}
public function usercartupdate($sid,$productId,$pqty)
{
  if(is_numeric($productId) && is_numeric($sid) && is_numeric($pqty) )
	{
	   $condition=" WHERE dps.product_id = $productId and dpp.product_id=dps.product_id";		
			$check_pro=$this->QueryFieldSelect('dri_product dpp,dri_product_size dps',$condition,'dps.product_qty');
			
		  $product_qty_remaning=$check_pro['product_qty'];
	  	
		if($product_qty_remaning >=$pqty)
		{
		 $whercon=" WHERE ct_session_id = '$sid' AND pd_id = $productId";
         $inserval="ct_qty = $pqty ,ct_date='".DB_DATE_FORMAT."'";
		$result123 = $this->Queryupdate('dri_cart',$inserval,"not insert",$whercon);		
		}
	}
		
		
}
public function usercartdelete($sid,$productId,$prsize)
{
  if(is_numeric($productId) && is_numeric($sid) && is_numeric($prsize) )
	{
	   
		 $whercon=" WHERE ct_session_id = '$sid' AND pd_id = $productId and ct_size_val=".$prsize;
         
		$result123 = $this->QueryDelete('dri_cart',"not delete",$whercon);		
		}
}
		
		

public function productusercart()
{
$check_qty='';
  $user_id=$_SESSION['user_id'];
  if(is_numeric($user_id))
	{
	  	
		       $conditioncart=" WHERE drc.ct_session_id = ".$user_id." and drp.product_id=drc.pd_id and dpi.image_product=drc.pd_id and  dpi.image_default=1";
		       $check_qty=$this->QueryFieldMultipleSelect('dri_cart drc ,dri_product drp,dri_pro_image dpi',$conditioncart,'drc.ct_qty,drp.product_id,drp.product_name,drp.product_sell,drp.product_model,dpi.image_name,drc.ct_size_val,drp.product_qty,drc.ct_gift');
			

	}
	//echo '<pre/>';
	//print_r($check_qty);
	return $check_qty;
}
public function productusercartorder()
{
$check_qty='';
  $user_id=$_SESSION['user_id'];
  if(is_numeric($user_id))
	{
	  		 $conditioncart=" WHERE drc.ct_session_id = ".$user_id." and drp.product_id=drc.pd_id and dpi.image_product=drc.pd_id and  dpi.image_default=1";
		       $check_qty=$this->QueryFieldMultipleSelect('dri_cart drc ,dri_product drp,dri_pro_image dpi',$conditioncart,'drc.ct_qty,drc.ct_size_val,drc.pd_id,drp.product_mrp,drp.product_sell,drp.product_name,drp.product_qty,drc.ct_gift');
			

	}
	
	return $check_qty;
}

public function productuserfinalcart($od_id=0)
{
$check_qty='';
 if($_SESSION['orderId']!='')
 {
  $order_id=$_SESSION['orderId'];
  }else
  {$order_id=$od_id;
  }
  if(is_numeric($order_id))
	{
	  	$conditioncart=" WHERE drc.od_id= ".$order_id." and drp.product_id=drc.pd_id and dpi.image_product=drc.pd_id and  dpi.image_default=1";
		$check_qty=$this->QueryFieldMultipleSelect('dri_order_item drc ,dri_product drp,dri_pro_image dpi',$conditioncart,'drc.od_qty,drc.od_pd_size,drc.pd_id,drp.product_mrp,drp.product_sell,drp.product_name,drp.product_model,dpi.image_name');
			

	}
	return $check_qty;
}
public function userorderdetails($od_id=0)
{
$check_qty='';
  if($_SESSION['orderId']!='')
 {
  $order_id=$_SESSION['orderId'];
  }else
  {$order_id=$od_id;
  }
  if(is_numeric($order_id))
	{
	  		$conditioncart=" WHERE od_id= ".$order_id;
		      $check_qty=$this->QueryFieldSelect('dri_order_final',$conditioncart,' * ');
			

	}
	return $check_qty;
}

public function oredrtype($hidpaymentid,$final_amount_point=0,$final_amount_credit=0,$final_amount)
{
  $coupons='';
			$credits='';
			$points='';
			$crepipe='';
			$poipipe='';
			$discipe='';  
			if($final_amount_point!=0 ||  $final_amount_credit!=0 || $final_amount!=0)
				{
				
					if($final_amount!=0)
					{
					  $coupons='Coupon';
					}
					  if($final_amount_credit!=0)
					{
					$credits='Credits';
					}
				  if($final_amount_point!=0)
					{
					$points='Points';
					}
					  if($final_amount!=0 && $final_amount_credit!=0 && $final_amount_point==0)
					{
						$crepipe='|';
						$points='';
					}
					if($final_amount_credit!=0 && $final_amount_point!=0 && $final_amount==0)
					{
						$poipipe='|';
						$coupons='';
					}
					if($final_amount!=0 && $final_amount_point!=0 && $final_amount_credit==0)
					{
						$discipe='|';
						$credits='';
					}
					if($final_amount!=0 && $final_amount_point!=0 && $final_amount_credit!=0)
					{
						$crepipe='|';
						$poipipe='|';
						$discipe='';
					}
					$paytypemode1=$coupons.$crepipe.$credits.$poipipe.$discipe.$points;
				} 
				
 
		if($hidpaymentid=='CPA')
		{
			if($paytypemode1!='')
			{
			$paytypemode12='|'.$paytypemode1;
			}
			  $paytypemode='Cheque Payment/Bank Transfer '.$paytypemode12;
			   $paytypemodeto='Cheque Payment/Bank Transfer ';
		}
		if($hidpaymentid=='COD')
		{
			if($paytypemode1!='')
			{
			$paytypemode12='|'.$paytypemode1;
			}
			  $paytypemode='Cash On Delivery '.$paytypemode12;
			   $paytypemodeto='Cash On Delivery ';
		}
		if($hidpaymentid=='Pay Online')
		{
		  if($paytypemode1!='')
			{
			$paytypemode12='|'.$paytypemode1;
			
			}
		  $paytypemode='Online Payment '.$paytypemode12;
		  $paytypemodeto='Online Payment ';
		}
		if($hidpaymentid=='Pay Online1')
		{
		  if($paytypemode1!='')
			{
			$paytypemode12='|'.$paytypemode1;
			
			}
		  $paytypemode='Online Payment '.$paytypemode12;
		  $paytypemodeto='Online Payment ';
		}
		if($hidpaymentid=='Zero')
		{
		$paytypemode=$paytypemode1;
		}
		return $paytypemode;

}
public function cartempty()
{
 $ins_upfdel=$this->QueryDelete('dri_cart','Not delete.', " WHERE ct_session_id= ".$_SESSION['user_id']);
}
 
}
	
?>