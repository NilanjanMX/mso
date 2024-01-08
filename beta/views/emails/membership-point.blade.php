<html>
	<head>
		<title>Membership Point</title>
	</head>
	<body>
	    <p>Dear {{ $name }},</p>
	    {!! $email_header !!}
		<p>Total amount before tax Rs. {{$total_amount}}</p>
		<p>Points credited : {{$membership_point_email}}</p>
		{!! $email_footer !!}
	</body>
</html>