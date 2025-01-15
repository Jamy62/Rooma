<?php 
	$orig = $_GET['orig'];
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="roomaStyle.css">
	<title></title>
</head>
<body>
	<div id="timer">
		<h1>Too many failed attempts</h1>
		<p>You will be redirected back to the Login Page after ten minutes. <span id="countDown"> 600 </span> seconds left.</p>

		<input id="orig" value="<?php echo $orig ?>" type="hidden">

		<script type="text/javascript">
			var seconds = 600;

			function updateCountDown()
			{
				document.getElementById('countDown').textContent = seconds;
				seconds--;

				var orig = document.getElementById('orig')

				if (seconds < 0) 
				{
					if (orig = "a") 
					{
						window.location.href = 'adminLogin.php';//redirect to login page
					}

					if (orig = "u") 
					{
						window.location.href = 'userLogin.php';//redirect to login page
					}
				}
			}

			setInterval(updateCountDown,1000);
		</script>
	</div>
</body>
</html>