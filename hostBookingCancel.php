<?php 
	include('dbConnect.php');
    session_start();

	$bID = $_GET['bID'];
    $pay = $_GET['pay'];

	$accept = "UPDATE bookings SET
               BookingStatus = 'Cancelled'
               WHERE BookingID = '$bID'";

    $takeRoomaBalance = "UPDATE admins SET
                         RoomaBalance = RoomaBalance - $pay";

	$query = mysqli_query($dbConnect, $accept);
    $query2 = mysqli_query($dbConnect, $takeRoomaBalance);

	if ($query and $query2)
	{
		echo "<script>window.alert('Booking cancelled.')</script>";
		echo "<script>window.location='hostMain.php'</script>";
	}

	else
	{
		echo "<p>Something went wrong with cancelling the booking.</p>";
	}