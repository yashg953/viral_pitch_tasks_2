<?php
require_once 'query.class.php';
class backcheckout extends commonUsequery
{

public function productuserfinalcart($order_id)
{
$check_qty='';
   if(is_numeric($order_id))
	{
	  		$conditioncart=" WHERE drc.od_id= ".$order_id." and drp.product_id=drc.pd_id and dpi.image_product=drc.pd_id and  dpi.image_default=1 and dps.product_id=drc.pd_id and dps.size_id=drc.od_pd_size and dss.size_id=dps.size_id ";
		       $check_qty=$this->QueryFieldMultipleSelect('dri_order_item drc ,dri_product drp,dri_pro_image dpi,dri_size dss,dri_product_size dps',$conditioncart,'drc.od_qty,drc.od_pd_size,drc.pd_id,drp.product_mrp,drp.product_sell,drp.product_name,drp.product_model,dpi.image_name,dps.product_qty,drc.pd_id,drc.od_pd_size');
	}
	return $check_qty;
}
public function userorderdetails($order_id,$selectcon='')
{
 
  if(is_numeric($order_id))
	{
	  if($selectcon=='')
	  {
	  $selectcon=' * ';
	  }
	  		$conditioncart=" WHERE od_id= ".$order_id;
		      $check_qty=$this->QueryFieldSelect('dri_order_final',$conditioncart, $selectcon);
			

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

public function payementresponse($orderId)
{


 $productcrtvat= $this->productuserfinalcart($orderId);
 $orderuser= $this->userorderdetails($orderId);
  extract($orderuser);
 $ordertype= $this->oredrtype($od_payment_id,$od_poins_cost,$d_credit_cost,$od_discount_amount);
 $ordermessage=$od_status;
$thankmessage='your order was placed successfully!';
$oddate=date("d-F-Y h:i:s", strtotime($od_date));
 if(od_status=='Declined')
 {
          $ordermessage='Declined';
		 $thankmessage='your order was declined!';
		
 }
 


  for($mk=0;$mk<count($productcrtvat);$mk++) {
					extract($productcrtvat[$mk]);
					$productamt=$productamt+$product_sell;
					$producttotal=$producttotal+($product_sell*$od_qty);
  $prdet.='
			<tr>
			<td align="left" valign="top" style="padding:10px 0px; border-bottom:1px solid #d6d6d6;">
			<table width="578" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td width="190" align="left" valign="middle" style="padding:10px 0px;">
			<a href="#"><img src="'.IMAGES_PATH_FRONT.'product/125/'.$image_name.'" width="107" height="51" border="0" alt="" title="" /></a>
			</td>
			<td width="206" align="left" valign="middle" style="padding:10px 0px;">
			<font style="font-size:15px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">'.$product_name.'</font><br />
			<font style="font-size:15px; color:#959595; font-family:Times New Roman, Times, serif; font-weight:bold;">Model: '.$product_model.'</font><br />
			<font style="font-size:12px; color:#000; font-family:Arial, Helvetica, sans-serif; font-weight:bold; text-transform:capitalize;">Hurry! Only '.$product_qty.' left in Stock</font>
			</td>
			<td width="182" align="right" valign="middle" style="padding:10px 20px 10px 0px;">
			<font style="font-size:15px; color:#000;  font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:capitalize;">Qty: '.$od_qty.'</font><br />
			<font style="font-size:21px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:capitalize; ">Rs. '.$product_sell.'</font>
			</td>
			</tr>
			</table>
			</td>
			</tr>';
 }
 
  $mailermessage='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Drishti- Product Purchase</title>
<style type="text/css">
body{ padding:0px; margin:0px;}
</style>
</head>

<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" valign="middle" height="92"><a href="#"><img src="'. IMAGES_PATH_FRONT.'mailer-images/logo.png" width="211" height="57" border="0" alt="" title="" /></a></td>
  </tr>
  <tr>
    <td align="center" valign="middle" height="40" bgcolor="#e1b215">
    	<table width="700" border="0" cellspacing="0" cellpadding="0" align="center" style="padding:0px 20px;">
  			<tr>
    			<td align="center" valign="middle" height="40" style="padding:0px 6px;">
    				<font style="font-size:14px; color:#fff; font-family: Times New Roman, Times, serif; text-transform:uppercase; font-weight:bold;" >
           			<a href="#" style="color:#FFFFFF; text-decoration:none;">EYEGLASSES</a>
        			</font>  
        		</td>
                <td align="center" valign="middle" height="40" style="padding:0px 6px;">
    				<font style="font-size:14px; color:#fff; font-family: Times New Roman, Times, serif; text-transform:uppercase; font-weight:bold;" >
           			<a href="#" style="color:#FFFFFF; text-decoration:none; padding:5px 10px;">SUNGLASSES</a>
                    </font>  
        		</td>
                <td align="center" valign="middle" height="40" style="padding:0px 6px;">
    				<font style="font-size:14px; color:#fff; font-family:Times New Roman, Times, serif; text-transform:uppercase; font-weight:bold;" >
           			<a href="#" style="color:#FFFFFF; text-decoration:none;">POWER SUNGLASSES</a>
        			</font>  
        		</td>
                <td align="center" valign="middle" height="40" style="padding:0px 6px;">
    				<font style="font-size:14px; color:#fff; font-family:Times New Roman, Times, serif; text-transform:uppercase; font-weight:bold;" >
           			<a href="#" style="color:#FFFFFF; text-decoration:none; padding:5px 10px;">CONTACT LENSES</a>
                    </font>  
        		</td>
                <td align="center" valign="middle" height="40" style="padding:0px 6px;">
    				<font style="font-size:14px; color:#fff; font-family: Times New Roman , Times, serif; text-transform:uppercase; font-weight:bold;" >
           			<a href="#" style="color:#FFFFFF; text-decoration:none; padding:5px 10px;">HOME TRY ON</a>
                    </font>  
        		</td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" style="background-color:#f9f9f9; padding:20px 40px; border:1px solid #e0e0e0;" >
    	<table width="618" border="0" cellspacing="0" cellpadding="0" align="center">
  			<tr>
    			<td align="left" valign="top" style="padding:10px 0px;">
                	<font style="font-size:30px; color:#000; font-family: Times New Roman, Times, serif; font-weight:bold; text-transform:capitalize; ">Congratulations '.$od_payment_first_name.' '.$od_payment_last_name.',</font>
                </td>
            </tr> <tr>
    			<td align="left" valign="top" style="padding:6px 0px;">
                	<font style="font-size:20px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; ">'.$thankmessage.'<br />Your order ID is #'.$orderId.'</font> <font style="font-size:14px; color:#000; font-family:Arial, Helvetica, sans-serif;">('.$oddate.' IST)</font>
                </td>
            </tr>
            <tr>
    			<td align="left" valign="top" style="padding:6px 0px;">
                	<font style="font-size:14px; color:#000; font-family:Arial, Helvetica, sans-serif;">You have done your part. Now sit back and enjoy! We�ll keep you posted about your
order, every step of the way, through SMS and Email. </font>
                </td>
            </tr>
            <tr>
    			<td align="left" valign="top" style="padding:6px 0px 15px;">
                	<font style="font-size:14px; color:#000; font-family:Arial, Helvetica, sans-serif;">Your order details are below. Thank you again for shopping with us.</font>
                </td>
            </tr><tr>
    			<td align="left" valign="top" >
                	<table width="616" border="0" cellspacing="0" cellpadding="0" align="center">
  							<tr>
    							<td width="280" align="left" valign="top" style="background-color:#fff; padding:10px 19px; border:1px solid #e0e0e0;">
                				<font style="font-size:20px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">Billing Address</font><br />
                                <img src="images/spacer.png" height="10" width="2" alt="" title="" /><br />
                                <font style="font-size:14px; color:#000; font-family:Arial, Helvetica, sans-serif;">'.$od_payment_first_name.' '.$od_payment_last_name.'<br> '.$od_payment_address1.', '. $od_payment_address2.'<br> '. $od_payment_city.'-'. $od_payment_state.', '. $od_payment_postal_code.', '. $od_payment_country.'.<br> T:'.$od_payment_phone;
							
							$mailermessage.='</font><br />
                                <img src="images/spacer.png" height="10" width="2" alt="" title="" />
                				</td>
                                <td width="45" align="left" valign="top" style="padding:10px 0px;">
                				<font style="font-size:20px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">&nbsp;</font>
                				</td>
                                <td width="280" align="left" valign="top" style="background-color:#fff; padding:10px 19px; border:1px solid #e0e0e0;">
                				<font style="font-size:20px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">Shipping Address</font><br />
                                <img src="images/spacer.png" height="10" width="2" alt="" title="" /><br />
                                <font style="font-size:14px; color:#000; font-family:Arial, Helvetica, sans-serif;">'.$od_shipping_first_name.' '.$od_shipping_last_name.'<br> '.$od_shipping_address1.', '. $od_shipping_address2.'<br> '. $od_shipping_city.'-'. $od_shipping_state.', '. $od_shipping_postal_code.', '. $od_shipping_country.'.<br> T:'.$od_shipping_phone;
							
							$mailermessage.='</font><br />
                                <img src="images/spacer.png" height="10" width="2" alt="" title="" />
                				</td>
                            </tr>
                    </table>
                </td>
            </tr><tr>
    			<td align="left" valign="top" style="padding:15px 0px 20px;">
                	<font style="font-size:20px; color:#000; font-family: Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">Payment Method:</font> <font style="font-size:20px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; ">'.$ordertype.'</font>
                </td>
            </tr>
			 <tr>
    			<td align="left" valign="top" style="background-color:#fff; padding:10px 19px; border:1px solid #e0e0e0;">
                		<table width="578" border="0" cellspacing="0" cellpadding="0" align="center">
  							<tr>
    							<td align="left" valign="top" style="padding:10px 0px;">
                				<font style="font-size:20px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">Product Summary</font>
                				</td>
                            </tr>
                              
                               '.$prdet.' 
                                                						   
                            
                            <tr>
    							<td align="left" valign="top" style="padding:2px 0px;">
                                    	<table width="578" border="0" cellspacing="0" cellpadding="0" align="center">
										<tr>
    											<td width="396" align="right" valign="middle" style="padding:5px 0px;">
                                                <font style="font-size:14px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">Sub Total:</font><br />
                                                <font style="font-size:11px; color:#b8b8b8; font-family:Arial, Helvetica, sans-serif; font-weight:bold; text-transform:capitalize;">Free Gift Wrapped &nbsp; </font>
                								</td>
                                                <td width="182" align="right" valign="middle" style="padding:5px 20px 5px 0px;">
                                                <font style="font-size:22px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:capitalize; ">Rs. '.$producttotal.'</font>
                								</td>
                                            </tr>	
  											<tr>
    											<td width="396" align="right" valign="middle" style="padding:5px 0px;">
                                                <font style="font-size:14px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:uppercase; ">Total:</font>
                								</td>
                                                <td width="182" align="right" valign="middle" style="padding:5px 20px 5px 0px;">
                                                <font style="font-size:22px; color:#e1b314; font-family:Times New Roman, Times, serif; font-weight:bold; text-transform:capitalize; ">Rs. '.$producttotal.'</font>
                								</td>
                                            </tr>
                                       	</table>
                            </tr>
                        </table>
                </td>
            </tr>
			
			';
			
		$orderemaf='<tr>
    			<td align="left" valign="top" style="padding:20px 0px 10px;">
                	<a href="#"><img src="'. IMAGES_PATH_FRONT.'mailer-images/track-bttn.gif" width="174" height="27" border="0" alt="Track your order" title="Track your order" /></a>
                </td>
            </tr>
            <tr>
    			<td align="left" valign="top" style="padding:6px 0px;">
                	<font style="font-size:14px; color:#000; font-family:Arial, Helvetica, sans-serif;">Our customer service team is committed to help you with all your needs 24x7. You can reach them online via chat or call them at 011-4567-4567 or email at <a href="mailto:support@drishti.com" style="text-decoration:none; color:#ed1c24;">support@drishti.com</a>. Thank you once again! </font>
                </td>
            </tr>
            <tr>
    			<td align="left" valign="top" style="padding:10px 0px;">
                	<font style="font-size:20px; color:#000; font-family:Times New Roman, Times, serif; font-weight:bold; ">Thank you,<br />Support Teams</font>
                </td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" height="48" >
    	<table width="700" border="0" cellspacing="0" cellpadding="0" align="center" style="padding:0px 20px;">
  			<tr>
    			<td align="right" valign="middle" width="320" height="48">
                	<font style="font-size:12px; color:#8f8d8d; font-family:Arial, Helvetica, sans-serif; ">Connect with us:</font>
                </td>
                <td align="left" valign="middle" width="380" height="44" style="padding:4px 0px 0px 10px;">
                <a href="#"><img src="'. IMAGES_PATH_FRONT.'mailer-images/twitter.jpg" width="23" height="22" border="0" alt="Twitter" title="Twitter" /></a> <a href="#"><img src="'. IMAGES_PATH_FRONT.'mailer-images/fb1.jpg" width="23" height="22" border="0" alt="Facebook" title="Facebook" /></a> <a href="#"><img src="'. IMAGES_PATH_FRONT.'mailer-images/linked.jpg" width="23" height="22" border="0" alt="LinkedIn" title="LinkedIn" /></a> <a href="#"><img src="'. IMAGES_PATH_FRONT.'mailer-images/pi.jpg" width="23" height="22" border="0" alt="Pinterest" title="Pinterest" /></a>
                </td>
            </tr>
        </table>    
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" height="35" >
    		<font style="font-size:12px; color:#8f8d8d; font-family:Arial, Helvetica, sans-serif; ">You have received this email because you have subscribed to the Delhi/NCR <a href="#" style="text-decoration:none; color:#ed1c24;">drishti.com</a> newsletter.<br />
If you prefer not to receive this email, you can always <a href="#" style="text-decoration:none; color:#ed1c24;">unsubscribe</a> with one click.</font>    
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" height="40" >
    		<font style="font-size:12px; color:#8f8d8d; font-family:Arial, Helvetica, sans-serif; ">Copyright � 2013 Drishti Platinum. All Right Reserved. <a href="#" style="text-decoration:none; color:#ed1c24;">T&amp;C</a> | <a href="#" style="text-decoration:none; color:#ed1c24;">Privacy Policy</a>.</font>    
    </td>
  </tr>
</table>


</body>

</html>';	
			$messageret= $mailermessage.$orderemaf;
			return $messageret;

}

 
}
	
?>