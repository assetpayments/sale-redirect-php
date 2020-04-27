<?php 

	header("Content-Type: text/html; charset=utf-8");
		
	$json = json_decode($_POST['order_json'], true);	
		
	//****Get Insales data mandatory for callback request signature***//		
	$processing_id = 1635;
	$allowed_processings = [1635];
	$asset_key = '49302ec8-5a1c-4c30-a788-5b1b00687e20';

	$get_source = parse_url(getenv("HTTP_REFERER"));
	$source_domain = $get_source['host'];
				
	$ip = getenv('HTTP_CLIENT_IP')?:
		  getenv('HTTP_X_FORWARDED_FOR')?:
		  getenv('HTTP_X_FORWARDED')?:
		  getenv('HTTP_FORWARDED_FOR')?:
		  getenv('HTTP_FORWARDED')?:
		  getenv('REMOTE_ADDR');

	//****Request mandatory variables****//	
	$option['TemplateId'] = 0;
	$option['ProcessingID'] = $processing_id;
	$option['AllowedProcessings'] = $allowed_processings;
	$option['CustomMerchantInfo'] = 'comments';
	$option['MerchantInternalOrderId'] = '12345';
	$option['StatusURL'] = 'status';	
	$option['ReturnURL'] = 'return';
	$option['AssetPaymentsKey'] = $asset_key;
	$option['Amount'] = 100;	
	$option['Currency'] = 'UAH';
	$option['IpAddress'] = $ip;
		
	//****Customer details and address****//
	$option['FirstName'] = 'Name';
        	$option['Email'] = 'test@test.com';
        	$option['Phone'] = '555666777';
        	$option['Address'] = 'addrr';
	$option['CountryISO'] = 'UKR';
		
		
	//****Cart details****//
	//for($i = 0, $size = count($json['order_lines']); $i < $size; ++$i) {
	//   $option['Products'][$i]['ProductId'] = $json['order_lines'][$i]['product_id'];
	//   $option['Products'][$i]['ProductName'] = $json['order_lines'][$i]['title'];
	//   $option['Products'][$i]['ProductPrice'] = $json['order_lines'][$i]['sale_price'];
	//   $option['Products'][$i]['ProductItemsNum'] = $json['order_lines'][$i]['quantity'];
	//   $option['Products'][$i]['ImageUrl'] = 'https://assetpayments.com/dist/css/images/product.png';   
	//}
		
	//****Delivery method****//
	//$option['Products'][] = array(
	//'ProductId' => 1,
	//'ProductName' => $json['delivery_description'],
	//'ProductPrice' => $json['delivery_price'],
	//'ProductItemsNum' => 1,
	//'ImageUrl' => 'https://assetpayments.com/dist/css/images/delivery.png',
	//);
		
	//var_dump($option);


	$data = base64_encode( json_encode($option) );

	//SEND POST WITH JS

        	//echo sprintf('
            	//<form method="POST" id="checkout" action="https://assetpayments.us/checkout/pay" accept-charset="utf-8">
                	//<input type="hidden" name="data" value='.$data.' />                
            	//</form>'
        	//);
	//echo "<script type=\"text/javascript\"> 
                	//window.onload=function(){
                    	//document.forms['checkout'].submit();
                	//}
	//</script>";

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