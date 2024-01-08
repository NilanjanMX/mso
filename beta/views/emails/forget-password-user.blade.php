<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<table align="center" width="570" cellpadding="0" cellspacing="0" >
			<tr>
				<td>
					<h1>Hello!</h1>
					<p>You are receiving this email because we received a password reset request for your account.</p>
					<div style="text-align:center;">
					<a href="{{ $url }}" style="border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3490dc;border-top:10px solid #3490dc;border-right:18px solid #3490dc;border-bottom:10px solid #3490dc;border-left:18px solid #3490dc" target="_blank">Reset Password</a>
					</div>
					<p>This password reset link will expire in 60 minutes.</p>
					<p>If you did not request a password reset, no further action is required.</p>
					<p>Regards,<br> Masterstroke</p>
					<hr/>
					<p style="box-sizing:border-box;color:#3d4852;line-height:1.5em;margin-top:0;text-align:left;font-size:12px">If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below
						into your web browser: {{ $url }}</p>
				</td>
			</tr>
		</table>
	</body>
</html>