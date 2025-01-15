<?php 
	include('dbConnect.php');

	if (isset($_POST['btnRegister'])) 
	{
		$aEmail = $_POST['txtaEmail'];
		$aName = $_POST['txtaName'];
		$aPassword = $_POST['txtaPassword'];
		$aPh = $_POST['txtaPh'];

        /*Profile img upload*/
		$aPp = $_FILES['txtaPp']['name'];
		$folder = "profileImg/";
		$aPpFileName = $folder."_".$aPp;
		$copy = copy($_FILES['txtaPp']['tmp_name'], $aPpFileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload picture.</p>";
		}

		/*Check password Format*/
		$passwordNumber = preg_match('@[0-9]@', $aPassword);

		$passwordUpperCase = preg_match('@[A-Z]@', $aPassword);
		$passwordLowerCase = preg_match('@[a-z]@', $aPassword);
		$passwordSpecial = preg_match('@[^\w]@', $aPassword);

		/*Check existing emails*/
		$checkaEmail = "SELECT * FROM admins WHERE AdminEmail = '$aEmail'";
		$result = mysqli_query($dbConnect, $checkaEmail);
		$count = mysqli_num_rows($result);

		if ($count > 0)
		{
			echo "<script>window.alert('The entered email already exists. Please try another one.')</script>";
			echo "<script>window.location='adminRegister.php'</script>";
		}

		else if (strlen($aPassword)<8 || !$passwordNumber || !$passwordUpperCase || !$passwordLowerCase || !$passwordSpecial)
		{
			echo "<script>window.alert('Password must be at least 8 characters in length and must contain at least one uppercase, one lowercase and, one number, and one special character. ')</script>";
			echo "<script>window.location='adminRegister.php'</script>";
		}

		else
		{
			$roomaBalanceSelect = "SELECT RoomaBalance FROM admins";
			$result = mysqli_query($dbConnect, $roomaBalance);
			$roomaBalanceArray = mysqli_fetch_array($result);
			$roomaBalance = $roomaBalanceArray['RoomaBalance'];

			$insert = "INSERT INTO admins(AdminEmail, AdminName, AdminPp, AdminPassword, AdminPh, RoomaBalance)
					   VALUES('$aEmail','$aName', '$aPpFileName', '$aPassword', '$aPh', '$roomaBalance')";

			$finalInsert = mysqli_query($dbConnect, $insert);

			if ($finalInsert)
			{
				echo "<script>window.alert('Admin register successful.')</script>";
			}
		}
	}
 ?>

 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="adminStyle.css">
	<title></title>
</head>

<body id="RegisterBody">
	<div id="registerContainer">
		<div id="logoContainer">
	    	<img id="registerLogo" src="websiteImg/logo2.png"><br>
	    </div>

	    <form id="Register" action="adminRegister.php" method="POST" enctype="multipart/form-data">
			<label>Email**</label>
			<input class="registerInfo" type="email" name="txtaEmail" placeholder="Email" required><br>

            <label>Name**</label>
			<input class="registerInfo" type="text" name="txtaName" placeholder="Name" required><br>

            <label>Profile picture</label>
		 	<input class="registerInfo" type="file" name="txtaPp" required/><br><br>

			<label>Password**</label>
			<input class="registerInfo" type="password" name="txtaPassword" placeholder="Password" required><br>

			<label>Phone number**</label>
			<input class="registerInfo" type="text" name="txtaPh" placeholder="Phone number" required><br><br>

	        <div class="g-recaptcha" data-sitekey="6LdXgSYpAAAAAGkBTQl7lMYqc45PaVgygIYEaqVH"></div><br>
			<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	        <input type="checkbox" required> Terms and conditions <br><br>
	        <input class="registerSubmit" type="submit" name="btnRegister" value="Register">
	    </form>
    </div>
</body>
</html>