<?php 
	include('dbConnect.php');
    session_start();

	$bID = $_GET['bID'];
    $pay = $_GET['pay'];
    $uID = $_GET['uID'];

	$accept = "UPDATE bookings SET
               BookingStatus = 'Completed'
               WHERE BookingID = '$bID'";

    $takeRoomaBalance = "UPDATE admins SET
                         RoomaBalance = RoomaBalance - $pay";
               
    $addUserBalance = "UPDATE users SET
                       UserBalance = UserBalance + $pay
                       WHERE UserID = $uID";

	$query = mysqli_query($dbConnect, $accept);
    $query2 = mysqli_query($dbConnect, $takeRoomaBalance);
    $query3 = mysqli_query($dbConnect, $addUserBalance);

	if ($query and $query2 and $query3)
	{
		echo "<script>window.alert('Checked out.')</script>";
		echo "<script>window.location='hostMain.php'</script>";
	}

	else
	{
		echo "<p>Something went wrong with checking out the booking.</p>";
	}