<?php 
	include('dbConnect.php');
    session_start();

	$ciID = $_GET['ciID'];

    $delete = "DELETE FROM cities WHERE CityID = $ciID";
    $deleteQuery = mysqli_query($dbConnect, $delete);

    if ($deleteQuery) 
    {
        echo "<script>window.alert('City deleted.')</script>";
        echo "<script>window.location='adminCity.php'</script>";
    }

    else
    {
        echo "<p>Something went wrong with deleting the city.</p>";
    }