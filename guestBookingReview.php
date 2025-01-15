<?php 
	session_start();
	include('dbConnect.php');

	if (!isset($_SESSION['uID'])) 
	{
		echo "<script>window.alert('Login Again.')</script>";
		echo "<script>window.location='userLogin.php'</script>";
	}

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];
    $bID = $_GET['bID'];
    $lName = $_GET['lName'];
    $hName = $_GET['hName'];

	if (isset($_POST['btnReview'])) 
	{
		$bReview = $_POST['txtbReview'];
        $bID = $_POST['txtbID'];
        $stars = $_POST['stars'];

		echo $review = "UPDATE bookings SET
                   BookingReview = '$bReview',
                   bookingReviewStar = '$stars'
                   WHERE BookingID = $bID";
		$query = mysqli_query($dbConnect, $review);

		if ($query) 
		{
			echo "<script>window.alert('Review has been sent.')</script>";
			echo "<script>window.location='guestBookingList.php'</script>";
		}
	}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" type="text/css" href="roomaStyle.css">
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 	<title></title>
 </head>
 <body id="contactBack">
 <div class="navContainer">
        <header class="guestHead">
            <div class="logo">
                <a href="guestMain.php"><img src="websiteImg/logo2.png" alt="Rooma Logo"></a>
            </div>

            <nav>
                <a href="guestMain.php">Home</a>
                <a href="guestBookingList.php" class="active">Bookings</a>
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
    </div><br><br><br>

    <div id="registerContainer">
        <h2>Review Your Stay</h2>
        <form id="Register" action="guestBookingReview.php" method="POST">
            <label>Listing Name</label>
            <input type="text" class="registerInfo" value="<?php echo $lName ?>" readonly>

            <label>Host</label>
            <input type="text" class="registerInfo" value="<?php echo $hName ?>" readonly>

            <label for="txtbReview">Your Review</label>
            <textarea id="txtbReview" name="txtbReview" class="registerInfo" required></textarea>

            <label>Rating</label>
            <div class="star">
                <input type="radio" name="stars" value="5" id="star5" required>
                <label for="star5"></label>
                <input type="radio" name="stars" value="4" id="star4">
                <label for="star4"></label>
                <input type="radio" name="stars" value="3" id="star3">
                <label for="star3"></label>
                <input type="radio" name="stars" value="2" id="star2">
                <label for="star2"></label>
                <input type="radio" name="stars" value="1" id="star1">
                <label for="star1"></label>
            </div>

            <input type="hidden" name="txtbID" value="<?php echo $bID ?>">
            <input type="hidden" name="txtlName" value="<?php echo $lName ?>">
            <input type="hidden" name="txthName" value="<?php echo $hName ?>">
            <input type="submit" name="btnReview" value="Submit Review" class="registerSubmit">
        </form>
    </div><br><br><br><br><br><br><br><br>

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