<?php 

	header("Content-Type: text/html; charset=utf-8");

	//Processing Details
	$asset_key = '03e5515f-7cd8-49ce-9284-5d78ff1390d9';
	$processing_method = 'redirect'; // allowed values redirect/iframe	
	$allowed_processings = '[' + $_POST['form_processingid'] + ']'
	$processing_id = $_POST['form_processingid'];
	$template_id = 0;
	
	//HTML form details
	$form_customer_name = $_POST['Name_Surname'];
	$form_phone = '+'.$_POST['form_phone'];
	$form_email = $_POST['form_email'];
	$form_address = $_POST['form_address'];
	$form_country = 'UKR';
	$form_order_id = '12345';	
	$form_description = $_POST['form_description'];
	$form_sum = number_format($_POST['form_sum'], 2, '.', '');	
	$form_currency = $_POST['form_currency'];	

	$get_source = parse_url(getenv("HTTP_REFERER"));
	$source_domain = $get_source['host'];
				
	$ip = getenv('HTTP_CLIENT_IP')?:
		  getenv('HTTP_X_FORWARDED_FOR')?:
		  getenv('HTTP_X_FORWARDED')?:
		  getenv('HTTP_FORWARDED_FOR')?:
		  getenv('HTTP_FORWARDED')?:
		  getenv('REMOTE_ADDR');

	//Required variables	
	$option['TemplateId'] = $template_id;
	$option['ProcessingId'] = $processing_id;
	$option['AllowedProcessings'] = $allowed_processings;
	$option['CustomMerchantInfo'] = $form_description;
	$option['MerchantInternalOrderId'] = $form_order_id;
	$option['StatusURL'] = 'status';	
	$option['ReturnURL'] = 'return';
	$option['AssetPaymentsKey'] = $asset_key;
	$option['Amount'] = $form_sum;	
	$option['Currency'] = $form_currency;
	$option['IpAddress'] = $ip;
		
	//****Customer details and address****//
	$option['FirstName'] = $form_customer_name;
        $option['Email'] = $form_email;
        $option['Phone'] = $form_phone;
        $option['Address'] = $form_address;
	$option['CountryISO'] = $form_country;	
		
	//****Cart details****//
   	$option['Products'][$i]['ProductId'] = $form_order_id;
	$option['Products'][$i]['ProductName'] = 'Order #' + $form_order_id;
	$option['Products'][$i]['ProductPrice'] = $form_sum;
	$option['Products'][$i]['ProductItemsNum'] = 1;
	$option['Products'][$i]['ImageUrl'] = 'https://assetpayments.com/dist/css/images/product.png';   
		
	//var_dump($option);
	$data = base64_encode( json_encode($option) );

	//SEND POST WITH CURL
	$url = 'https://assetpayments.us/checkout/pay';
	$fields = ['data' => $data];
	$fields_string = http_build_query($fields);

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
	$result = curl_exec($ch);
	echo $result;
?>
