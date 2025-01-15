<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    $currentBookings = "SELECT * FROM bookings
                        WHERE BookingStatus NOT IN ('Completed', 'Cancelled')";
    $currentBookingsResult = mysqli_query($dbConnect, $currentBookings);
    $currentBookingsCount = mysqli_num_rows($currentBookingsResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Host Dashboard</title>
</head>
<body>
    <div class="navContainer">
        <header class="hostHead">
            <div class="logo">
                <a href="hostMain.php"><img src="websiteImg/logo2.png" alt="Logo"></a>
            </div>

            <nav>
                <a href="hostMain.php" class="active">Bookings</a>
                <a href="hostListing.php">Listings</a>
                <a href="userMessageInbox.php">Message Inbox</a>
                <a href="hostVerify.php">Verification</a>
                <a href="hostPaymentInfo.php">Payment Details</a>
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

    <div class="dashboardContainer">
        <main>
            <a href="hostListingAdd.php" class="listPropertyBtn">List your property</a><br>

            <div class="bookingSection">
                <h2>Your Booking Requests</h2>
                <a href="#" class="currentBooking">Current bookings (<?php echo $currentBookingsCount ?>)</a>
                <div class="bookingTabs">
                    <button class="tabBtn active" data-tab-target="pendingRequest">Pending request</button>
                    <button class="tabBtn" data-tab-target="currentlyHosting">Currently hosting</button>
                    <button class="tabBtn" data-tab-target="completedHosting">Completed hosting</button>
                    <button class="tabBtn" data-tab-target="recentReviews">Recent reviews</button>
                </div>

                <div id="pendingRequest" class="bookingContent active">
                    <?php
                        $select = "SELECT b.*, p.PropertyName, ph.HostType, u.UserName as GuestName
                                   FROM bookings b, properties p, users u, propertyhosts ph
                                   WHERE b.PropertyID = p.PropertyID
                                   AND b.UserID = u.UserID
                                   AND p.PropertyID = ph.PropertyID
                                   AND ph.UserID = '$uID'
                                   AND b.BookingStatus = 'pending'";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Role</th>
                                        <th>Property Name</th>
                                        <th>Guest Name</th>
                                        <th>Guest Qty</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Price</th>
                                        <th>After Service Fee (5%)</th>
                                        <th>Message</th>
                                        <th>Action</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $hostType = $array['HostType'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['GuestName'];
                                $bGuestQty = $array['BookingGuestQty'];
                                $bCheckInDate = $array['BookingCheckInDate'];
                                $bCheckOutDate = $array['BookingCheckOutDate'];
                                $bTotalPrice = $array['BookingTotalPrice'];
                                $message = $array['BookingMessage'];
                                $hServiceFee = $array['HostServiceFee'];
                                $afterServiceFee = $bTotalPrice - $hServiceFee;
                                $gServiceFee = $array['GuestServiceFee'];
                                $guestPayment = $bTotalPrice + $gServiceFee;


                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$hostType</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td>$bGuestQty</td>
                                        <td>$bCheckInDate</td>
                                        <td>$bCheckOutDate</td>
                                        <td>$$bTotalPrice</td>
                                        <td>$$afterServiceFee</td>
                                        <td>$message</td>
                                        <td>
                                            <a href='hostBookingAccept.php?bID=$bID &pay=$guestPayment'>Accept</a>
                                            <a href='hostBookingDeny.php?bID=$bID'>Deny</a>
                                        </td>
                                    </tr>";
                            }
                            echo "</table>";
                        } 
                        
                        else {
                            echo "<div class='noBooking'>
                                    <div class='icon'>🏠</div>
                                    <p>You don't have any booking requests at the moment.</p>
                                </div>";
                        }
                    ?>
                </div>

                <div id="currentlyHosting" class="bookingContent">
                    <?php
                        $select = "SELECT b.*, p.PropertyName, ph.HostType, u.UserName as GuestName
                                   FROM bookings b, properties p, users u, propertyhosts ph
                                   WHERE b.PropertyID = p.PropertyID
                                   AND b.UserID = u.UserID
                                   AND p.PropertyID = ph.PropertyID
                                   AND ph.UserID = '$uID'
                                   AND b.BookingStatus = 'Booked'";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Role</th>
                                        <th>Property Name</th>
                                        <th>Guest Name</th>
                                        <th>Guest Qty</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Price</th>
                                        <th>After Service Fee (5%)</th>
                                        <th>Action</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $hostType = $array['HostType'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['GuestName'];
                                $bGuestQty = $array['BookingGuestQty'];
                                $bCheckInDate = $array['BookingCheckInDate'];
                                $bCheckOutDate = $array['BookingCheckOutDate'];
                                $bTotalPrice = $array['BookingTotalPrice'];
                                $hServiceFee = $array['HostServiceFee'];
                                $afterServiceFee = $bTotalPrice - $hServiceFee;
                                $gServiceFee = $array['GuestServiceFee'];
                                $guestPayment = $bTotalPrice + $gServiceFee;

                                $ppID = $array['PropertyID'];
                                $ownerHostSelect = "SELECT UserID FROM propertyhosts
                                                    WHERE PropertyID = $ppID
                                                    AND HostType != 'Co-Host'";
                                $ownerHostResult = mysqli_query($dbConnect, $ownerHostSelect);
                                $ownerHostArray = mysqli_fetch_array($ownerHostResult);
                                $ownerHost = $ownerHostArray["UserID"];

                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$hostType</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td>$bGuestQty</td>
                                        <td>$bCheckInDate</td>
                                        <td>$bCheckOutDate</td>
                                        <td>$$bTotalPrice</td>
                                        <td>$$afterServiceFee</td>
                                        <td>
                                            <a href='hostBookingComplete.php?bID=$bID &pay=$afterServiceFee &uID=$ownerHost'>Complete</a>
                                            <a href='hostBookingCancel.php?bID=$bID &pay=$guestPayment'>Cancel</a>
                                        </td>
                                    </tr>";
                            }
                            echo "</table>";
                        } 
                        
                        else {
                            echo "<div class='noBooking'>
                                    <div class='icon'>👥</div>
                                    <p>You are not currently hosting any guests.</p>
                                </div>";
                        }
                    ?>
                </div>

                <div id="completedHosting" class="bookingContent">
                    <?php
                        $select = "SELECT b.*, p.PropertyName, ph.HostType, u.UserName as GuestName
                                   FROM Bookings b, properties p, users u, propertyhosts ph
                                   WHERE b.PropertyID = p.PropertyID
                                   AND b.UserID = u.UserID
                                   AND p.PropertyID = ph.PropertyID
                                   AND ph.UserID = '$uID'
                                   AND b.BookingStatus = 'completed'";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Role</th>
                                        <th>Property Name</th>
                                        <th>Guest Name</th>
                                        <th>Guest Qty</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Price</th>
                                        <th>After Service Fee (5%)</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $hostType = $array['HostType'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['GuestName'];
                                $bGuestQty = $array['BookingGuestQty'];
                                $bCheckInDate = $array['BookingCheckInDate'];
                                $bCheckOutDate = $array['BookingCheckOutDate'];
                                $bTotalPrice = $array['BookingTotalPrice'];
                                $hServiceFee = $array['HostServiceFee'];
                                $afterServiceFee = $bTotalPrice - $hServiceFee;

                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$hostType</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td>$bGuestQty</td>
                                        <td>$bCheckInDate</td>
                                        <td>$bCheckOutDate</td>
                                        <td>$$bTotalPrice</td>
                                        <td>$$afterServiceFee</td>
                                    </tr>";
                            }
                            echo "</table>";
                        }
                        
                        else {
                            echo "<div class='noBooking'>
                                    <div class='icon'>✅</div>
                                    <p>You haven't completed any hosting yet.</p>
                                </div>";
                        }
                    ?>
                </div>

                <div id="recentReviews" class="bookingContent">
                    <?php
                        $select = "SELECT b.*, pp.PropertyName, ph.HostType, u.UserName as GuestName
                                   FROM bookings b, properties pp, users u, propertyhosts ph
                                   WHERE b.PropertyID = pp.PropertyID
                                   AND b.UserID = u.UserID
                                   AND pp.PropertyID = ph.PropertyID
                                   AND ph.UserID = '$uID'
                                   AND b.BookingReview IS NOT NULL
                                   ORDER BY b.BookingCheckOutDate DESC
                                   LIMIT 10";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Role</th>
                                        <th>Property Name</th>
                                        <th>Guest Name</th>
                                        <th>Review</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $hostType = $array['HostType'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['GuestName'];
                                $bReviewStar = $array['BookingReviewStar'];
                                $bReview = $array['BookingReview'];

                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$hostType</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td><span class='bookingRating'>★</span> $bReviewStar</td>
                                        <td>$bReview</td>
                                    </tr>";
                            }
                            echo "</table>";
                        } 
                        
                        else {
                            echo "<div class='noBooking'>
                                    <div class='icon'>⭐</div>
                                    <p>You haven't received any reviews yet.</p>
                                </div>";
                        }
                    ?>
                </div>
            </div>
        </main>
    </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

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
        window.onload = function() {
            var tabButtons = document.querySelectorAll('.tabBtn');
            var tabContents = document.querySelectorAll('.bookingContent');
            var dropdownBtn = document.querySelector('.dropBtn');
            var dropdownContent = document.querySelector('.dropdownContent');

            function handleTabClick(event) {
          
                tabButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                });
                tabContents.forEach(function(content) {
                    content.classList.remove('active');
                });

                event.target.classList.add('active');

                var tabTarget = event.target.getAttribute('data-tab-target');
                document.getElementById(tabTarget).classList.add('active');
            }

            // tabs
            function toggleDropdown(event) {
                event.stopPropagation();
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            }

            function closeDropdown() {
                dropdownContent.style.display = 'none';
            }

            tabButtons.forEach(function(button) {
                button.addEventListener('click', handleTabClick);
            });

            dropdownBtn.addEventListener('click', toggleDropdown);

            window.addEventListener('click', closeDropdown);
        };
    </script>
</body>
</html>
