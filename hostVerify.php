<?php 
	include('dbConnect.php');
    session_start();

	if (!isset($_SESSION['uID'])) 
	{
		echo "<script>window.alert('Login Again.')</script>";
		echo "<script>window.location='userLogin.php'</script>";
	}

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    /*Check existing verification*/
	$checkVerification = "SELECT * FROM verifications 
						  WHERE UserID = '$uID'
                          AND UserAccountType = 'Host'";
    $result = mysqli_query($dbConnect, $checkVerification);
    $count = mysqli_num_rows($result);

    if ($count > 0)
    {
        echo "<script>window.alert('This account verification has already been submitted or has already been registered as a Host.')</script>";
        echo "<script>window.location='hostMain.php'</script>";
    }

	if (isset($_POST['btnVerify'])) 
	{
		$legalName = $_POST['txtLegalName'];
		$cID = $_POST['cbocID'];
		$idType = $_POST['cboIDType'];
		$idNumber = $_POST['txtIDNumber'];
        $verificationDate = date('Y-m-d');
        $verificationStatus = 'Pending';

        /*Selfie img upload*/
		$selfie = $_FILES['imgSelfie']['name'];
		$folder = "profileImg/";
		$selfieFileName = $folder."_".$selfie;
		$copy = copy($_FILES['imgSelfie']['tmp_name'], $selfieFileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload selfie.</p>";
		}

        /*ID front img upload*/
		$idFront = $_FILES['imgIDFront']['name'];
		$folder = "profileImg/";
		$idFrontFileName = $folder."_".$idFront;
		$copy = copy($_FILES['imgIDFront']['tmp_name'], $idFrontFileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload ID front picture.</p>";
		}

        /*ID back img upload*/
		$idBack = $_FILES['imgIDBack']['name'];
		$folder = "profileImg/";
		$idBackFileName = $folder."_".$idBack;
		$copy = copy($_FILES['imgIDBack']['tmp_name'], $idBackFileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload ID back picture.</p>";
		}

		$insert = "INSERT INTO verifications(UserAccountType, LegalName, Selfie, IDNumber, IDFront, IDBack, IDType, VerificationDate, VerificationStatus, UserID, CountryID)
				   VALUES('Host','$legalName', '$selfieFileName', '$idNumber', '$idFrontFileName', '$idBackFileName', '$idType', '$verificationDate', '$verificationStatus', '$uID', '$cID')";

		$verificationInsert = mysqli_query($dbConnect, $insert);

		if ($verificationInsert)
		{
			echo "<script>window.alert('Host account verification application has been submitted. We will reach back to you once your account is verified.')</script>";
            echo "<script>window.location='guestMain.php'</script>";
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
	    <form id="Register" action="hostVerify.php" method="POST" enctype="multipart/form-data">
			<label>LegalName**</label>
			<input class="registerInfo" type="text" name="txtLegalName" placeholder="Your legal name" required><br>

            <label>Selfie picture**</label>
		 	<input class="registerInfo" type="file" name="imgSelfie" required/><br><br>

			 <label>Country</label>
            <div>
                <?php 
			        $countrySelect = "SELECT * FROM countries";
			        $countryResult = mysqli_query($dbConnect, $countrySelect);

                    echo "<select name='cbocID' class='registerInfo'>";
			            while ($array = mysqli_fetch_array($countryResult)) 
			            { 
			                $cID = $array['CountryID'];
                            $cName = $array['CountryName'];

			                echo "<option value='$cID'>$cName</option>";
                        }
                    echo "</select>";
			    ?>
            </div>

            <label>ID type</label>
            <select name="cboIDType" class="registerInfo">
                <option value="Government ID">Government ID</option>
                <option value="Passport">Passport</option>
            </select>

            <label>ID number**</label>
			<input class="registerInfo" type="text" name="txtIDNumber" placeholder="The ID number on your chosen identification" required><br>

            <label>ID front picture**</label>
		 	<input class="registerInfo" type="file" name="imgIDFront" required/><br><br>

            <label>ID back picture**</label>
		 	<input class="registerInfo" type="file" name="imgIDBack" required/><br><br>

	        <input type="checkbox" required> Terms and conditions <br><br>
	        <input class="registerSubmit" type="submit" name="btnVerify" value="Save">
	    </form>
    </div>
</body>
</html>