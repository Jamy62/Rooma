<?php
    include('dbConnect.php');
    session_start();

    if (isset($_SESSION['uID'])) 
    {
        $uID = $_SESSION['uID'];
        $uPp = $_SESSION['uPp'];
    }

    else
    {
        $uID = 0;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Guest Dashboard</title>
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
                    <a href="#" class="dropBtn">More ▼</a>
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

    <div class="searchContainer">
        <form class="searchForm" action="guestListingSearch.php" method="POST">
            <div class="searchInput">
                <label>Location</label>
                <input type="text" name="location" placeholder="Search a city" required/>
            </div>
            <div class="searchInput">
                <label>Check in</label>
                <input type="date" name="checkInDate" id="checkInDate" required/>
            </div>
            <div class="searchInput">
                <label>Check out</label>
                <input type="date" name="checkOutDate" id="checkOutDate" required/>
            </div>
            <div class="searchInput">
                <label>Guests</label>
                <select name="guests">
                    <option value="1">1 guest</option>
                    <option value="2">2 guests</option>
                    <option value="3">3 guests</option>
                    <option value="4">4+ guests</option>
                </select>
            </div>
            <button type="submit" class="searchButton">Search</button>
        </form>
    </div>

    <h2 class="explore">Explore New destinations</h2>

    <div class="propertyListings">
        <?php
            $query = "SELECT * FROM properties
                      WHERE PropertyStatus = 'Listed'
                      LIMIT 12";
            $result = mysqli_query($dbConnect, $query);

            while ($array = mysqli_fetch_array($result)) 
            {
                $ppID = $array['PropertyID'];
                $ppImg1 = $array['PropertyImage1'];
                $ppName = $array['PropertyName'];
                $ppDes = $array['PropertyDescription'];
                $ppPrice = $array['PropertyPrice'];

                $ownedSelect = "SELECT * FROM propertyHosts
                                WHERE UserID = $uID
                                AND PropertyID = $ppID";
                $ownedQuery = mysqli_query($dbConnect, $ownedSelect);
                $ownedCount = mysqli_num_rows($ownedQuery);

                $reviewSelect = "SELECT * FROM bookings
                                 WHERE PropertyID = $ppID
                                 AND BookingReview IS NOT NULL";
                $reviewQuery = mysqli_query($dbConnect, $reviewSelect);
                $reviewCount = mysqli_num_rows($reviewQuery);

                $ppStarSelect = "SELECT AVG(BookingReviewStar) AS AverageStar
                                FROM bookings
                                WHERE PropertyID = $ppID
                                AND BookingStatus = 'Completed'
                                AND BookingReviewStar IS NOT NULL
                                AND BookingReviewStar > 0;";
                $ppStarResult = mysqli_query($dbConnect, $ppStarSelect);
                $ppStarArray = mysqli_fetch_array($ppStarResult);
                $ppStar = number_format($ppStarArray['AverageStar'], 1);

                if ($ownedCount > 0) {continue;}

                echo "<a href='guestListingDetails.php?ppID=$ppID' class='propertyLink'>";
                    echo "<div class='propertyCard'>";
                        echo "<img src='$ppImg1'>";
                        echo "<div class='propertyInfo'>";
                            echo "<div class='propertyHeader'>";
                                echo "<span class='propertyName'> $ppName </span><br>";
                                echo '<span class="propertyDes">' . substr($ppDes, 0, 50) . '...</span>';
                            echo "</div>";
                            echo '<span class="propertyPrice">$' . $ppPrice . ' / night</span>';
                        echo '</div>';
                        echo '<div class="propertyRating"><span>' . $ppStar . '</span> <span>(' . $reviewCount . ')</span></div>';
                    echo '</div>';
                echo '</a>';
            }
        ?>
    </div><br><br><br><br><br><br>

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
            // Date input functionality
            var today = new Date().toISOString().split('T')[0];
            document.getElementById('checkIn').setAttribute('min', today);
            
            document.getElementById('checkIn').addEventListener('change', function() {
                var checkInDate = new Date(this.value);
                checkInDate.setDate(checkInDate.getDate() + 1);
                var minCheckOutDate = checkInDate.toISOString().split('T')[0];
                document.getElementById('checkOut').setAttribute('min', minCheckOutDate);
            });
        };

        var todayDate = new Date().toISOString().split('T')[0];
        var checkInDate = document.getElementById('checkInDate');
        var checkOutDate = document.getElementById('checkOutDate');

        checkInDate.min = todayDate;
        checkOutDate.min = todayDate;

        checkInDate.addEventListener('change', function() {
            var minCheckOutDate = new Date(this.value);
            minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);
            checkOutDate.min = minCheckOutDate.toISOString().split('T')[0];
            checkOutDate.value = '';
        });
    </script>
</body>
</html>