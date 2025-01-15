<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    if (!isset($_GET['ppID'])) {
        echo "<script>window.alert('Invalid property selection.')</script>";
        echo "<script>window.location='guestMain.php'</script>";
        exit();
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];
    $ppID = $_GET['ppID'];

    $listingQuery = "SELECT pp.*, u.UserName, u.UserPp, ph.HostType, c.CityName, c.CountryID, co.CountryName, cnp.CancelPolicyName, cnp.CancelPolicyDescription
                     FROM properties pp, propertyhosts ph, users u, cities c, countries co, cancellationpolicies cnp
                     WHERE pp.PropertyID = $ppID 
                     AND pp.PropertyID = ph.PropertyID
                     AND u.UserID = ph.UserID
                     AND pp.CityID = c.CityID
                     AND c.CountryID = co.CountryID
                     AND pp.CancelPolicyID = cnp.CancelPolicyID
                     AND ph.HostType = 'Host'
                     AND pp.PropertyStatus = 'Listed'";

    $listingResult = mysqli_query($dbConnect, $listingQuery);
    $count = mysqli_num_rows($listingResult);

    if ($count == 0) 
    {
        echo "<script>window.alert('Property not found.')</script>";
        echo "<script>window.location='guestMain.php'</script>";
    }

    $listingArray = mysqli_fetch_array($listingResult);

    $ppName = $listingArray["PropertyName"];
    $ppAddress = $listingArray["PropertyAddress"];
    $ppCoordinates = $listingArray["PropertyCoordinates"];
    $ppImg1 = $listingArray["PropertyImage1"];
    $ppImg2 = $listingArray["PropertyImage2"];
    $ppImg3 = $listingArray["PropertyImage3"];
    $ppDes = $listingArray["PropertyDescription"];
    $ppRooms = $listingArray["PropertyRooms"];
    $ppMaxGuests = $listingArray["PropertyMaxGuest"];
    $ppListingDate = $listingArray["PropertyListingDate"];
    $ppStatus = $listingArray["PropertyStatus"];
    $ppPrice = $listingArray["PropertyPrice"];
    $cName = $listingArray["CityName"];
    $coName = $listingArray["CountryName"];
    $cnpName = $listingArray["CancelPolicyName"];
    $cnpDes = $listingArray["CancelPolicyDescription"];
    $uPp = $listingArray["UserPp"];
    $uName = $listingArray["UserName"];

    $bookingsQuery = "SELECT BookingCheckInDate, BookingCheckOutDate 
                      FROM bookings 
                      WHERE PropertyID = $ppID 
                      AND BookingStatus != 'Cancelled'";
    $bookingsResult = mysqli_query($dbConnect, $bookingsQuery);

    $bookedDates = array();
    while ($booking = mysqli_fetch_array($bookingsResult, MYSQLI_NUM)) 
    {
        $start = new DateTime($booking[0]);  
        $end = new DateTime($booking[1]);  
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);

        foreach ($dateRange as $date) 
        {
            $bookedDates[] = $date->format('Y-m-d');
        }
    }

    $rulesSelect = "SELECT r.*
                    FROM rules r, propertyrules pr
                    WHERE r.RuleID = pr.RuleID
                    AND pr.PropertyID = $ppID";
    $rulesResult = mysqli_query($dbConnect, $rulesSelect);

    $reviewSelect = "SELECT * FROM bookings b, Users u
                     WHERE PropertyID = $ppID
                     AND b.UserID = u.UserID
                     AND BookingReview IS NOT NULL
                     LIMIT 2";
    $reviewResult = mysqli_query($dbConnect, $reviewSelect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Listing Details</title>
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

    <div class="propertyDetails">
        <div class="propertyHeader">
            <h1><?php echo $ppName ?></h1>
            <p><?php echo $ppAddress ?></p>
            <p>Location: <?php echo $cName ?>, <?php echo $coName ?></p>
        </div>

        <div class="propertyImages">
            <img src="<?php echo $ppImg1 ?>">
            <img src="<?php echo $ppImg2 ?>">
            <img src="<?php echo $ppImg3 ?>">
        </div>

        <div class="propertyInfo">
            <div class="propertyDescription">
                <h2>About this place</h2>
                <p><?php echo $ppDes ?></p>

                <h3>Property Details</h3>
                <p><strong>Rooms:</strong> <?php echo $ppRooms ?></p>
                <p><strong>Max Guests:</strong> <?php echo $ppMaxGuests ?></p>
                <p><strong>Listed on:</strong> <?php echo $ppListingDate ?></p>
                <p><strong>Price per night:</strong> $<?php echo $ppPrice ?></p>
                <p><strong>Cancellation Policy:</strong> <?php echo $cnpName ?></p>
                <p><?php echo $cnpDes ?></p><br>
                
                <h3>House Rules</h3>
                <div class="rulesList">
                    <?php 
                        while($rulesArray = mysqli_fetch_array($rulesResult))
                        {
                            $ppRuleIcon = $rulesArray['RuleIcon'];
                            $ppRuleName = $rulesArray['RuleName'];
                            echo "<div class='ruleItem'>$ppRuleIcon $ppRuleName</div>";
                        }
                    ?>
                </div><br>

                <h3>Location</h3>
                <p><strong>Country:</strong> <?php echo $coName ?></p>
                <p><strong>City:</strong> <?php echo $cName ?></p>
                
                <p><strong>Map</strong></p>
                <?php
                    $mapCoordinates = explode(',', $ppCoordinates);
                    $lat = trim($mapCoordinates[0]);
                    $long = trim($mapCoordinates[1]);
                    $mapUrl = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000!2d{$long}!3d{$lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM38wMCc0My4yIk4gOTbCsDA2JzI1LjQiRQ!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus";
                ?>
                <iframe src="<?php echo $mapUrl; ?>" width="161%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                <h3>Host Information</h3>
                <div class="hostInfo">
                    <img src="<?php echo $uPp ?>">
                    <div class="hostDetails">
                        <h4>Owner <?php echo $uName ?>
                        <div class="hostVerified">
                            <span>✓</span> Verified
                        </div></h4>
                    </div>
                </div>
            </div>

            <div class="bookingPanel">
                <h2>$<?php echo $ppPrice ?> / night</h2>
                    <form action="guestBooking.php" method="POST">
                        <input type="hidden" name="txtppID" value="<?php echo $ppID; ?>">
                        <input type="hidden" name="txtppName" value="<?php echo $ppName; ?>">
                        <input type="hidden" name="txtppPrice" value="<?php echo $ppPrice; ?>">
                        
                        <label>Check-in:</label>
                        <input type="date" id="checkInDate" name="txtbCheckInDate" required>
                        
                        <label>Check-out:</label>
                        <input type="date" id="checkOutDate" name="txtbCheckOutDate" required>
                        
                        <label>Guests:</label>
                        <select id="guests" name="txtbGuestQty" required>
                            <?php
                            for ($i = 1; $i <= 10; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                    <button type="submit">Reserve</button>
                </form>
            </div>
        </div>
    </div>

    <div class="reviewContainer">
        <div class="reviewBoxes">
            <?php
            while ($reviewArray = mysqli_fetch_array($reviewResult)) 
            {
                $reviewName = $reviewArray["UserName"];
                $reviewStar = $reviewArray["BookingReviewStar"];
                $review = $reviewArray["BookingReview"];
                $reviewDate = $reviewArray["BookingCheckOutDate"];
            ?>
                <div class="reviewBox">
                    <span class="ratingShow">
                    <?php echo $reviewStar ?> <span style="color: orange; font-size: 130%">★</span>
                    </span>
                    <span class="reviewDate">
                        <?php echo $reviewDate ?>
                    </span><br><br>

                    <b><?php echo $reviewName ?></b><br>
                    <p><?php echo $review ?></p><br>
                    <span class="reply">
                        <i class="fa-solid fa-thumbs-up" style="color: #878787;"></i>
                        <i class="fa-solid fa-thumbs-down" style="color: #878787;"></i>
                        <i class="fa-solid fa-reply" style="color: #878787;"></i>
                    </span>
                </div>
            <?php
            }
            ?>
        </div>
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

    <script>
        var bookedDates = <?php echo json_encode($bookedDates); ?>;
        var todayDate = new Date().toISOString().split('T')[0];

        var checkInDate = document.getElementById('checkInDate');
        var checkOutDate = document.getElementById('checkOutDate');

        /*Set minimum date*/
        checkInDate.min = todayDate;
        checkOutDate.min = todayDate;

        /*Not avaliable booked dates*/
        function disableBookedDates(dateInput) {
            dateInput.addEventListener('input', function() {
                var selectedDate = new Date(this.value);
                if (bookedDates.includes(selectedDate.toISOString().split('T')[0])) {
                    this.value = '';
                    alert('This date is not available. Please choose another date.');
                }
            });
        }
        disableBookedDates(checkInDate);
        disableBookedDates(checkOutDate);

        /*Set checkout date after checkin date*/
        checkInDate.addEventListener('change', function() {
            var minCheckOutDate = new Date(this.value);
            minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);
            checkOutDate.min = minCheckOutDate.toISOString().split('T')[0];
            checkOutDate.value = '';
        });

        /*Date range*/
        function isDateRangeAvailable(start, end) {
            let current = new Date(start);
            while (current <= end) {
                if (bookedDates.includes(current.toISOString().split('T')[0])) {
                    return false;
                }
                current.setDate(current.getDate() + 1);
            }
            return true;
        }

        /*unavaliable date range*/
        checkOutDate.addEventListener('change', function() {
            var start = new Date(checkInDate.value);
            var end = new Date(this.value);
            
            if (!isDateRangeAvailable(start, end)) {
                this.value = '';
                alert('Some dates in this range are not available. Please choose different dates.');
            }
        });
    </script>
</body>
</html>