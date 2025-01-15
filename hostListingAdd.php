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

    /*Check existing verification*/
	$checkVerification = "SELECT * FROM users
                          WHERE HostVerificationStatus = 'Verified'
                          AND UserID = $uID";
    $result = mysqli_query($dbConnect, $checkVerification);
    $count = mysqli_num_rows($result);

    if ($count == 0)
    {
        echo "<script>window.alert('Please verify this account to list a property.')</script>";
        echo "<script>window.location='hostMain.php'</script>";
    }

	if (isset($_POST['btnList'])) 
	{
		$ppName = $_POST['txtppName'];
		$ppAddress = $_POST['txtppAddress'];
		$ppCord = $_POST['txtppCoordinates'];
		$ppRoom = $_POST['txtppRoom'];
        $ppMaxGuest = $_POST['cboMaxGuest'];
        $ppDes = $_POST['txtppDescription'];
        $ppListingDate = date('Y-m-d');
        $ppStatus = 'Pending';
        $ppPrice = $_POST['txtppPrice'];
        $ciIDIn = $_POST['cbociID'];
        $cnpIDIn = $_POST['cbocnpID'];
        $hostType = 'Host';

        /*Property image 1 upload*/
		$pp1 = $_FILES['imgpp1']['name'];
		$folder = "profileImg/";
		$pp1FileName = $folder."_".$pp1;
		$copy = copy($_FILES['imgpp1']['tmp_name'], $pp1FileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload image.</p>";
		}

        /*Property image 2 upload*/
        $pp2 = $_FILES['imgpp2']['name'];
		$folder = "profileImg/";
		$pp2FileName = $folder."_".$pp2;
		$copy = copy($_FILES['imgpp2']['tmp_name'], $pp2FileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload image.</p>";
		}

        /*Property image 3 upload*/
		$pp3 = $_FILES['imgpp3']['name'];
		$folder = "profileImg/";
		$pp3FileName = $folder."_".$pp3;
		$copy = copy($_FILES['imgpp3']['tmp_name'], $pp3FileName);
		if (!$copy) 
		{
			echo "<p>An error occured. Cannot upload image.</p>";
		}

		$propertyInsert = "INSERT INTO properties (PropertyName, PropertyImage1, PropertyImage2, PropertyImage3, PropertyAddress, PropertyCoordinates, PropertyRooms, PropertyMaxGuest, PropertyDescription, PropertyListingDate, PropertyStatus, PropertyPrice, CityID, CancelPolicyID)
				           VALUES('$ppName','$pp1FileName', '$pp2FileName', '$pp3FileName', '$ppAddress', '$ppCord', '$ppRoom', '$ppMaxGuest', '$ppDes', '$ppListingDate', '$ppStatus', '$ppPrice', '$ciIDIn', '$cnpIDIn')";

		$propertyQuery = mysqli_query($dbConnect, $propertyInsert);

        if ($propertyQuery) 
        {
            $ppID = mysqli_insert_id($dbConnect);

            if(isset($_POST['rules'])) 
            {
                $selectedRules = is_array($_POST['rules']) ? $_POST['rules'] : array($_POST['rules']);
                
                foreach($selectedRules as $rID) {
                    $ruleInsert = "INSERT INTO propertyrules (PropertyID, RuleID) 
                                   VALUES ('$ppID', '$rID')";
                    $ruleQuery = mysqli_query($dbConnect, $ruleInsert);
                }
            }

            $hostInsert = "INSERT INTO propertyhosts (UserID, PropertyID, HostType)
                           VALUES ('$uID', '$ppID', '$hostType')";
            $hostQuery = mysqli_query($dbConnect, $hostInsert);

            echo "<script>window.alert('Property listing has been submitted. We will reach back to you once your listing is verified.')</script>";
            echo "<script>window.location='hostListing.php'</script>";
        }
	}

    if (isset($_GET['action'])) 
    {
        if ($_GET['action'] == 'getCities') 
        {
            $countryId = mysqli_real_escape_string($dbConnect, $_GET['countryId']);
            
            $citySelect = "SELECT * FROM cities WHERE CountryID = '$countryId'";
            $cityResult = mysqli_query($dbConnect, $citySelect);

            echo "<select name='cbociID' class='registerInfo'>";
            echo "<option value=''>Select a city</option>";
            while ($ciArray = mysqli_fetch_array($cityResult)) 
            { 
                $ciID = $ciArray['CityID'];
                $ciName = $ciArray['CityName'];
                echo "<option value='$ciID'>$ciName</option>";
            }
            echo "</select>";
            exit;
        }

        elseif ($_GET['action'] == 'getPolicyDescription') 
        {
            $policyId = mysqli_real_escape_string($dbConnect, $_GET['policyId']);
            
            $policySelect = "SELECT CancelPolicyDescription FROM cancellationpolicies WHERE CancelPolicyID = '$policyId'";
            $policyResult = mysqli_query($dbConnect, $policySelect);
            $policyArray = mysqli_fetch_array($policyResult);
            
            echo $policyArray['CancelPolicyDescription'];
            exit;
        }
    }
 ?>

 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" type="text/css" href="roomaStyle.css">
	<title></title>
