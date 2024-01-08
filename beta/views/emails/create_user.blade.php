<html>
	<head>
		<title>User</title>
	</head>
	<body>
	    <p>Dear {{ $name }},</p>
	    <p>Welcome to Masterstroke!</p>
	    <p>Your account has been created at www.masterstrokeonline.com by {{$master_name}} through our multi-user feature.</p>
	    <p>Please find below your login-id (email id) and the system generated password.</p>
	    <table>
			<tr><td>Email : {{ $email }}</td></tr>
			<tr><td>Password : {{$password}}</td></tr>
			<tr><td>It is highly recommended that you change your password immediately through the option available under My Account -> Change Password section.</td></tr>
			<tr><td>Click on the link below to log-in and start using the features of MasterStrokeOnline</td></tr>
			<tr><td><a href="https://masterstrokeonline.com/login">https://masterstrokeonline.com/login</a></td></tr>
			<tr><td>Please call us for any further clarification at 9883818627 or write to us at <a href="mail:info@masterstrokeonline.com">info@masterstrokeonline.com</a></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>Thanks & Kind Regards,</td></tr>
			<tr><td>Team MasterStroke</td></tr>
			<tr><td><a href="www.masterstrokeonline.com">www.masterstrokeonline.com</a></td></tr>
		</table>
	</body>
</html>