<?php 
	session_start();
	include('dbConnect.php');

	if (isset($_POST['btnLogin'])) 
	{
		$uEmail = $_POST['txtuEmail'];
		$uPassword = $_POST['txtuPassword'];

		$check = "SELECT * FROM users WHERE UserEmail = '$uEmail' AND UserPassword = '$uPassword'";
		$result = mysqli_query($dbConnect, $check);
		$count = mysqli_num_rows($result);

		if ($count > 0) 
		{
			$array = mysqli_fetch_array($result);
			$uID = $array['UserID'];
			$uEmail = $array['UserEmail'];
			$uName = $array['UserName'];
			$uPp = $array['UserPp'];
			$uPh = $array['UserPh'];

			$_SESSION['uID'] = $uID;
			$_SESSION['uEmail'] = $uEmail;
			$_SESSION['uName'] = $uName;
			$_SESSION['uPp'] = $uPp;
			$_SESSION['uPh'] = $uPh;
		
			echo "<script>window.alert('User login successful.')</script>";
			echo "<script>window.location='guestMain.php'</script>";
		}

		else
		{
			if (isset($_SESSION['loginError'])) 
			{
				$countError = $_SESSION['loginError'];

				if ($countError == 1) 
				{
					echo "<script>window.alert('User login failed attampt 2.')</script>";
					$_SESSION['loginError'] = 2;
				}

				else if ($countError == 2)
				{
					echo "<script>window.alert('User login failed attampt 3.')</script>";
					echo "<script>window.location='timer.php?orig=u'</script>";
				}
			}

			else 
			{
				echo "<script>window.alert('User login failed attampt 1.')</script>";

				$_SESSION['loginError'] = 1;
			}
		}
	}

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="roomaStyle.css">
	<title></title>
</head>
<body id="loginBody"><br><br>
	<div id="loginContainer">
		<div id="logoContainer">
	    	<img id="loginLogo" src="websiteImg/logo2.png">
	    </div><br>

	    <form id="login" action="userLogin.php" method="POST">
	        <label>User Email</label>
	        <input class="loginInfo" type="email" name="txtuEmail" placeholder="Email" required><br>

	        <label>Password</label>
	        <input class="loginInfo" type="password" name="txtuPassword" placeholder="Password" required><br><br>

	        <div class="g-recaptcha" data-sitekey="6LdXgSYpAAAAAGkBTQl7lMYqc45PaVgygIYEaqVH"></div><br>
			<script src="https://www.google.com/recaptcha/api.js" async defer></script><br>

	        <input class="loginSubmit" type="submit" name="btnLogin" value="Login">
	    </form>
		<p>No account? <a href="userRegister.php">Create one!</a></p>
    </div>
</body>
</html>