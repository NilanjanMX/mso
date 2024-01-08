<html>
	<head>
		<title>Subscription Email</title>
	</head>
	<body>
	    <p>Dear {{ $name }},</p>
	     {!! $email_header !!}
		<table>
			<tr><td>Name: {{ $name }}</td></tr>
			<tr><td>Email: {{ $email }}</td></tr>
			<tr><td>Mobile Number: {{$phone_no}}</td></tr>
			<tr><td>Payment Date: {{$date}}</td></tr>
			<tr><td>Amount: INR {{$amount}}</td></tr>
			<tr><td>Product Name:Membership / Subscription</td></tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		 {!! $email_footer !!}
	</body>
</html>