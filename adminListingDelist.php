<?php 
	include('dbConnect.php');

	$ppID = $_GET['ppID'];

	$delete = "DELETE FROM propertyRules WHERE PropertyID = '$ppID'";
    $delete2 = "DELETE FROM propertyHosts WHERE PropertyID = '$ppID'";
	$delete3 = "DELETE FROM bookings WHERE PropertyID = '$ppID'";
    $delete4 = "DELETE FROM properties WHERE PropertyID = '$ppID'";

	$query = mysqli_query($dbConnect, $delete);
	$query2 = mysqli_query($dbConnect, $delete2);
    $query3 = mysqli_query($dbConnect, $delete3);
	$query4 = mysqli_query($dbConnect, $delete4);

	if ($query and $query2 and $query3 and $query4)
	{
		echo "<script>window.alert('Listing delisted.')</script>";
		echo "<script>window.location='adminListingVerify.php'</script>";
	}

	else
	{
		echo "<p>Something went wrong with delisting the listing.</p>";
	}