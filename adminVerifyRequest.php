<?php 
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='adminLogin.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminStyle.css">
    <title>Document</title>
</head>
<body>
    <div class="tabs">
		<button class="tabButton" data-tab-target="tab1">Guest Verification Requests</button>
		<button class="tabButton" data-tab-target="tab2">Host Verification Requests</button>
        <button class="tabButton" data-tab-target="tab3">Verified Guests</button>
        <button class="tabButton" data-tab-target="tab4">Verified Hosts</button>

		<div id="tab1" class="tabContent">
			<h2 class="listingTitle scroll">Guest Verifications</h2><br>
            <div>
                <table class="listingTable">
                    <tr>
                        <th>VerificationID</th>
                        <th>LegalName</th>
                        <th>Selfie</th>
                        <th>IDNumber</th>
                        <th>IDFront</th>
                        <th>IDBack</th>
                        <th>IDType</th>
                        <th>CountryID</th>
                        <th>VerificationDate</th>
                        <th>VerificationStatus</th>
                        <th>UserID</th>
                        <th>Options</th>
                    </tr>

                    <?php 
                        $verificationSelect = "SELECT * FROM verifications WHERE UserAccountType='Guest'
                                               AND VerificationStatus != 'Accepted'";
                        $result = mysqli_query($dbConnect, $verificationSelect);

                        $count = mysqli_num_rows($result);


                        while ($array = mysqli_fetch_array($result)) 
                        {
                            $verificationID = $array['VerificationID'];
                            $legalName = $array['LegalName'];
                            $selfie = $array['Selfie'];
                            $idNumber = $array['IDNumber'];
                            $idFront = $array['IDFront'];
                            $idBack = $array['IDBack'];
                            $idType = $array['IDType'];
                            $cID = $array['CountryID'];
                            $verificationDate = $array['VerificationDate'];
                            $verificationStatus = $array['VerificationStatus'];
                            $uID = $array['UserID'];

                            $countrySelect = "SELECT * FROM countries WHERE CountryID='$cID'";
                            $countryResult = mysqli_query($dbConnect, $countrySelect);
                            $cArray = mysqli_fetch_array($countryResult);
                            $cName = $cArray['CountryName'];

                            echo "<tr>";
                                echo "<td>$verificationID</td>";
                                echo "<td>$legalName</td>";
                                echo "<td><img src='$selfie' width='100px' height='100px'></td>";
                                echo "<td>$idNumber</td>";
                                echo "<td><img src='$idFront' width='100px' height='100px'></td>";
                                echo "<td><img src='$idBack' width='100px' height='100px'></td>";
                                echo "<td>$idType</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$verificationDate</td>";
                                echo "<td>$verificationStatus</td>";
                                echo "<td>$uID</td>";
                                echo "<td>
                                <a href='adminVerifyAcceptg.php?vID=$verificationID &uID=$uID'>Accept</a>|
                                <a href='adminVerifyDenyg.php?vID=$verificationID'>Deny</a>|
                                </td>";
                            echo "</tr>";
                        }  
                    ?>
                </table>
            </div><br>
        </div>

        <div id="tab2" class="tabContent">
        <h2 class="listingTitle scroll">Host Verifications</h2><br>
            <div>
                <table class="listingTable">
                    <tr>
                        <th>VerificationID</th>
                        <th>LegalName</th>
                        <th>Selfie</th>
                        <th>IDNumber</th>
                        <th>IDFront</th>
                        <th>IDBack</th>
                        <th>IDType</th>
                        <th>CountryID</th>
                        <th>VerificationDate</th>
                        <th>VerificationStatus</th>
                        <th>UserID</th>
                        <th>Options</th>
                    </tr>

                    <?php 
                        $verificationSelect = "SELECT * FROM verifications WHERE UserAccountType='Host'
                                               AND VerificationStatus != 'Accepted'";
                        $result = mysqli_query($dbConnect, $verificationSelect);

                        $count = mysqli_num_rows($result);

                        while($array = mysqli_fetch_array($result))
                        { 
                            $verificationID = $array['VerificationID'];
                            $legalName = $array['LegalName'];
                            $selfie = $array['Selfie'];
                            $idNumber = $array['IDNumber'];
                            $idFront = $array['IDFront'];
                            $idBack = $array['IDBack'];
                            $idType = $array['IDType'];
                            $cID = $array['CountryID'];
                            $verificationDate = $array['VerificationDate'];
                            $verificationStatus = $array['VerificationStatus'];
                            $uID = $array['UserID'];

                            $countrySelect = "SELECT * FROM countries WHERE CountryID='$cID'";
                            $countryResult = mysqli_query($dbConnect, $countrySelect);
                            $cArray = mysqli_fetch_array($countryResult);
                            $cName = $cArray['CountryName'];

                            echo "<tr>";
                                echo "<td>$verificationID</td>";
                                echo "<td>$legalName</td>";
                                echo "<td><img src='$selfie' width='100px' height='100px'></td>";
                                echo "<td>$idNumber</td>";
                                echo "<td><img src='$idFront' width='100px' height='100px'></td>";
                                echo "<td><img src='$idBack' width='100px' height='100px'></td>";
                                echo "<td>$idType</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$verificationDate</td>";
                                echo "<td>$verificationStatus</td>";
                                echo "<td>$uID</td>";
                                echo "<td>
                                <a href='adminVerifyAccepth.php?vID=$verificationID &uID=$uID'>Accept</a>|
                                <a href='adminVerifyDenyh.php?vID=$verificationID'>Deny</a>|
                                </td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div><br>
        </div>

        <div id="tab3" class="tabContent">
			<h2 class="listingTitle scroll">Verified Guests</h2><br>
            <div>
                <table class="listingTable">
                    <tr>
                        <th>VerificationID</th>
                        <th>LegalName</th>
                        <th>Selfie</th>
                        <th>IDNumber</th>
                        <th>IDFront</th>
                        <th>IDBack</th>
                        <th>IDType</th>
                        <th>CountryID</th>
                        <th>VerificationDate</th>
                        <th>VerificationStatus</th>
                        <th>UserID</th>
                        <th>Options</th>
                    </tr>

                    <?php 
                        $verificationSelect = "SELECT * FROM verifications WHERE UserAccountType='Guest'
                                               AND VerificationStatus = 'Accepted'";
                        $result = mysqli_query($dbConnect, $verificationSelect);

                        $count = mysqli_num_rows($result);

                        while($array = mysqli_fetch_array($result))
                        { 
                            $verificationID = $array['VerificationID'];
                            $legalName = $array['LegalName'];
                            $selfie = $array['Selfie'];
                            $idNumber = $array['IDNumber'];
                            $idFront = $array['IDFront'];
                            $idBack = $array['IDBack'];
                            $idType = $array['IDType'];
                            $cID = $array['CountryID'];
                            $verificationDate = $array['VerificationDate'];
                            $verificationStatus = $array['VerificationStatus'];
                            $uID = $array['UserID'];

                            $countrySelect = "SELECT * FROM countries WHERE CountryID='$cID'";
                            $countryResult = mysqli_query($dbConnect, $countrySelect);
                            $cArray = mysqli_fetch_array($countryResult);
                            $cName = $cArray['CountryName'];

                            echo "<tr>";
                                echo "<td>$verificationID</td>";
                                echo "<td>$legalName</td>";
                                echo "<td><img src='$selfie' width='100px' height='100px'></td>";
                                echo "<td>$idNumber</td>";
                                echo "<td><img src='$idFront' width='100px' height='100px'></td>";
                                echo "<td><img src='$idBack' width='100px' height='100px'></td>";
                                echo "<td>$idType</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$verificationDate</td>";
                                echo "<td>$verificationStatus</td>";
                                echo "<td>$uID</td>";
                                echo "<td>
                                <a href='adminVerifyDenyg.php?vID=$verificationID'>Unverify</a>
                                </td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div><br>
        </div>

        <div id="tab4" class="tabContent">
        <h2 class="listingTitle scroll">Verified Hosts</h2><br>
            <div>
                <table class="listingTable">
                    <tr>
                        <th>VerificationID</th>
                        <th>LegalName</th>
                        <th>Selfie</th>
                        <th>IDNumber</th>
                        <th>IDFront</th>
                        <th>IDBack</th>
                        <th>IDType</th>
                        <th>CountryID</th>
                        <th>VerificationDate</th>
                        <th>VerificationStatus</th>
                        <th>UserID</th>
                        <th>Options</th>
                    </tr>

                    <?php 
                        $verificationSelect = "SELECT * FROM verifications WHERE UserAccountType='Host'
                                               AND VerificationStatus = 'Accepted'";
                        $result = mysqli_query($dbConnect, $verificationSelect);

                        $count = mysqli_num_rows($result);

                        while($array = mysqli_fetch_array($result))
                        { 
                            $verificationID = $array['VerificationID'];
                            $legalName = $array['LegalName'];
                            $selfie = $array['Selfie'];
                            $idNumber = $array['IDNumber'];
                            $idFront = $array['IDFront'];
                            $idBack = $array['IDBack'];
                            $idType = $array['IDType'];
                            $cID = $array['CountryID'];
                            $verificationDate = $array['VerificationDate'];
                            $verificationStatus = $array['VerificationStatus'];
                            $uID = $array['UserID'];

                            $countrySelect = "SELECT * FROM countries WHERE CountryID='$cID'";
                            $countryResult = mysqli_query($dbConnect, $countrySelect);
                            $cArray = mysqli_fetch_array($countryResult);
                            $cName = $cArray['CountryName'];

                            echo "<tr>";
                                echo "<td>$verificationID</td>";
                                echo "<td>$legalName</td>";
                                echo "<td><img src='$selfie' width='100px' height='100px'></td>";
                                echo "<td>$idNumber</td>";
                                echo "<td><img src='$idFront' width='100px' height='100px'></td>";
                                echo "<td><img src='$idBack' width='100px' height='100px'></td>";
                                echo "<td>$idType</td>";
                                echo "<td>$cName</td>";
                                echo "<td>$verificationDate</td>";
                                echo "<td>$verificationStatus</td>";
                                echo "<td>$uID</td>";
                                echo "<td>
                                <a href='adminVerifyDenyh.php?vID=$verificationID'>Unverify</a>
                                </td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div><br>
        </div>
    </div>

    <script>
		document.addEventListener('DOMContentLoaded', function() {
		    var tabButtons = document.querySelectorAll('.tabButton');
		    var tabContents = document.querySelectorAll('.tabContent');

		    tabButtons.forEach((button) => {
			    button.addEventListener('click', () => {
			    var target = button.dataset.tabTarget;
			    tabButtons.forEach((btn) => btn.classList.remove('active'));
			    button.classList.add('active');
			    tabContents.forEach((content) => content.classList.remove('active'));
			    document.getElementById(target).classList.add('active');
		        });
		    });

		    tabButtons[0].click();
		});
	</script>
</body>
</html>