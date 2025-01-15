<?php 
	include('dbConnect.php');
    session_start();

	$ppID = $_GET['ppID'];
    $aID = $_SESSION['aID'];

	$accept = "UPDATE properties SET
               PropertyStatus = 'Listed',
               AdminID = '$aID'
               WHERE PropertyID = '$ppID'";

	$query = mysqli_query($dbConnect, $accept);

	if ($query)
	{
		echo "<script>window.alert('Property has been listed.')</script>";
		echo "<script>window.location='adminListingVerify.php'</script>";
	}

	else
	{
		echo "<p>Something went wrong with accepting the listing request.</p>";
	}