</head>

<body id="RegisterBody">
	<div id="registerContainer">
	    <form id="Register" action="hostListingAdd.php" method="POST" enctype="multipart/form-data">

            <label>Country</label>
                <div>
                    <?php 
                        $countrySelect = "SELECT * FROM countries";
                        $countryResult = mysqli_query($dbConnect, $countrySelect);

                        echo "<select name='cbocID' id='countrySelect' class='registerInfo' onchange='updateCities(this.value)'>";
                            echo "<option value=''>Select a country</option>";
                            while ($cArray = mysqli_fetch_array($countryResult)) 
                            { 
                                $cID = $cArray['CountryID'];
                                $cName = $cArray['CountryName'];
                                echo "<option value='$cID'>$cName</option>";
                            }
                        echo "</select>";
                    ?>
                </div><br>

            <label>City</label>
            <div id="cityDropdown">
                <select name='cbociID' class='registerInfo' disabled>
                    <option>Select a country first</option>
                </select>
            </div><br>

            <label>Property image 1**</label>
            <input class="registerInfo" type="file" name="imgpp1" required/><br><br>
            <label>Property image 2**</label>
		 	<input class="registerInfo" type="file" name="imgpp2" required/><br><br>
            <label>Property image 3**</label>
		 	<input class="registerInfo" type="file" name="imgpp3" required/><br><br>
			<label>Property name**</label>
			<input class="registerInfo" type="text" name="txtppName" placeholder="Listing's name" required><br>
            <label>Address**</label>
			<input class="registerInfo" type="text" name="txtppAddress" placeholder="Listing's address" required><br>
            <label>Map Coordinates**</label>
			<input class="registerInfo" type="text" name="txtppCoordinates" placeholder="Listing's map coordinates" required><br>
            <label>Rooms**</label>
			<input class="registerInfo" type="text" name="txtppRoom" placeholder="The available rooms" required><br>
            <label>Max number of guests**</label>
			<select name="cboMaxGuest" class="registerInfo">
                <option value="">Select max number of guests</option>
                <option value="1">1 guest</option>
                <option value="2">2 guests</option>
                <option value="3">3 guests</option>
                <option value="4">4+ guests</option>
            </select>
            <label>Description**</label>
			<textarea name="txtppDescription" class="registerInfo" required></textarea>
            <label>Price per night**</label>
			<input class="registerInfo" type="number" name="txtppPrice" placeholder="$" required><br>

            <label for="ruleSelect">House Rules</label>
            <div id="ruleSelect" class="registerInfo">
                <?php 
                    $ruleSelect = "SELECT * FROM rules";
                    $ruleResult = mysqli_query($dbConnect, $ruleSelect);

                    while ($rArray = mysqli_fetch_array($ruleResult)) 
                    { 
                        $rID = $rArray['RuleID'];
                        $rIcon = $rArray['RuleIcon'];
                        $rName = $rArray['RuleName'];
                        echo "<div class='ruleOption'>";
                        echo "<input type='checkbox' id='$rID' name='rules[]' value='$rID'>";
                        echo "<label for='$rID'>$rIcon $rName</label>";
                        echo "</div>";
                    }
                ?>
            </div>

            <label>Cancellation policy</label>
            <div>
                <?php 
			        $cancelSelect = "SELECT * FROM cancellationpolicies";
			        $cancelResult = mysqli_query($dbConnect, $cancelSelect);

                    echo "<select name='cbocnpID' id='policySelect' class='registerInfo' onchange='showPolicyDescription(this.value)'>";
                        echo "<option value=''>Select a cancellation policy</option>";
                        while ($cnpArray = mysqli_fetch_array($cancelResult)) 
			            { 
			                $cnpID = $cnpArray['CancelPolicyID'];
                            $cnpName = $cnpArray['CancelPolicyName'];

			                echo "<option value='$cnpID'>$cnpName</option>";
                        }
                    echo "</select><br>";

                    echo "<textarea id='policyDescription' class='registerInfo' readonly></textarea>";
			    ?>
            </div>

	        <input class="registerSubmit" type="submit" name="btnList" value="Save">
	    </form>
    </div>

    <script>
        /*Rule select*/
        function toggleRuleSelection() {
            var checkbox = this;
            var label = checkbox.nextElementSibling;
            if (checkbox.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
        }

        var checkboxes = document.querySelectorAll('.ruleOption input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', toggleRuleSelection);
        }

        /*Cities Ajax*/
        function updateCities(countryId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("cityDropdown").innerHTML = this.responseText;
                }
            };
            xhr.open("GET", "hostListingAdd.php?action=getCities&countryId=" + countryId, true);
            xhr.send();
        }

        /*Policy description Ajax*/
        function showPolicyDescription(policyId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("policyDescription").value = this.responseText;
                }
            };
            xhr.open("GET", "hostListingAdd.php?action=getPolicyDescription&policyId=" + policyId, true);
            xhr.send();
        }
    </script>
</body>
</html>
</body>
</html>