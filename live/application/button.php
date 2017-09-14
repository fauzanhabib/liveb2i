<html>
<head>
	<title></title>
</head>
<body>

	<div id="name">..</div>
	<div id="email">..</div>

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script type="text/javascript">
		$.post( "http://idbuild.id.dyned.com/cookies/server.php", function( data ) {
			data = jQuery.parseJSON( data );
			$( "#name" ).html( data.name );
			$( "#email" ).html( data.email );
		});
	</script>
</body>
</html>
