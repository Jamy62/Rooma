<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

	$checkVerification = "SELECT * FROM users 
                          WHERE UserID = '$uID'
                          AND GuestVerificationStatus = 'Verified'";
    $result = mysqli_query($dbConnect, $checkVerification);
    $count = mysqli_num_rows($result);

    if ($count == 0)
    {
        echo "<script>window.alert('You need to verify your account to continue the booking process.')</script>";
        echo "<script>window.location='guestVerify.php'</script>";
    }

    if (isset($_POST['btnBook'])) 
    {
        $ppID = $_POST['txtppID'];
        $bDate = date('Y-m-d');
        $bGuestQty = $_POST['txtbGuestQty'];
        $bMessage = $_POST['txtbMessage'];
        $bCheckInDate = $_POST['txtbCheckInDate'];
        $bCheckOutDate = $_POST['txtbCheckOutDate'];
        $bTotalNights = $_POST['txtbTotalNights'];
        $bTotalPrice = $_POST['txtbTotalPrice'];
        $bStatus = 'Pending';
        $gServiceFee = $_POST['txtgServiceFee'];
        $hServiceFee = $_POST['txthServiceFee'];
        $paymentID = $_POST['cboPayment'];

        $query = "INSERT INTO bookings (BookingDate, BookingGuestQty, BookingMessage, BookingCheckInDate, BookingCheckOutDate, BookingTotalNights, BookingTotalPrice, GuestServiceFee, HostServiceFee, BookingStatus, PaymentID, PropertyID, UserID) 
                  VALUES ('$bDate', $bGuestQty, '$bMessage', '$bCheckInDate', '$bCheckOutDate', $bTotalNights, $bTotalPrice, $gServiceFee, '$hServiceFee', '$bStatus', $paymentID, $ppID, $uID)";
    
        $result = mysqli_query($dbConnect, $query);

        if ($result) {
            echo "<script>window.alert('Booking request has been sent.')</script>";
            echo "<script>window.location='guestBookingList.php'</script>";
        } else {
            echo "<script>window.alert('Booking failed. Please try again.')</script>";
            echo "<script>window.location='guestMain.php'</script>";
        }
    }

    $ppID = $_POST['txtppID'];
    $ppName = $_POST['txtppName'];
    $ppPrice = $_POST['txtppPrice'];
    $bCheckInDate = $_POST['txtbCheckInDate'];
    $bCheckOutDate = $_POST['txtbCheckOutDate'];
    $bGuestQty = $_POST['txtbGuestQty'];

    $checkInDate = new DateTime($bCheckInDate);
    $checkOutDate = new DateTime($bCheckOutDate);
    $checkInterval = $checkInDate->diff($checkOutDate);
    $bTotalNights = $checkInterval->days;

    $bTotalPrice = $ppPrice * $bTotalNights;
    $gServiceFee = $bTotalPrice * 0.05;
    $hServiceFee = $bTotalPrice * 0.03;

    $paymentInfoQuery = "SELECT * FROM paymentinfo WHERE UserID = $uID
                         AND UserAccountType = 'Guest'";
    $paymentInfoResult = mysqli_query($dbConnect, $paymentInfoQuery);
    $paymentInfoCount = mysqli_num_rows($paymentInfoResult);

    if ($paymentInfoCount == 0) 
    {
        echo "<script>window.alert('Please add a payment method first.')</script>";
        echo "<script>window.location='guestPaymentInfo.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Guest Booking</title>
</head>
<body>
    <div class="navContainer">
        <header class="guestHead">
            <div class="logo">
                <a href="guestMain.php"><img src="websiteImg/logo2.png" alt="Rooma Logo"></a>
            </div>

            <nav>
                <a href="guestMain.php" class="active">Home</a>
                <a href="guestBookingList.php">Bookings</a>
                <a href="userMessageInbox.php">Message Inbox</a>
                <a href="guestVerify.php">Verification</a>
                <a href="guestPaymentInfo.php">Payment Details</a>
                <div class="dropdown">
                    <a href="#" class="dropBtn">About us ▼</a>
                    <div class="dropdownContent">
                        <a href="#">About Us</a>
                        <a href="#">Privacy policy</a>
                    </div>
                </div>
            </nav>

            <div class="profile">
                <a href="hostMain.php" class="switchMode">Switch to Host</a>
                <a href="" class="profileBtn"><img src="<?php echo $uPp ?>" alt=""></a>
            </div>
        </header>
    </div>

    <div class="bookingContainer">
        <h1 class="bookingTitle">Confirm Your Booking</h1>
        <form action="guestBooking.php" method="POST" class="bookingForm">
            <input type="hidden" name="txtppID" value="<?php echo $ppID; ?>">
            <input type="hidden" name="txtbCheckInDate" value="<?php echo $bCheckInDate; ?>">
            <input type="hidden" name="txtbCheckOutDate" value="<?php echo $bCheckOutDate; ?>">
            <input type="hidden" name="txtbGuestQty" value="<?php echo $bGuestQty; ?>">
            <input type="hidden" name="txtbTotalNights" value="<?php echo $bTotalNights; ?>">
            <input type="hidden" name="txtbTotalPrice" value="<?php echo $bTotalPrice; ?>">
            <input type="hidden" name="txtgServiceFee" value="<?php echo $gServiceFee; ?>">
            <input type="hidden" name="txthServiceFee" value="<?php echo $hServiceFee; ?>">

            <div class="bookingInfoBox">
                <h2 class="bookingPropertyName"><?php echo $ppName ?></h2>
                <div class="bookingDetails" style="color: lightgray;">
                    <p><span>Check-in:</span> <?php echo $bCheckInDate; ?></p>
                    <p><span>Check-out:</span> <?php echo $bCheckOutDate; ?></p>
                    <p><span>Guests:</span> <?php echo $bGuestQty; ?></p>
                    <p><span>Total Nights:</span> <?php echo $bTotalNights; ?></p>
                    <p><span>Price per night:</span> $<?php echo $ppPrice ?></p>
                    <p><span>Total Price:</span> $<?php echo $bTotalPrice; ?></p>
                    <p><span>Service Fee:</span> $<?php echo $gServiceFee; ?></p><br>
                </div>
            </div>

            <div class="bookingFormGroup">
                <label for="paymentMethod" class="bookingLabel">Select Payment Method:</label>
                <select name="cboPayment" id="paymentMethod" class="bookingSelect" required>
                    <?php
                    $paymentMethodQuery = "SELECT * FROM paymentinfo WHERE UserID = $uID AND UserAccountType = 'Guest'";
                    $paymentMethodResult = mysqli_query($dbConnect, $paymentMethodQuery);

                    echo "<option value=''>Select a payment method</option>";
                    while ($paymentMethodArray = mysqli_fetch_array($paymentMethodResult)) 
                    {
                        $pID = $paymentMethodArray['PaymentID'];
                        $cardHolderName = $paymentMethodArray['CardHolderName'];
                        $cardNumber = $paymentMethodArray['CardNumber'];
                        $last4Digits = substr($cardNumber, -4);

                        echo "<option value='$pID'>$cardHolderName (**** **** **** $last4Digits)</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="bookingFormGroup">
                <label for="bookingMessage" class="bookingLabel">Message to Host (optional):</label>
                <textarea name="txtbMessage" id="bookingMessage" class="bookingTextarea" maxlength="255"></textarea>
            </div>

            <input type="submit" name="btnBook" value="Book" class="bookingSubmitBtn">
        </form>
    </div><br><br><br><br>

    <footer>
        <div class="footerContainer">
            <div class="row">
                <div class="footerColumn">
                    <h4>Organization</h4>
                    <ul>
                        <li><a href="#">about us</a></li>
                        <li><a href="#">our services</a></li>
                        <li><a href="termsAndConditions.php">privacy policy</a></li>
                        <li><a href="#">partners</a></li>
                    </ul>
                </div>
                <div class="footerColumn">
                    <h4>get help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">reviews</a></li>
                        <li><a href="#">payment options</a></li>
                    </ul>
                </div>
                <div class="footerColumn">
                    <h4>follow us</h4>
                    <div class="mediaLinks">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-discord"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <p class="copyRight">© 2024 Rooma Ltd. | All Rights Reserved</p>
    </footer>
</body>
</html>