<?php 
	include('dbConnect.php');
    session_start();

	$bID = $_GET['bID'];
    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

	// Get the guest's UserID
	$getGuestID = "SELECT b.UserID AS GuestID
				   FROM bookings b
				   WHERE b.BookingID = '$bID'";
	$guestResult = mysqli_query($dbConnect, $getGuestID);
	$guestRow = mysqli_fetch_assoc($guestResult);
	$guestID = $guestRow['GuestID'];

	$delete = "DELETE FROM bookings
               WHERE BookingID = $bID";

	$query = mysqli_query($dbConnect, $delete);

	if ($query)
	{
		// Rejection message
		$welcomeMessage = "Sorry! Your booking was rejected.";
	
		$addMessage = "INSERT INTO messages (MessageSenderID, MessageReceiverID, MessageText)
		 VALUES ('$uID', '$guestID', '$welcomeMessage')";
		
		// Execute the query
		$messageResult = mysqli_query($dbConnect, $addMessage);
	
		if ($messageResult) {
			echo "<script>window.alert('Booking has been denied and a notice has been sent.')</script>";
			echo "<script>window.location='hostMain.php'</script>";
		} else {
			echo "<script>window.alert('Booking denied but failed to send notice message.')</script>";
			echo "<script>window.location='hostMain.php'</script>";
		}
	}