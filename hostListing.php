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
                <a href="hostMain.php">Bookings</a>
                <a href="hostListing.php" class="active">Listings</a>
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

    <div class="hostListingSection">
        <h2>Listed Properties</h2>
        <div class="hostPropertyListings">
        <?php
            $query = "SELECT p.*, ph.*
                      FROM properties p, propertyhosts ph
                      WHERE p.PropertyID = ph.PropertyID
                      AND ph.UserID = $uID
                      ";
            $result = mysqli_query($dbConnect, $query);
            $resultCount = mysqli_num_rows($result);

            if ($resultCount > 0)
            {
                while ($array = mysqli_fetch_array($result)) 
                {
                    $ppID = $array['PropertyID'];
                    $ppImg1 = $array['PropertyImage1'];
                    $ppName = $array['PropertyName'];
                    $ppDes = $array['PropertyDescription'];
                    $ppPrice = $array['PropertyPrice'];

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

                    echo "<a href='hostListingDetails.php?ppID=$ppID' class='hostPropertyLink'>";
                        echo "<div class='hostPropertyCard'>";
                            echo "<img src='$ppImg1'>";
                            echo "<div class='hostPropertyInfo'>";
                                echo "<div class='HostPropertyHeander'>";
                                    echo "<span class='hostPropertyName'> $ppName </span><br>";
                                    echo '<span class="hostPropertyDes">' . $ppDes . '...</span>';
                                echo "</div>";
                                echo '<span class="hostPropertyPrice">$' . $ppPrice . ' / night</span>';
                            echo '</div>';
                            echo '<div class="hostPropertyRating"><span>' . $ppStar . '</span> <span>(' . $reviewCount . ')</span></div>';
                        echo '</div>';
                    echo '</a>';
                }
            }

            else {
                echo "<div class='noListing'>
                        <div class='icon'>🏠</div>
                        <p>You have no properties listed</p>
                    </div>";
            }
        ?>
        </div>
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
</body>
</html>
