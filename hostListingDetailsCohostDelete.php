<?php 
	include('dbConnect.php');
    session_start();

	$uID = $_GET['uIDco'];

    $delete = "DELETE FROM propertyhosts WHERE UserID = $uID";
    $deleteQuery = mysqli_query($dbConnect, $delete);

    if ($deleteQuery) 
    {
        echo "<script>window.alert('Co-host removed.')</script>";
        echo "<script>window.location='hostListing.php'</script>";
    }

    else
    {
        echo "<p>Something went wrong with removing the Co-host.</p>";
    }