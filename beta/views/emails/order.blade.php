<html>
	<head>
		<title>Masterstroke Order Email</title>
	</head>
	<body>

	    <p>Dear {{ $name }},</p>
	    <p>Welcome to Masterstroke!</p>
	    <p>Your payment has been successfully processed. Please find below the details of the transaction.</p>
		<table>
			<tr><td>Name: {{ $name }}</td></tr>
			<tr><td>Email: {{ $email }}</td></tr>
			<tr><td>Mobile Number: {{$phone_no}}</td></tr>
			<tr><td>Order ID: {{$order->invoice_id}}</td></tr>
			<tr><td>Payment Date: {{$date}}</td></tr>
			
		</table>
		<br>
		<table style="font-family: arial, sans-serif; border-collapse: collapse;  width: 100%;">
		    <tr>
                <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Product Name</th>
                <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Quantity</th>
                <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Price</th>
            </tr>
			 @foreach($orderItems as $orderItem)
			<tr>
			    <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$orderItem['product_name']}}</td>
			    <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{$orderItem['quantity']}}</td>
			    <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">INR {{$orderItem['price']}}</td>
			 </tr>
			 @endforeach
			<tr>
			    
			    <td colspan="2" style="border: 1px solid #dddddd;text-align: right;padding: 8px;">Amount</td>
			    <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">INR {{$order->total_amount}}</td>
			</tr>
			<tr>
			    
			    <td colspan="2" style="border: 1px solid #dddddd;text-align: right;padding: 8px;">Discount Amount</td>
			    <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">INR {{$order->coupon_amount}}</td>
			</tr>
			<tr>
			    
			    <td colspan="2" style="border: 1px solid #dddddd;text-align: right;padding: 8px;">Pay Amount</td>
			    <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">INR {{$order->payable_amount}}</td>
			</tr>
			
			@if($store_type == 'package')
    			<tr><td>&nbsp;</td></tr>
    			<tr><td>You will get branding image download link within 48 hrs on your registered mail id</td></tr>
    		@endif
			
			<tr><td>&nbsp;</td></tr>
			<tr><td>Please call us for any further clarification at 9883818627.</td></tr>
			<tr><td>Thanks & Regards,</td></tr>
			<tr><td>Team-Masterstroke</td></tr>
		</table>
	</body>
</html>