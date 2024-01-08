<html>
	<head>
		<title>Subscription Email</title>
	</head>
	<body>
	    <p>Dear {{ $name }},</p>
	    <p>Welcome to Masterstroke!</p>
	    <p>Your payment has been successfully processed and applied to your account. Below you will find details of the transaction:</p>
		<table>
			<tr><td>Name: {{ $name }}</td></tr>
			<tr><td>Email: {{ $email }}</td></tr>
			<tr><td>Mobile Number: {{$phone_no}}</td></tr>
			<!-- <tr><td>Created Date: { {$ da te} }</td></tr> -->
			<tr><td>Amount: INR {{$amount}}</td></tr>
			<tr><td>Product Name:Membership / Subscription</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>
				<a href="{{route('frontend.subscriptionCart',$subscription_id)}}">
					Pay Now
				</a>
			</td></tr>
			<tr><td>Please call us for any further clarification at 9883818627.</td></tr>
			<tr><td>Thanks & Regards,</td></tr>
			<tr><td>Team-Masterstroke</td></tr>
	</body>
</html>