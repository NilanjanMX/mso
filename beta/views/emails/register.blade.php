<html>
	<head>
		<title>Register Email</title>
	</head>
	<body>
		<table>
			<tr><td>Dear {{ $name }}!</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>Your account has been successfully created.<br>
			Your account information is as below:</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>Name: {{$name}}</td></tr>
			<tr><td>Email: {{ $email }}</td></tr>
			<tr><td>Phone No: {{$phone_no}}</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>You get 15 days trial.</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>Thanks & Regards,</td></tr>
			<tr><td>Team-Masterstroke</td></tr>
	</body>
</html>