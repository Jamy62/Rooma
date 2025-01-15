<?php 
	include('dbConnect.php');

	if (isset($_POST['btnRegister'])) 
	{
		$uEmail = $_POST['txtuEmail'];
		$uName = $_POST['txtuName'];
		$uPassword = $_POST['txtuPassword'];
		$uPh = $_POST['txtuPh'];

        /*Profile img upload*/
		$uPp = $_FILES['txtuPp']['name'];
		$folder = "profileImg/";
		$uPpFileName = $folder."_".$uPp;
		$copy = copy($_FILES['txtuPp']['tmp_name'], $uPpFileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload picture.</p>";
		}

		/*Check password Format*/
		$passwordNumber = preg_match('@[0-9]@', $uPassword);

		$passwordUpperCase = preg_match('@[A-Z]@', $uPassword);
		$passwordLowerCase = preg_match('@[a-z]@', $uPassword);
		$passwordSpecial = preg_match('@[^\w]@', $uPassword);

		/*Check existing emails*/
		$checkuEmail = "SELECT * FROM users WHERE UserEmail = '$uEmail'";
		$result = mysqli_query($dbConnect, $checkuEmail);
		$count = mysqli_num_rows($result);

		if ($count > 0)
		{
			echo "<script>window.alert('The entered email already exists. Please try another one.')</script>";
			echo "<script>window.location='userRegister.php'</script>";
		}

		else if (strlen($uPassword)<8 || !$passwordNumber || !$passwordUpperCase || !$passwordLowerCase || !$passwordSpecial)
		{
			echo "<script>window.alert('Password must be at least 8 characters in length and must contain at least one uppercase, one lowercase and, one number, and one special character. ')</script>";
			echo "<script>window.location='userRegister.php'</script>";
		}

		else
		{
			$insert = "INSERT INTO users(UserEmail, UserName, UserPp, UserPassword, UserPh, UserBalance)
							VALUES('$uEmail','$uName', '$uPpFileName', '$uPassword', '$uPh', '0')";

			$finalInsert = mysqli_query($dbConnect, $insert);

			if ($finalInsert)
			{
				echo "<script>window.alert('User register successful.')</script>";
                echo "<script>window.location='userLogin.php'</script>";
			}
		}
	}
 ?>

 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="roomaStyle.css">
	<title></title>
</head>

<body id="RegisterBody">
	<div id="registerContainer">
		<div id="logoContainer">
	    	<img id="registerLogo" src="websiteImg/logo2.png"><br>
	    </div>

	    <form id="Register" action="userRegister.php" method="POST" enctype="multipart/form-data">
			<label>Email**</label>
			<input class="registerInfo" type="email" name="txtuEmail" placeholder="Email" required><br>

            <label>Name**</label>
			<input class="registerInfo" type="text" name="txtuName" placeholder="Name" required><br>

            <label>Profile picture</label>
		 	<input class="registerInfo" type="file" name="txtuPp" required/><br><br>

			<label>Password**</label>
			<input class="registerInfo" type="password" name="txtuPassword" placeholder="Password" required><br>

			<label>Phone number**</label>
			<input class="registerInfo" type="text" name="txtuPh" placeholder="Phone number" required><br><br>

	        <div class="g-recaptcha" data-sitekey="6LdXgSYpAAAAAGkBTQl7lMYqc45PaVgygIYEaqVH"></div><br>
			<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	        <input type="checkbox" required> Terms and conditions <br><br>
	        <input class="registerSubmit" type="submit" name="btnRegister" value="Register">
	    </form>
		<p>Already registered? <a href="userLogin.php">Log in!</a></p>
    </div>
</body>
</html>