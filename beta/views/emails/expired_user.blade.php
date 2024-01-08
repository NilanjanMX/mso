<html>
	<head>
		<title>Membership Point</title>
	</head>
	<body>
	    <p>Dear {{ $name }},</p>
	    {!! $email_header !!}
	    
		<p>Your membership expires on  {{$expire_at}}. Please renew today .</p>
		
		{!! $email_footer !!}
	</body>
</html>