<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='adminLogin.php'</script>";
    }

    $aID = $_SESSION['aID'];

    if (isset($_POST['btnAdd'])) 
	{
		$cNameIn = $_POST['txtcName'];

		/*Check existing countries*/
		$checkCountry = "SELECT * FROM countries WHERE CountryID = '$cNameIn'";
		$result = mysqli_query($dbConnect, $checkCountry);
		$count = mysqli_num_rows($result);

		if ($count > 0)
		{
			echo "<script>window.alert('This country already exists in the list. Please try another one.')</script>";
			echo "<script>window.location='adminCountry.php'</script>";
		}

		else
		{
			$insert = "INSERT INTO countries(CountryName)
					   VALUES('$cNameIn')";

			$finalInsert = mysqli_query($dbConnect, $insert);

			if ($finalInsert)
			{
				echo "<script>window.alert('New country added.')</script>";
                echo "<script>window.location='adminCountry.php'</script>";
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminStyle.css">
    <title>Countries</title>
</head>
<body>
    <div class="container">
        <h2>Supported countries</h2>

        <div class="addButton">
            <a href="#" class="btn btnAdd">Add country</a>
        </div>

        <div class="deleteButton">
            <a href="#" class="btn btnDelete">Delete</a>
        </div>

        <table class="countryTable">
            <tr>
                <th>Country ID</th>
                <th>Name</th>
            </tr>

            <?php 
            $countrySelect = "SELECT * FROM countries";
            $result = mysqli_query($dbConnect, $countrySelect);

            while ($array = mysqli_fetch_array($result)) 
            { 
                $cID = $array['CountryID'];
                $cName = $array['CountryName'];

                echo "<tr data-cid='$cID'>";
                echo "<td>$cID</td>";
                echo "<td>$cName</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <div id="addCountryPopup">
            <div class="popupContent">
                <span class="closePopup" id="cancelAddCountry">&times;</span>
                <h3>Add New Country</h3>

                <form id="Register" action="adminCountry.php" method="POST">
                    <label>Country name**</label>
                    <input class="registerInfo" type="text" name="txtcName" required><br><br>

                    <input class="registerSubmit" type="submit" name="btnAdd" value="Save">
                </form>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
        var countryTable = document.querySelector('.countryTable');
        var deleteButton = document.querySelector('.deleteButton');
        var deleteLink = document.querySelector('.btnDelete');
        var addButton = document.querySelector('.btnAdd');
        var addCountryPopup = document.getElementById('addCountryPopup');
        var cancelAddCountryButton = document.getElementById('cancelAddCountry');
        
        var selectedRow = null;

        function handleRowClick(event) {
            var clickedRow = event.target.closest('tr');
            
            if (clickedRow && clickedRow.dataset.cid) {
                if (selectedRow === clickedRow) {
                    clickedRow.classList.remove('selected');
                    deleteButton.classList.remove('visible');
                    selectedRow = null;
                } else {
                    if (selectedRow) {
                        selectedRow.classList.remove('selected');
                    }
                    
                    clickedRow.classList.add('selected');
                    deleteButton.classList.add('visible');
                    selectedRow = clickedRow;
                    
                    var countryID = clickedRow.dataset.cid;
                    deleteLink.href = 'adminCountryDelete.php?cID=' + countryID;
                }
            }
        }

        function handleDocumentClick(event) {
            if (!countryTable.contains(event.target) && !deleteButton.contains(event.target)) {
                if (selectedRow) {
                    selectedRow.classList.remove('selected');
                    selectedRow = null;
                }
                deleteButton.classList.remove('visible');
            }
        }

        function showAddCountryPopup() {
            addCountryPopup.style.display = 'block';
        }

        function hideAddCountryPopup() {
            addCountryPopup.style.display = 'none';
        }

        countryTable.addEventListener('click', handleRowClick);
        document.addEventListener('click', handleDocumentClick);
        addButton.addEventListener('click', showAddCountryPopup);
        cancelAddCountryButton.addEventListener('click', hideAddCountryPopup);

        window.onclick = function(event) {
            if (event.target == addCountryPopup) {
                hideAddCountryPopup();
            }
        }
    };
    </script>
</body>
</html>