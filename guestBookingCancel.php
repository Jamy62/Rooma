<?php 
	include('dbConnect.php');
    session_start();

	$bID = $_GET['bID'];
    $cnpID = $_GET['cnpID'];
    $status = $_GET['status'];
    $pay = $_GET['pay'];

    if ($status == 'Pending') 
    {
        $delete = "DELETE FROM bookings
                   WHERE BookingID = $bID";

	    $deleteQuery = mysqli_query($dbConnect, $delete);

        if ($deleteQuery)
        {
            echo "<script>window.alert('Booking has been cancelled.')</script>";
            echo "<script>window.location='guestBookingList.php'</script>";
        }

        else
        {
            echo "<p>Something went wrong with the booking cancellation. Please try again.</p>";
        }
    }

    else
    {
        if ($cnpID == '1') 
        {
            $cancel = "SELECT * FROM bookings 
                       WHERE CURDATE() < DATE_ADD(BookingCheckInDate, INTERVAL 1 DAY) 
                       AND BookingID = $bID";
            $query = mysqli_query($dbConnect, $cancel);
            $count = mysqli_num_rows($query);

            $delete = "DELETE FROM bookings
                       WHERE BookingID = $bID";
	        $deleteQuery = mysqli_query($dbConnect, $delete);

            if ($count == 1)
            {
                $takeRoomaBalance = "UPDATE admins SET
                                     RoomaBalance = RoomaBalance - $pay";
                $payQuery = mysqli_query($dbConnect, $takeRoomaBalance);

                if ($payQuery and $deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved a full refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }

            else
            {
                $takeRoomaBalanceHalf = "UPDATE admins SET
                                         RoomaBalance = RoomaBalance - ($pay * 0.5)";
                $payQuery = mysqli_query($dbConnect, $takeRoomaBalanceHalf);

                if ($payQuery and $deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved a 50% refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }
        }

        else if ($cnpID == '2')
        {
            $cancel = "SELECT * FROM bookings 
                       WHERE CURDATE() < DATE_ADD(BookingCheckInDate, INTERVAL 5 DAY) 
                       AND BookingID = $bID";
            $query = mysqli_query($dbConnect, $cancel);
            $count = mysqli_num_rows($query);

            $delete = "DELETE FROM bookings
                       WHERE BookingID = $bID";
	        $deleteQuery = mysqli_query($dbConnect, $delete);

            if ($count == 1)
            {
                $takeRoomaBalance = "UPDATE admins SET
                                     RoomaBalance = RoomaBalance - $pay";
                $payQuery = mysqli_query($dbConnect, $takeRoomaBalance);

                if ($payQuery and $deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved a full refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }

            else
            {
                $takeRoomaBalanceHalf = "UPDATE admins SET
                                         RoomaBalance = RoomaBalance - ($pay * 0.5)";
                $payQuery = mysqli_query($dbConnect, $takeRoomaBalanceHalf);

                if ($payQuery and $deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved a 50% refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }
        }

        else if ($cnpID == '3')
        {
            $cancel = "SELECT * FROM bookings 
                       WHERE CURDATE() < DATE_ADD(BookingCheckInDate, INTERVAL 1 DAY) 
                       AND BookingID = $bID";
            $query = mysqli_query($dbConnect, $cancel);
            $count = mysqli_num_rows($query);

            $delete = "DELETE FROM bookings
                       WHERE BookingID = $bID";
	        $deleteQuery = mysqli_query($dbConnect, $delete);

            if ($count == 1)
            {
                $takeRoomaBalance = "UPDATE admins SET
                                     RoomaBalance = RoomaBalance - ($pay * 0.5)";
                $payQuery = mysqli_query($dbConnect, $takeRoomaBalance);

                if ($payQuery and $deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved a 50% refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }

            else
            {
                if ($deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved no refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }
        }

        else if ($cnpID == '4')
        {
            $cancel = "SELECT * FROM bookings 
                       WHERE CURDATE() < DATE_ADD(BookingCheckInDate, INTERVAL 5 DAY) 
                       AND BookingID = $bID";
            $query = mysqli_query($dbConnect, $cancel);
            $count = mysqli_num_rows($query);

            $delete = "DELETE FROM bookings
                       WHERE BookingID = $bID";
	        $deleteQuery = mysqli_query($dbConnect, $delete);

            if ($count == 1)
            {
                $takeRoomaBalanceHalf = "UPDATE admins SET
                                         RoomaBalance = RoomaBalance - ($pay * 0.5)";
                $payQuery = mysqli_query($dbConnect, $takeRoomaBalanceHalf);

                if ($payQuery and $deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved a 50% refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }

            else
            {
                if ($deleteQuery)
                {
                    echo "<script>window.alert('You have cancelled the booking and recieved no refund.')</script>";
                    echo "<script>window.location='guestBookingList.php'</script>";
                }

                else
                {
                    echo "<p>Something went wrong with cancelling the booking.</p>";
                }
            }
        }

        else if ($cnpID == '5')
        {
            $delete = "DELETE FROM bookings
                       WHERE BookingID = $bID";
	        $deleteQuery = mysqli_query($dbConnect, $delete);

            if ($deleteQuery)
            {
                echo "<script>window.alert('You have cancelled the booking and recieved no refund.')</script>";
                echo "<script>window.location='guestBookingList.php'</script>";
            }

            else
            {
                echo "<p>Something went wrong with cancelling the booking.</p>";
            }
        }
    }
