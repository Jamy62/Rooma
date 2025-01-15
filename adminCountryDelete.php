<?php 
	include('dbConnect.php');
    session_start();

	$cID = $_GET['cID'];

    $delete = "DELETE FROM countries WHERE CountryID = $cID";
    $deleteQuery = mysqli_query($dbConnect, $delete);

    if ($deleteQuery) 
    {
        echo "<script>window.alert('Country deleted.')</script>";
        echo "<script>window.location='adminCountry.php'</script>";
    }

    else
    {
        echo "<p>Something went wrong with deleting the country.</p>";
    }