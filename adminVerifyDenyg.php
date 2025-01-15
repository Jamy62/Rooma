<?php 
	include('dbConnect.php');

	$vID = $_GET['vID'];

	$delete = "DELETE FROM verifications WHERE verificationID = '$vID'";

	$delete2 = "UPDATE users SET
                GuestVerificationStatus = NULL
                WHERE UserID = '$uID'";

	$query = mysqli_query($dbConnect, $delete);
	$query2 = mysqli_query($dbConnect, $delete2);

	if ($query and $query2) 
	{
		echo "<script>window.alert('Verification denied.')</script>";
		echo "<script>window.location='adminVerifyRequest.php'</script>";
	}

	else
	{
		echo "<p>Something went wrong with denying the verification request.</p>";
	}