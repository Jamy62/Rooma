<?php 
	session_start();
	include('dbConnect.php');

	if (isset($_POST['btnLogin'])) 
	{
		$aEmail = $_POST['txtaEmail'];
		$aPassword = $_POST['txtaPassword'];

		$check = "SELECT * FROM admins WHERE AdminEmail = '$aEmail' AND AdminPassword = '$aPassword'";
		$result = mysqli_query($dbConnect, $check);
		$count = mysqli_num_rows($result);

		if ($count > 0) 
		{
			$array = mysqli_fetch_array($result);
			$aID = $array['AdminID'];
			$aEmail = $array['AdminEmail'];
			$aName = $array['AdminName'];
			$aPp = $array['AdminPp'];
			$aPh = $array['AdminPh'];

			$_SESSION['aID'] = $aID;
			$_SESSION['aEmail'] = $aEmail;
			$_SESSION['aName'] = $aName;
			$_SESSION['aPp'] = $aPp;
			$_SESSION['aPh'] = $aPh;
		
			echo "<script>window.alert('Admin login successful.')</script>";
			echo "<script>window.location='adminMain.php'</script>";
		}

		else
		{
			if (isset($_SESSION['loginError'])) 
			{
				$countError = $_SESSION['loginError'];

				if ($countError == 1) 
				{
					echo "<script>window.alert('Admin login failed attampt 2.')</script>";
					$_SESSION['loginError'] = 2;
				}

				else if ($countError == 2)
				{
					echo "<script>window.alert('Admin login failed attampt 3.')</script>";
					echo "<script>window.location='timer.php?orig=a'</script>";
				}
			}

			else 
			{
				echo "<script>window.alert('Admin login failed attampt 1.')</script>";

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
	<link rel="stylesheet" href="adminStyle.css">
	<title></title>
</head>
<body id="loginBody"><br><br>
	<div id="loginContainer">
		<div id="logoContainer">
	    	<img id="loginLogo" src="websiteImg/logo2.png"><br>
	    </div>

	    <form id="login" action="adminLogin.php" method="POST">
	        <label>Admin Email</label>
	        <input class="loginInfo" type="email" name="txtaEmail" placeholder="Email" required><br>

	        <label>Password</label>
	        <input class="loginInfo" type="password" name="txtaPassword" placeholder="Password" required><br><br>

	        <div class="g-recaptcha" data-sitekey="6LdXgSYpAAAAAGkBTQl7lMYqc45PaVgygIYEaqVH"></div><br>
			<script src="https://www.google.com/recaptcha/api.js" async defer></script><br>

	        <input class="loginSubmit" type="submit" name="btnLogin" value="Login">
	    </form>
    </div>
</body>
</html>