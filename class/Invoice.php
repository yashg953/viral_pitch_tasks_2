<?php
class Invoice extends commonUsequery {
	
	private $invoiceUserTable = TABLE_PREFIX.'invoice_user_details';	
    private $invoiceClientTable = TABLE_PREFIX.'invoice_client_details';
	private $invoiceServiceTable = TABLE_PREFIX.'invoice_service_details';
	private $invoiceDetailsTable = TABLE_PREFIX.'invoice_details';
	private $bankDetailsTable = TABLE_PREFIX.'bank_details';
	private $invoicetermstable = TABLE_PREFIX.'invoice_terms';
	private $invoicelabeltable = TABLE_PREFIX.'invoice_labels';


	private $dbConnect = false;
   
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error($this->dbConnect));
		}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;            
		}
		return $data;
	}

	public function allQueryList($tablename,$whrere){
	
		return $this->QueryFieldMultipleSelect($tablename,$whrere ,' * ');
	}
	
	public function getInvoiceNo(){
		//$userId = $_SESSION['influencer_user'];
		//return $this->QueryFieldMultipleSelect($this->invoiceDetailsTable," where user_id = '".$userId."' order by order_id DESC LIMIT 1" ,'  ');
		return $this->QueryFieldMultipleSelect($this->invoiceDetailsTable," WHERE user_id = '".$_SESSION['influencer_user']."' order by order_id DESC LIMIT 1" ,' invoice_no, order_id ');
	}
	/*public function getBankUser(){
		$sqlQuery="SELECT user, mobile FROM ".$this->invoiceUserTable." ORDER BY order_id DESC LIMIT 1";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return $row;	
		}*/

		public function getInvoiceClient($invoiceId=0,$selectcol=''){

		$invoicewhere=" where user_id = '".$_SESSION['influencer_user']."' ";
		if($invoiceId>0){
			$invoicewhere = " where order_id = '$invoiceId'";
		}
		if($selectcol==''){
			$selectcol = ' * ';
		}
  
		return $this->QueryFieldMultipleSelect($this->invoiceClientTable,$invoicewhere ,$selectcol);

	}
	
	public function saveInvoice($POST) {
		//echo "<pre>";
		 $img = $_FILES['business_logo']['name'];
		    $tmp = $_FILES['business_logo']['tmp_name'];
		    // get uploaded file's extension
		    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
		    $size=$_FILES["business_logo"]["size"];
		    // can upload same image using rand function
		    if(!empty($ext)){
		    $final_image= 'business_logo_'.$_SESSION['influencer_user'].rand(1,1000).'.'.$ext;
		    // check's valid format
		    $path1 = 'logo/'.strtolower($final_image); 
		    move_uploaded_file($_FILES["business_logo"]["tmp_name"], $path1);
			}
		$amountPaid = empty($POST['amountPaid'])? 0:$POST['amountPaid'];
		$totalAftertax = empty($POST['totalAftertax'])? 0:$POST['totalAftertax'];
		$amountDue = empty($POST['amountDue'])? 0:$POST['amountDue'];
		$flexRadioDefault = empty($POST['taxtype'])? 0:$POST['taxtype'];
		$taxRate = empty($POST['taxRate'])? 0:$POST['taxRate'];
		$taxAmount = empty($POST['taxAmount'])? 0:$POST['taxAmount'];
		$subTotal = empty($POST['subTotal'])? 0:$POST['subTotal'];
		
		 
		 $totalAftertax = 0;
		 if(!empty($POST['amountPaid'])){
		 	$totalAftertax = $POST['totalAftertax'];

		 }
		 $sal ="user_id = '".$_SESSION['influencer_user']."',invoice_no='".$POST['invoice_no']."'";
		  $sqlInsertInvoice = "
			INSERT INTO ".$this->invoiceDetailsTable."(user_id,business_logo,invoice_heading,invoice_no, invoice_date, due_date, user_address, client_address, order_total_before_tax, order_total_tax,order_tax_per,order_total_after_tax,order_amount_paid,order_total_amount_due,gst, invoice_insert, invoice_update)
			VALUES ('".$_SESSION['influencer_user']."', '".$path1."', '".$POST['invoice_heading']."', '".$POST['invoice_no']."', '".$POST['datepicker1']."', '".$POST['datepicker2']."', '".$POST['user-drop']."', '".$POST['client-drop']."', '".$subTotal."', '".$taxAmount."', '".$taxRate."', '".$totalAftertax."', '".$amountPaid."', '".$amountDue."', '".$flexRadioDefault."', '".mktime()."', '".mktime()."')";
			//$this->dbQuery($sqlInsertInvoice,'');
			  $InvoceId = $this->QueryNewinsert($sqlInsertInvoice);
			

			//$InvoceId= mysqli_insert_id($this->dbConnect);
			//$InvoceId = $this->dbInsertId('');

		//$lastInsertId = $this->dbInsertId('');;
		for ($i = 0; $i < count($POST['productCode']); $i++) {
			$sqlInsertItem = "
			INSERT INTO ".$this->invoiceServiceTable."(order_id, user_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount, service_description, service_insert, service_update) 
			VALUES ('".$InvoceId."', '".$_SESSION['influencer_user']."', '".$POST['productCode'][$i]."', '".$POST['productName'][$i]."', '".$POST['quantity'][$i]."', '".$POST['price'][$i]."', '".$POST['total'][$i]."', '".$POST['serviceDes'][$i]."', '".mktime()."', '".mktime()."')";			
			//mysqli_query($this->dbConnect, $sqlInsertItem);
			$this->QueryNewinsert($sqlInsertItem);
		}

		for ($i = 0; $i < count($POST['terms']); $i++) {
			$sqlInsertItem = "
			INSERT INTO ".$this->invoicetermstable."(invoice_id, userid, terms) 
			VALUES ('".$InvoceId."', '".$_SESSION['influencer_user']."', '".$POST['terms'][$i]."')";			
			//mysqli_query($this->dbConnect, $sqlInsertItem);
			$this->QueryNewinsert($sqlInsertItem);
		}

		for ($i = 0; $i < count($POST['labelname']); $i++) {
			$sqlInsertItem = "
			INSERT INTO ".$this->invoicelabeltable."(order_id, userid, label_name,label_value) 
			VALUES ('".$InvoceId."', '".$_SESSION['influencer_user']."', '".$POST['labelname'][$i]."','".$POST['labelvalue'][$i]."')";			
			//mysqli_query($this->dbConnect, $sqlInsertItem);
			$this->QueryNewinsert($sqlInsertItem);
		}
		//die(mysqli_error($this->dbConnect)); 
		return $InvoceId;   	
	}
	public function updateInvoice($POST) {
		
		if($POST['invoiceId']) {	
			$sqlInsert = "
				UPDATE ".$this->invoiceClientTable." 
				SET client_country = '".$POST['client_country']."', client_name= '".$POST['client_name']."', client_email= '".$POST['client_email']."', client_mobile= '".$POST['client_mobile']."', client_gstin= '".$POST['client_gstin']."', client_pan_no= '".$POST['client_pan_no']."', client_street_address= '".$POST['client_street_address']."', client_state= '".$POST['client_state']."', client_city= '".$POST['client_city']."', client_zip_code= '".$POST['client_zip_code']."', note = '".$POST['notes']."', client_update = '".mktime()."' 
				WHERE user_id = '".$_SESSION['influencer_user']."' AND order_id = '".$POST['client-drop']."'";	
				$this->QueryNewinsert($sqlInsert,2);

			 $sqlInsertUser = "
					UPDATE ".$this->invoiceUserTable."
					SET country = '".$POST['country']."', user= '".$POST['user']."', email= '".$POST['email']."', mobile= '".$POST['mobile']."', gstin= '".$POST['gstin']."', pan_no= '".$POST['pan_no']."', street_address= '".$POST['street_address']."', gst_state= '".$POST['gst_state']."', city= '".$POST['city']."', zip_code= '".$POST['zip_code']."', user_update = '".mktime()."' 
				WHERE user_id = '".$_SESSION['influencer_user']."' AND order_id = '".$POST['user-drop']."'";	
				
				$this->QueryNewinsert($sqlInsertUser,2);
			
			$sqlInsertInvoice = "
					UPDATE ".$this->invoiceDetailsTable."
					SET invoice_no = '".$POST['invoice_no']."', invoice_date= '".$POST['datepicker1']."', due_date= '".$POST['datepicker2']."', user_address= '".$POST['user-drop']."', client_address= '".$POST['client-drop']."', order_total_before_tax = '".$POST['subTotal']."', order_total_tax = '".$POST['taxAmount']."', order_tax_per = '".$POST['taxRate']."', order_total_after_tax = '".$POST['totalAftertax']."', order_amount_paid = '".$POST['amountPaid']."', order_total_amount_due = '".$POST['amountDue']."', gst = '".$POST['flexRadioDefault']."', invoice_insert = '".mktime()."', invoice_update = '".mktime()."'
				WHERE user_id = '".$_SESSION['influencer_user']."' AND order_id = '".$POST['invoiceId']."' ";
				$this->QueryNewinsert($sqlInsertInvoice,2);
		}
				
		$this->deleteInvoiceItems($POST['invoiceId']);
		for ($i = 0; $i < count($POST['productCode']); $i++) {			
			$sqlInsertItem = "
				INSERT INTO ".$this->invoiceServiceTable."(order_id, user_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount, service_description, service_insert, service_update) 
				VALUES ('".$POST['invoiceId']."', '".$_SESSION['influencer_user']."', '".$POST['productCode'][$i]."', '".$POST['productName'][$i]."', '".$POST['quantity'][$i]."', '".$POST['price'][$i]."', '".$POST['total'][$i]."', '".$POST['serviceDes'][$i]."', '".mktime()."', '".mktime()."')";
				$this->QueryNewinsert($sqlInsertItem);		
		}
		  //die(mysqli_error($this->dbConnect));   	
	}
	public function updateUser($POST) {
		$sqlInsertUser = "
					UPDATE ".$this->invoiceUserTable."
					SET country = '".$POST['country']."', user= '".$POST['user']."', email= '".$POST['email']."', mobile= '".$POST['mobile']."', gstin= '".$POST['gstin']."', pan_no= '".$POST['pan_no']."', street_address= '".$POST['street_address']."', gst_state= '".$POST['gst_state']."', city= '".$POST['city']."', zip_code= '".$POST['zip_code']."', user_insert = '".mktime()."', user_update = '".mktime()."' 
				WHERE user_id = '".$_SESSION['influencer_user']."' AND order_id = '".$POST['invoiceId']."'";
				$this->QueryNewinsert($sqlInsertUser,2);
		}
		//die(mysqli_error($this->dbConnect));
	public function updateClient($POST) {
		$sqlInsert = "
		UPDATE ".$this->invoiceClientTable." 
		SET client_country = '".$POST['client_country']."', client_name= '".$POST['client_name']."', client_email= '".$POST['client_email']."', client_mobile= '".$POST['client_mobile']."', client_gstin= '".$POST['client_gstin']."', client_pan_no= '".$POST['client_pan_no']."', client_street_address= '".$POST['client_street_address']."', client_state= '".$POST['client_state']."', client_city= '".$POST['client_city']."', client_zip_code= '".$POST['client_zip_code']."', note = '".$POST['notes']."', client_insert = '".mktime()."', client_update = '".mktime()."' 
		WHERE user_id = '".$_SESSION['influencer_user']."' AND order_id = '".$POST['invoiceId']."'";
		$this->QueryNewinsert($sqlInsert,2);
		}

	/*public function getInvoiceList($invoiceId=0){
		$sqlAdd = " WHERE user_id = '".$_SESSION['influencer_user']."'";
		if($invoiceId>0){
           $sqlAdd = $sqlAdd." and order_id=".$invoiceId;
		}
		
		return $this->QueryFieldMultipleSelect($this->invoiceDetailsTable, $sqlAdd,' * ');
	}*/
	public function getInvoiceList(){
		
		return $this->QueryFieldMultipleSelect($this->invoiceDetailsTable," WHERE user_id = '".$_SESSION['influencer_user']."'" ,' * ');
	}

	public function getClientList(){
		
		return $this->QueryFieldMultipleSelect($this->invoiceClientTable," WHERE user_id = '".$_SESSION['influencer_user']."'" ,' * ');
	}
	public function getUserList(){	

		return $this->QueryFieldMultipleSelect($this->invoiceUserTable," WHERE user_id = '".$_SESSION['influencer_user']."'" ,' * ');
	}

	public function getInvoice($invoiceId){
	
		return $this->QueryFieldMultipleSelect($this->invoiceDetailsTable," where order_id = '".$invoiceId."' " ,' * ');
	}	
	public function getInvoiceUser($invoiceId=0,$selectcol=''){

		$invoicewhere=" where user_id = '".$_SESSION['influencer_user']."' ";
		if($invoiceId>0){
			$invoicewhere = " where order_id = '$invoiceId'";
		}
		if($selectcol==''){
			$selectcol = ' * ';
		}
  
		return $this->QueryFieldMultipleSelect($this->invoiceUserTable,$invoicewhere ,$selectcol);

	}
	public function getInvoiceItems($invoiceId){

		return $this->QueryFieldMultipleSelect($this->invoiceServiceTable," where order_id = '".$invoiceId."' " ,' * ');	
	}
	public function getInvoiceTerms($invoiceId,$userid){

		return $this->QueryFieldMultipleSelect($this->invoicetermstable," where invoice_id = '".$invoiceId."' and userid = '".$userid."' " ,' * ');	
	}
	public function getInvoiceDetails($invoiceId=0,$selectcol=''){
		
		$invoicewhere=" where user_id = '".$_SESSION['influencer_user']."' ";
		if($invoiceId>0){
			$invoicewhere = " where order_id = '$invoiceId'";
		}
		if($selectcol==''){
			$selectcol = ' * ';
		}
  
		return $this->QueryFieldMultipleSelect($this->invoiceClientTable,$invoicewhere ,$selectcol);
    

	}
	public function deleteInvoiceUser($invoiceId){

		return $this->QueryDelete($this->invoiceUserTable,''," WHERE order_id = '".$invoiceId."'" );
	}
	public function deleteInvoiceClients($invoiceId){

		return $this->QueryDelete($this->invoiceClientTable,''," WHERE order_id = '".$invoiceId."'" );
	}
	public function deleteInvoiceItems($invoiceId){
		
		return $this->QueryDelete($this->invoiceServiceTable,''," WHERE order_id = '".$invoiceId."'" );				
	}
	public function deleteBankDetails($invoiceId){

		return $this->QueryDelete($this->bankDetailsTable,''," WHERE id = '".$invoiceId."'" );		
	}
	public function deleteInvoice($invoiceId){

		return $this->QueryDelete($this->invoiceDetailsTable,''," WHERE order_id = '".$invoiceId."'" );
		$this->deleteInvoiceItems($invoiceId);
		$this->deleteBankDetails($invoiceId);	
		return 1;
		//die("dddddddddddddddd");
	}
	public function saveBankDetails($POST) {	
		$sqlbank = "		
			INSERT INTO ".$this->bankDetailsTable."(user_id, country, bank_name, account_no, cnfrm_account_no, ifsc, account_type, account_holder, phone, upi_id, paytm_id, invoice_id, bank_insert, bank_update) 
			VALUES ('".$_SESSION['influencer_user']."', '".$POST['country']."', '".$POST['bank_name']."', '".$POST['account_no']."', '".$POST['cnfrm_account_no']."', '".$POST['ifsc']."', '".$POST['account_type']."', '".$POST['account_holder']."', '".$POST['phone']."', '".$POST['upi_id']."', '".$POST['paytm_id']."', '".$POST['invoiceId']."', '".mktime()."', '".mktime()."')";
			$this->QueryNewinsert($sqlbank);
	}
	public function saveClient($POST) {
		  $sqlInsertClients = "
			INSERT INTO ".$this->invoiceClientTable."(user_id, client_country, client_name, client_email, client_mobile, client_gstin, client_pan_no, client_street_address, client_state, client_city, client_zip_code, note, client_insert, client_update) 
			VALUES ('".$_SESSION['influencer_user']."', '".$POST['client_country']."', '".$POST['client_name']."', '".$POST['client_email']."', '".$POST['client_mobile']."', '".$POST['client_gstin']."', '".$POST['client_pan_no']."', '".$POST['client_street_address']."', '".$POST['client_state']."', '".$POST['client_city']."', '".$POST['client_zip_code']."', '".$POST['notes']."', '".mktime()."', '".mktime()."')";		
			 $id = $this->QueryNewinsert($sqlInsertClients);
			//die("ddddddwwwwwwwwwwwwwwww");
			
		//return mysqli_insert_id($this->dbConnect);
		return $id;
	}
	public function saveUser($POST) {
		 $sqlInsertUsers = "
			INSERT INTO ".$this->invoiceUserTable."(user_id, country, user, email, mobile, gstin, pan_no, street_address, gst_state, city, zip_code, user_insert, user_update) 
			VALUES ('".$_SESSION['influencer_user']."', '".$POST['country']."', '".$POST['user']."', '".$POST['email']."', '".$POST['mobile']."', '".$POST['gstin']."', '".$POST['pan_no']."', '".$POST['street_address']."', '".$POST['gst_state']."', '".$POST['city']."', '".$POST['zip_code']."', '".mktime()."', '".mktime()."')";		
			//die("rrrrrrrrrrr");

			$id=  $this->QueryNewinsert($sqlInsertUsers);
			echo "<script>alert('New user added');</script>";
	   //return mysqli_insert_id($this->dbConnect);
	   return $id;
	}
	public function getBankDetails($invoiceId){

		

	 /*$sqlQuery = "
			SELECT * FROM ".$this->bankDetailsTable." 
			WHERE user_id = '".$_SESSION['influencer_user']."' AND invoice_id = '$invoiceId' ";
		return  $this->getData($sqlQuery);*/
		return $this->QueryFieldMultipleSelect($this->bankDetailsTable," where invoice_id = '".$invoiceId."' " ,' * ');	
	}
	public function getInvoiceUserAll(){

		return $this->QueryFieldMultipleSelect($this->invoiceUserTable," WHERE order_id = '".$_SESSION['influencer_user']."'" ,' * ');
	}

	public function getIndianCurrency(float $number)
		{
			$decimal = round($number - ($no = floor($number)), 2) * 100;
			$hundred = null;
			$digits_length = strlen($no);
			$i = 0;
			$str = array();
			$words = array(0 => '', 1 => 'one', 2 => 'two',
				3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
				7 => 'seven', 8 => 'eight', 9 => 'nine',
				10 => 'ten', 11 => 'eleven', 12 => 'twelve',
				13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
				16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
				19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
				40 => 'forty', 50 => 'fifty', 60 => 'sixty',
				70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
			$digits = array('', 'hundred','thousand','lakh', 'crore');
			while( $i < $digits_length ) {
				$divider = ($i == 2) ? 10 : 100;
				$number = floor($no % $divider);
				$no = floor($no / $divider);
				$i += $divider == 10 ? 1 : 2;
				if ($number) {
					$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
					$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
					$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
				} else $str[] = null;
			}
			$Rupees = implode('', array_reverse($str));
			$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
			return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
		}

}



