<html>
	<head>
		<title>Masterstroke Order Email</title>
	</head>
	<body>

	    <p>Dear {{ $name }},</p>
	    <p>Welcome to Masterstroke!</p>
		<table>
			<tr><td>Name: {{ $name }}</td></tr>
			<tr><td>Email: {{ $email }}</td></tr>
			<tr><td>Date: {{$date}}</td></tr>
			<tr><td>Order ID: {{$invoice_id}}</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>Your order is ready for delivery.</td></tr>
			<tr><td>Portrait : Click here for <a href="{{$portrait}}">Download</a></td></tr>
			<tr><td>Landscape : Click here for <a href="{{$landscape}}">Download</a></td></tr>
		</table>
		<br>
		<table>
			
			<tr><td>&nbsp;</td></tr>
			<tr><td>Please call us for any further clarification at 9883818627.</td></tr>
			<tr><td>Thanks & Regards,</td></tr>
			<tr><td>Team-Masterstroke</td></tr>
		</table>
	</body>
</html>