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
    <title>Guest Booking List</title>
</head>
<body>
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
    </div>


    <div class="dashboardContainer">
        <main>
            <div class="bookingSection">
                <h2>Bookings List</h2>
                <a href="#" class="currentBooking">Pending bookings (<?php echo $currentBookingsCount ?>)</a>
                <div class="bookingTabs">
                    <button class="tabBtn active" data-tab-target="pendingRequest">Pending request</button>
                    <button class="tabBtn" data-tab-target="currentlyHosting">Currently booking</button>
                    <button class="tabBtn" data-tab-target="completedHosting">Completed booking</button>
                    <button class="tabBtn" data-tab-target="recentReviews">Reviewed stays</button>
                </div>

                <div id="pendingRequest" class="bookingContent active">
                    <?php
                        $select = "SELECT b.*, pp.PropertyName, cnp.*, ph.*, u.UserName as HostName
                                   FROM bookings b, properties pp, cancellationpolicies cnp, users u, propertyhosts ph
                                   WHERE b.PropertyID = pp.PropertyID
                                   AND pp.PropertyID = ph.PropertyID
                                   AND ph.UserID = u.UserID
                                   AND pp.CancelPolicyID = cnp.CancelPolicyID
                                   AND b.UserID = $uID 
                                   AND b.BookingStatus = 'pending'";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) 
                        {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Property Name</th>
                                        <th>Host Name</th>
                                        <th>Guest Qty</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Price</th>
                                        <th>Service Fee (3%)</th>
                                        <th>Message</th>
                                        <th>Cancellation Policy</th>
                                        <th>Action</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['HostName'];
                                $bGuestQty = $array['BookingGuestQty'];
                                $bCheckInDate = $array['BookingCheckInDate'];
                                $bCheckOutDate = $array['BookingCheckOutDate'];
                                $bTotalPrice = $array['BookingTotalPrice'];
                                $message = $array['BookingMessage'];
                                $hServiceFee = $array['HostServiceFee'];
                                $afterServiceFee = $bTotalPrice - $hServiceFee;
                                $gServiceFee = $array['GuestServiceFee'];
                                $guestPayment = $bTotalPrice + $gServiceFee;
                                $cnpName = $array['CancelPolicyName'];
                                $cnpID = $array['CancelPolicyID'];
                                $status = 'Pending';

                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td>$bGuestQty</td>
                                        <td>$bCheckInDate</td>
                                        <td>$bCheckOutDate</td>
                                        <td>$$bTotalPrice</td>
                                        <td>$$gServiceFee</td>
                                        <td>$message</td>
                                        <td>$cnpName</td>
                                        <td>
                                            <a href='guestBookingCancel.php?bID=$bID &cnpID=$cnpID &status=$status'>Cancel</a>
                                        </td>
                                    </tr>";
                            }
                            echo "</table>";
                        } 
                        
                        else 
                        {
                            echo "<div class='noBooking'>
                                    <div class='icon'>🏠</div>
                                    <p>You don't have any bookings at the moment.</p>
                                </div>";
                        }
                    ?>
                </div>

                <div id="currentlyHosting" class="bookingContent">
                    <?php
                        $select = "SELECT b.*, pp.PropertyName, cnp.*, ph.*, u.UserName as HostName
                                   FROM bookings b, properties pp, cancellationpolicies cnp, users u, propertyhosts ph
                                   WHERE b.PropertyID = pp.PropertyID
                                   AND pp.PropertyID = ph.PropertyID
                                   AND ph.UserID = u.UserID
                                   AND pp.CancelPolicyID = cnp.CancelPolicyID
                                   AND b.UserID = $uID 
                                   AND b.BookingStatus = 'Booked'";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) 
                        {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Property Name</th>
                                        <th>Host Name</th>
                                        <th>Guest Qty</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Price</th>
                                        <th>Service Fee (3%)</th>
                                        <th>Message</th>
                                        <th>Cancellation Policy</th>
                                        <th>Action</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['HostName'];
                                $bGuestQty = $array['BookingGuestQty'];
                                $bCheckInDate = $array['BookingCheckInDate'];
                                $bCheckOutDate = $array['BookingCheckOutDate'];
                                $bTotalPrice = $array['BookingTotalPrice'];
                                $message = $array['BookingMessage'];
                                $hServiceFee = $array['HostServiceFee'];
                                $afterServiceFee = $bTotalPrice - $hServiceFee;
                                $gServiceFee = $array['GuestServiceFee'];
                                $guestPayment = $bTotalPrice + $gServiceFee;
                                $cnpName = $array['CancelPolicyName'];
                                $cnpID = $array['CancelPolicyID'];
                                $status = 'Booked';

                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td>$bGuestQty</td>
                                        <td>$bCheckInDate</td>
                                        <td>$bCheckOutDate</td>
                                        <td>$$bTotalPrice</td>
                                        <td>$$gServiceFee</td>
                                        <td>$message</td>
                                        <td>$cnpName</td>
                                        <td>
                                            <a href='guestBookingCancel.php?bID=$bID &cnpID=$cnpID &status=$status &pay=$guestPayment'>Cancel</a>
                                        </td>
                                    </tr>";
                            }
                            echo "</table>";
                        } 
                        
                        else 
                        {
                            echo "<div class='noBooking'>
                                    <div class='icon'>👥</div>
                                    <p>You have no upcoming bookings.</p>
                                </div>";
                        }
                    ?>
                </div>

                <div id="completedHosting" class="bookingContent">
                    <?php
                        $select = "SELECT b.*, pp.PropertyName, cnp.*, ph.*, u.UserName as HostName
                                   FROM bookings b, properties pp, cancellationpolicies cnp, users u, propertyhosts ph
                                   WHERE b.PropertyID = pp.PropertyID
                                   AND pp.PropertyID = ph.PropertyID
                                   AND ph.UserID = u.UserID
                                   AND pp.CancelPolicyID = cnp.CancelPolicyID
                                   AND b.UserID = $uID 
                                   AND b.BookingStatus = 'Completed'";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) 
                        {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Property Name</th>
                                        <th>Host Name</th>
                                        <th>Guest Qty</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Price</th>
                                        <th>Service Fee (3%)</th>
                                        <th>Message</th>
                                        <th>Cancellation Policy</th>
                                        <th>Action</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['HostName'];
                                $bGuestQty = $array['BookingGuestQty'];
                                $bCheckInDate = $array['BookingCheckInDate'];
                                $bCheckOutDate = $array['BookingCheckOutDate'];
                                $bTotalPrice = $array['BookingTotalPrice'];
                                $message = $array['BookingMessage'];
                                $hServiceFee = $array['HostServiceFee'];
                                $afterServiceFee = $bTotalPrice - $hServiceFee;
                                $gServiceFee = $array['GuestServiceFee'];
                                $guestPayment = $bTotalPrice + $gServiceFee;
                                $cnpName = $array['CancelPolicyName'];

                                $reviewSelect = "SELECT BookingReview FROM bookings
                                                 WHERE BookingID = $bID
                                                 AND BookingReview IS NULL";
                                $reviewResult = mysqli_query($dbConnect, $reviewSelect);
                                $reviewCount = mysqli_num_rows($reviewResult);

                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td>$bGuestQty</td>
                                        <td>$bCheckInDate</td>
                                        <td>$bCheckOutDate</td>
                                        <td>$$bTotalPrice</td>
                                        <td>$$gServiceFee</td>
                                        <td>$message</td>
                                        <td>$cnpName</td>
                                        <td>";
                                            if ($reviewCount > 0) 
                                            {
                                                echo"<a href='guestBookingReview.php?bID=$bID &lName=$ppName &hName=$uName'>Review</a>";
                                            }
                                            else
                                            {
                                                echo"✅";
                                            }
                                echo "</td></tr>";
                            }
                            echo "</table>";
                        } 
                        
                        else 
                        {
                            echo "<div class='noBooking'>
                                    <div class='icon'>✅</div>
                                    <p>You haven't completed any bookings yet.</p>
                                </div>";
                        }
                    ?>
                </div>

                <div id="recentReviews" class="bookingContent">
                    <?php
                        $select = "SELECT b.*, pp.PropertyName, cnp.*, ph.*, u.UserName as HostName
                                   FROM bookings b, properties pp, cancellationpolicies cnp, users u, propertyhosts ph
                                   WHERE b.PropertyID = pp.PropertyID
                                   AND pp.PropertyID = ph.PropertyID
                                   AND ph.UserID = u.UserID
                                   AND pp.CancelPolicyID = cnp.CancelPolicyID
                                   AND b.UserID = $uID 
                                   AND b.BookingReview IS NOT NULL";

                        $result = mysqli_query($dbConnect, $select);
                        $count = mysqli_num_rows($result);

                        if($count > 0) {
                            echo "<table class='bookingTable'>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Property Name</th>
                                        <th>Host Name</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                    </tr>";
                            
                            while($array = mysqli_fetch_array($result)) 
                            {
                                $bID = $array['BookingID'];
                                $ppName = $array['PropertyName'];
                                $uName = $array['HostName'];
                                $bReviewStar = $array['BookingReviewStar'];
                                $bReview = $array['BookingReview'];

                                echo "<tr>
                                        <td>$bID</td>
                                        <td>$ppName</td>
                                        <td>$uName</td>
                                        <td><span class='bookingRating'>★</span> $bReviewStar</td>
                                        <td>$bReview</td>
                                    </tr>";
                            }
                            echo "</table>";
                        } 
                        
                        else 
                        {
                            echo "<div class='noBooking'>
                                    <div class='icon'>⭐</div>
                                    <p>You haven't reviewed any bookings yet.</p>
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
