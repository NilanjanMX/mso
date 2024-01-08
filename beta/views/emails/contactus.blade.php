<html>
	<head>
		<title>Contact Us</title>
	</head>
	<body>
	    <p>Dear Masterstroke,</p>
	    <table>
			<tr><td>Name: {{ $name }}</td></tr>
			<tr><td>Email: {{ $email }}</td></tr>
			<tr><td>Phone Number: {{$phone_no}}</td></tr>
			<tr><td>Subject: {{$subject}}</td></tr>
			<tr><td>Message: {{$client_message}}</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>Thanks & Regards,</td></tr>
			<tr><td>{{ $name }}</td></tr>
		</table>
	</body>
</html>