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

    $hostQuery = "SELECT ph.*, u.* 
                  FROM propertyhosts ph, users u, properties p
                  WHERE u.UserID = ph.UserID
                  AND p.PropertyID = ph.PropertyID
                  AND ph.PropertyID = $ppID
                  AND ph.HostType = 'Co-host'";
    $hostResult = mysqli_query($dbConnect, $hostQuery);
    $hostCount = mysqli_num_rows($hostResult);
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
                <a href="guestMain.php" class="switchMode">Switch to Guest</a>
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
                <iframe src="<?php echo $mapUrl; ?>" width="104%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <br>

                <h3>Host Information</h3>
                <div class="hostInfo">
                    <img src="<?php echo $uPp ?>">
                    <div class="hostDetails">
                        <h4>Owner <?php echo $uName ?>
                        <div class="hostVerified">
                            <span>✓</span> Verified
                        </div></h4>
                    </div>
                </div><br><br><br><br><br>

                <div class="bookingSection">
                    <h2>Your Booking Requests</h2>
                    <a href="#" class="currentBooking"></a>
                    <div class="bookingTabs">
                        <a href="hostListingDetailsCohost.php?ppID=<?php $ppID ?>" class="tabBtn" style="text-decoration: none;">Add Co-host</a>
                    </div>

                    <div id="pendingRequest" class="bookingContent active">
                        <?php
                            if($hostCount > 0) {
                                echo "<table class='bookingTable'>
                                        <tr>
                                            <th>Co-host ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>";
                                
                                while($array = mysqli_fetch_array($hostResult)) 
                                {
                                    $uIDco = $array['UserID'];
                                    $uNameco = $array['UserName'];
                                    $uEmailco = $array['UserEmail'];

                                    echo "<tr>
                                            <td>$uIDco</td>
                                            <td>$uNameco</td>
                                            <td>$uEmailco</td>
                                            <td>
                                                <a href='hostListingDetailsCohostDelete.php?uIDco=$uIDco'>Delete</a>
                                            </td>
                                        </tr>";
                                }
                                echo "</table>";
                            } 
                            
                            else {
                                echo "<div class='noBooking'>
                                        <div class='icon'>👥</div>
                                        <p>You don't have any booking requests at the moment.</p>
                                    </div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
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
        
    </script>
</body>
</html>