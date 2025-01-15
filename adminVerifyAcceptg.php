<?php 
	include('dbConnect.php');
    session_start();

	$vID = $_GET['vID'];
	$uID = $_GET['uID'];
    $aID = $_SESSION['aID'];

	$accept = "UPDATE verifications SET
               VerificationStatus = 'Accepted',
               AdminID = '$aID'
               WHERE VerificationID = '$vID'";
    
    $accept2 = "UPDATE users SET
                GuestVerificationStatus = 'Verified'
				WHERE UserID = '$uID'";

	$query = mysqli_query($dbConnect, $accept);
	$query2 = mysqli_query($dbConnect, $accept2);

	if ($query and $query2) 
	{
		echo "<script>window.alert('Verification accepted.')</script>";
		echo "<script>window.location='adminVerifyRequest.php'</script>";
	}

	else
	{
		echo "<p>Something went wrong with accepting the verification request.</p>";
	}