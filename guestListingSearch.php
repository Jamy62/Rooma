<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    $location = $_POST['location'];
    $checkIn = $_POST['checkInDate'];
    $checkOut = $_POST['checkOutDate'];
    $guests = (int)$_POST['guests'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Search Results</title>
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

    <h2 class="explore">Search Results</h2>

    <div class="searchPropertyListings">
        <?php
            $search = "SELECT p.* 
            FROM properties p
            WHERE p.CityID IN (SELECT CityID FROM cities WHERE CityName LIKE '%$location%')
            AND p.PropertyStatus = 'Listed'
            AND p.PropertyMaxGuest >= '$guests'
            AND NOT EXISTS (
                SELECT * 
                FROM bookings b
                WHERE b.PropertyID = p.PropertyID
                AND b.BookingStatus != 'Cancelled'
                AND b.BookingStatus != 'Completed'
                AND (
                    (DATE('$checkIn') < DATE(b.BookingCheckOutDate) AND DATE('$checkOut') > DATE(b.BookingCheckInDate))
                    OR (DATE('$checkIn') BETWEEN DATE(b.BookingCheckInDate) AND DATE(b.BookingCheckOutDate))
                    OR (DATE('$checkOut') BETWEEN DATE(b.BookingCheckInDate) AND DATE(b.BookingCheckOutDate))
                )
            )";

            $searchResult = mysqli_query($dbConnect, $search);

            while ($searchArray = mysqli_fetch_array($searchResult)) 
            {
                $ppID = $searchArray['PropertyID'];
                $ppImg1 = $searchArray['PropertyImage1'];
                $ppName = $searchArray['PropertyName'];
                $ppDes = $searchArray['PropertyDescription'];
                $ppPrice = $searchArray['PropertyPrice'];

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
                    echo "<div class='searchPropertyCard'>";
                        echo "<img src='$ppImg1'>";
                        echo "<div class='propertyInfo'>";
                            echo "<div class='propertyHeander'>";
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
    </div>
</body>
</html>