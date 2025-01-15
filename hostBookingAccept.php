<?php 
    include('dbConnect.php');
    session_start();

    $bID = $_GET['bID'];
    $pay = $_GET['pay'];
    $uID = $_SESSION['uID'];
        $uPp = $_SESSION['uPp'];


    $getGuestID = "SELECT b.UserID AS GuestID
                FROM bookings b
                WHERE b.BookingID = '$bID'";
    $guestResult = mysqli_query($dbConnect, $getGuestID);
    $guestRow = mysqli_fetch_assoc($guestResult);
    $guestID = $guestRow['GuestID'];

    $accept = "UPDATE bookings SET
            BookingStatus = 'Booked'
            WHERE BookingID = '$bID'";
            
    $addRoomaBalance = "UPDATE admins SET
                        RoomaBalance = RoomaBalance + $pay";

    $query = mysqli_query($dbConnect, $accept);
    $query2 = mysqli_query($dbConnect, $addRoomaBalance);

    if ($query and $query2)
    {
        $welcomeMessage = "Welcome! Your booking has been accepted.";

        $addMessage = "INSERT INTO messages (MessageSenderID, MessageReceiverID, MessageText)
        VALUES ('$uID', '$guestID', '$welcomeMessage')";
        
        $messageResult = mysqli_query($dbConnect, $addMessage);

        if ($messageResult) {
            echo "<script>window.alert('Booking has been accepted and a welcome message has been sent.')</script>";
            echo "<script>window.location='hostMain.php'</script>";
        } else {
            echo "<script>window.alert('Booking accepted but failed to send welcome message.')</script>";
            echo "<script>window.location='hostMain.php'</script>";
        }
    }