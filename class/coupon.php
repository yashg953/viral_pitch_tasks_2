<?php
	require_once 'query.class.php';
	class CouponClass extends  commonUsequery
	{
	
	function getCouponAmount($prodAmt123, $code, $user_id)
	{
	
	$date=date('Y-m-d');
	$msg= '';
	$discountAmount=0;
	$final_amount=0;
	$prodAmtfin=0;

		
	$condt="status=1 ";		
		if($this->GetInfoFunc($code, $condt,0) > 0)
		{
			$condt.=" and expiry_date >= '$date'";	//true, if  coupon not expire and activated. 
			if($this->GetInfoFunc($code, $condt,0) > 0)
			{
				$condt1=" user_id='".$user_id."'";	//true, if  coupon does not already used. 
				if($this->isJustUse($user_id,$code) == 0 )//&& $this->$this->GetInfoFunc($code, $condt1,0) == 0
				{
					//$condt.=" and used_date IS NULL and user_id IS NULL and od_id IS NULL";	//coupon ended
					$condt.=" and used_date ='0000-00-00' and user_id = 0 and od_id = 0";	
					if($this->GetInfoFunc($code, $condt,1) > 0)
					{
						//" and minimum_amount >= $prodAmt123 ";//true if product amount is greater than from coupon purchasing amount
						$row=$this->GetInfoFunc($code, $condt,1);
						if($prodAmt123 > 0 && $row['order_type']=='product_offer')
						{
							$checkamount=$value['minimum_amount'];
							$msg="Coupon valid for a minimum purchase of Rs.".$value['minimum_amount'];
						}else if($prodAmt123 > 0 && $row['order_type']=='minimum_amount'){
							$msg="Coupon valid for a minimum purchase of Rs.".$row['offer_value'] ;
							$checkamount=$row['offer_value'];
						}else{
							$msg="Coupon valid for a minimum purchase of Rs.".$value['minimum_amount'];
							$checkamount=$value['minimum_amount'];
						}
						if($prodAmt123 > 0 && $prodAmt123 >= $checkamount)
						{							 	
							$discount=$row['amount'];
							$auto_id=$row['id'];
							$offer_value=$row['offer_value'];
							$offer_type=$row['offer_type'];	
							$coupon_pd_id=$row['coupon_pd_id'];	
														
							$inserval="user_id =$user_id, coupon_code='$code', offer_type='$offer_type', offer_value='$offer_value', coupon_auto_id='$auto_id', coupon_pd_id_temp='$coupon_pd_id'";
							$result123 = $this->Queryinsert('dri_coupon_temp_status',$inserval,"not insert");
															
							$updateid=$this->dbInsertId();
							$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);
							
							
							if($row['order_type']=='minimum_amount')
							{
								$discountAmount = $amount_details['discount'];
								$productName = "";	 								
								$prodAmtfin = $discountAmount;
								$msg= '';
								if($productName!='')
								{
									$discountAmount=0;
								}											
																
								$whercon=" WHERE id=$updateid";
								$inserval=" discount_amount = $discountAmount";
								$result123 = $this->Queryupdate('dri_coupon_temp_status',$inserval,"not insert",$whercon);		
								
								$success=1;	
								$_SESSION['coupon_order']=$row['offer_value'];		
								
							}else{
							
								if($amount_details['discount'] > 0)
								{ 
									$discountAmount = $amount_details['discount'];
									$productName = "";	
									if($prodAmt123>$discountAmount)
									{
											$totalamountvalue=$discountAmount+$value['minimum_amount'];
											if($prodAmt123>$totalamountvalue)
											{
											
												$prodAmtfin = $prodAmt123-$discountAmount;
												$msg= '';
												if($productName!='')
												{
													$discountAmount=0;
												}											
																								
												$whercon=" WHERE id=$updateid";
												$inserval="discount_amount	=$discountAmount";
												$result123 = $this->Queryupdate('dri_coupon_temp_status',$inserval,"not insert",$whercon);	
												
												$success=1;											
												
											}
											else
											{
												
												//$prodAmt123=getTotalOrderAmount($coupon_pd_id);
												$discountAmount =$amount_details['discount'];
												$prodAmtfindif = ($discountAmount+$value['minimum_amount'])-$prodAmt123;
												$productName = "";									
												$prodAmtfin=$prodAmt123 ;			
												$msg="You need to add Rs.".number_format($prodAmtfindif,2,'.','')." worth of beverages more to use this coupon." ;
												$success=0;
												$tbl=" dri_coupon_temp_status ";
												$condt=" where user_id='$user_id'";
												$message="Coupon Deleted.";
												$this->QueryDelete($tbl,$message,$condt);
											}	
									}
									else
									{
										echo 'here ';
										$discountAmount =$amount_details['discount'];
										$prodAmtfindif = ($discountAmount)-$prodAmt123;
										$productName = "";									
										$prodAmtfin=$prodAmt123 ;			
										$msg="You need to add Rs.".number_format($prodAmtfindif,2,'.','')." worth of beverages more to use this coupon." ;
										$success=0;
										
										$tbl=" dri_coupon_temp_status ";
										$condt=" where user_id='$user_id'";
										$message="Coupon Deleted.";
										$this->QueryDelete($tbl,$message,$condt);		
										
									}								
								}
								else
								{		
									$colmn1=' product_name ';
									$tbl1=" dri_product ";
									$condt1=" where product_id=".$coupon_pd_id;
									$data = $this->QueryFieldMultipleSelect($tbl1, $condt1,$colmn1);								
									
									$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);	//TE9121(50), PE6811 (100) 
									$discountAmount = $amount_details['discount'];
									$productName = $data[0]['product_name'];	
											
									$prodAmtfin=$prodAmt123 ;							
									$success=0;								
									$msg="Aah! you must add ".$productName." to your bag to use this coupon discount.";								
								}
							}
						 }else{
							$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);	//TE9121(50), PE6811 (100) 
							$discountAmount = $amount_details['discount'];
							$productName = "";	 			
							$prodAmtfin=$prodAmt123 ;			
							$success=0;
						 }
					}else{
						$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);	//TE9121(50), PE6811 (100) 
						$discountAmount =$amount_details['discount'];
						$productName = "";									
						$prodAmtfin=$prodAmt123 ;			
						$msg="Coupon ended." ;
						$success=0;
					}	
				}else{
					$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);	//TE9121(50), PE6811 (100) 
					$discountAmount =$amount_details['discount'];
					$productName = ""	; 									
					$prodAmtfin=$prodAmt123 ;			
					$msg="You have already used coupon." ;
					$success=0;
				}
			}else{
				$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);	//TE9121(50), PE6811 (100) 
				$discountAmount =$amount_details['discount'];
				$productName = ""	 ;									
				$prodAmtfin=$prodAmt123 ;			
				$msg="Coupon expired" ;	
			}
		}else{
			$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);	//TE9121(50), PE6811 (100) 
			$discountAmount =$amount_details['discount'];
			$productName = ""	 ;									
			$prodAmtfin=$prodAmt123 ;	
			$msg="Invalid coupon code." ;	
			$success=0;
		}
	/*}else{
		$amount_details = $this->getTotalCopuonAmount($prodAmt123,$user_id);	//TE9121(50), PE6811 (100) 
		$discountAmount = ($amount_details['discount']);
		$productName = ""	 ;									
		$prodAmtfin=$prodAmt123 ;	
		$msg=$value['msg'] ;	
		$success=0;
	}	*/
			
		$cat = array();
		$cat[]=array('prodAmtfin'=>$prodAmtfin,
					  'discountAmount'=>$discountAmount,
					  'product_name'=>$productName,
					  'msg'=>$msg,
					  'success'=>$success
					  );
					 
		return $cat;
	}

	function getTotalCopuonAmount($total_order_amount, $userId)
	{
		$colmn=' * ';
		$tbl=" dri_coupon_temp_status ";
		$condt=" where user_id='$userId'";
		$row= $this->QueryFieldSelect($tbl, $condt,$colmn);
			
		extract($row);
		if($coupon_pd_id_temp > 0){
							
				$colmn=' ct_id,ct_qty ';
				$tbl=" dri_cart ";
				$condt=" where ct_session_id='$user_id' and pd_id = ".$coupon_pd_id_temp;
				$rowcart = $this->QueryFieldMultipleSelect($tbl, $condt,$colmn);						
					
				if(count($rowcart)>0)
				{
					$colmn1=' product_sell ';
					$tbl1=" dri_product ";
					$condt1="where product_id=$coupon_pd_id_temp";
					$data = $this->QueryFieldMultipleSelect($tbl1, $condt1,$colmn1);					
					$pd_price=$data[0]['product_sell'];
					
					if($offer_type=='fixed'){
					
						$discount=$offer_value * $rowcart[0]['ct_qty'];
					
					}else if($offer_type=='percent'){
					
						$discount=(($pd_price * $offer_value)/100) *  $rowcart[0]['ct_qty'];		
						$precent=$offer_value;	
					}
				}else{
					$tbl=" dri_coupon_temp_status ";
					$condt=" where user_id='$userId'";
					$message="Coupon Deleted.";
					$this->QueryDelete($tbl,$message,$condt);					
					$discount=0;
					$precent=0;	
				}
				
		}else{		
		
			$colmn=' order_type, offer_type';
			$tbl=" dri_coupons ";
			$condt=" where coupon_code='".$coupon_code."'";
			$rowcop= $this->QueryFieldMultipleSelect($tbl, $condt,$colmn);
			if($rowcop[0]['order_type']=='minimum_amount')
			{
					$discount=0;
					$precent=0;	
			}else{	
				if($offer_type == 'percent')
				{
					$discount=(($total_order_amount * $offer_value)/100);		
					$precent=$offer_value;		
				}
				else if($offer_type == 'fixed')
				{
					$discount=$offer_value;	
				}
			}
		}
		
		$tag=array('discount'=>$discount,'percent'=>$precent);

		return $tag;
	}



	function GetInfoFunc($code, $condt,$val)				/////
	{	
		$colmn=' minimum_amount,offer_type, offer_value, coupon_pd_id, order_type,id';
		$tbl=" dri_coupons ";
		$condt=" where coupon_code='$code' and  $condt";
		$data= $this->QueryFieldMultipleSelect($tbl, $condt,$colmn);
			
			
			if($val==0){
				return count($data);
			}else{
				return $data[0];
			}
	}


	function isJustUse($user_id, $code)
	{
		$colmn=' id ';
		$tbl=" dri_coupon_temp_status ";
		$condt=" where user_id=".$user_id;
		$data= $this->QueryRowFieldSelect($tbl, $condt,$colmn);
		return $data;
	   
	}	
	
		function getCouponcode()
		{
			$code='';
			$charArray=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','U','R','S','T','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
			
			for($i=1 ; $i <= 5; $i++)
			{
			  $code.= $charArray[array_rand($charArray)];	
			}
			
			$productColumn=' coupon_code ';
			$producttable=" dri_coupons ";
			$productwhere=" where coupon_code='$code' order by coupon_code asc";
			$pd_list= $this->QueryFieldMultipleSelect($producttable, $productwhere,$productColumn);

			if(count($pd_list)> 0)
			{
				getCouponcode();
			}
			else
			{
				return $code;
			}
		}
		
		
		function getOfferDetail($offer_type,$offer_value,$order_type)
		{
			if($order_type=='product_offer')
			{
				if($offer_type=='percent')
				{
					return $offer_value.'% ';
				}
				else if($offer_type=='fixed')
				{
					return 'Rs.'.$offer_value;
				}
			}
			else if($order_type=='price_offer')
			{
				if($offer_type=='percent')
				{
					return $offer_value.'% ';
				}
				else if($offer_type=='fixed')
				{
					return 'Rs.'.$offer_value;
				}
			}
			else
			{
			return 'Minimum Order amount Rs.'.$offer_value;
			}

		}
		
	

	function CountTotalRecord($str)
	{
		$couponColumn=' id ';
		$coupontable="dri_coupons";
		$couponwhere=" where coupon_group_id='$str'";
		$couponlist= $this->QueryFieldMultipleSelect($coupontable, $couponwhere,$couponColumn);
		return count($couponlist);	
	
	}

	function countUsedCoupons($value)
	{
		$couponColumn=' id ';
		$coupontable=" dri_coupons as A, dri_order_final as B ";
		$couponwhere=" where A.od_id = B.od_id and A.coupon_group_id='$value' and  A.used_date <>'0000-00-00' ";
		$couponlist= $this->QueryFieldMultipleSelect($coupontable, $couponwhere,$couponColumn);
		return count($couponlist);
	}
	
	function emptyCouponDetails($userId)
	{
		$tbl=" dri_coupon_temp_status ";
		$condt=" where user_id='$userId'";
		$message="Coupon Deleted.";
		$this->QueryDelete($tbl,$message,$condt);	
		return true;
	}
	
	
}	
	
?>