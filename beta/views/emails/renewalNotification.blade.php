<html>
	<head>
		<title>Membership Point</title>
	</head>
	<body>
	    <p>Dear {{ $name }},</p>
	    {!! $email_header !!}
	    
		<p>Thank you for being a member of Masterstroke. We would like to inform you that your Membership is expiring on {{$expire_at}}. Please renew today and get {{$discount}}% discount.</p>
		
		{!! $email_footer !!}
	</body>
</html>