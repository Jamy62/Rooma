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
		$cnpNameIn = $_POST['txtcnpName'];
        $cnpDesIn = $_POST['txtcnpDescription'];

		/*Check existing policies*/
		$checkCancelPolicy = "SELECT * FROM cancellationpolicies WHERE CancelPolicyID = '$cnpNameIn'";
		$result = mysqli_query($dbConnect, $checkCancelPolicy);
		$count = mysqli_num_rows($result);

		if ($count > 0)
		{
			echo "<script>window.alert('This policy already exists in the list. Please try another one.')</script>";
			echo "<script>window.location='adminCancelPolicy.php'</script>";
		}

		else
		{
			$insert = "INSERT INTO cancellationpolicies(CancelPolicyName, CancelPolicyDescription)
					   VALUES('$cnpNameIn', '$cnpDesIn')";

			$finalInsert = mysqli_query($dbConnect, $insert);

			if ($finalInsert)
			{
				echo "<script>window.alert('New policy added.')</script>";
                echo "<script>window.location='adminCancelPolicy.php'</script>";
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
    <title>CancelPolicies</title>
</head>
<body>
    <div class="container">
        <h2>Cancellation Policies</h2>

        <div class="addButton">
            <a href="#" class="btn btnAdd">Add new policy</a>
        </div>

        <div class="deleteButton">
            <a href="#" class="btn btnDelete">Delete</a>
        </div>

        <table class="cancelPolicyTable">
            <tr>
                <th>Cancel Policy ID</th>
                <th>Name</th>
                <th>Description</th>
            </tr>

            <?php 
            $cancelPolicySelect = "SELECT * FROM cancellationpolicies";
            $result = mysqli_query($dbConnect, $cancelPolicySelect);

            while ($array = mysqli_fetch_array($result)) 
            { 
                $cnpID = $array['CancelPolicyID'];
                $cnpName = $array['CancelPolicyName'];
                $cnpDescription = $array['CancelPolicyDescription'];

                echo "<tr data-cnpid='$cnpID'>";
                echo "<td>$cnpID</td>";
                echo "<td>$cnpName</td>";
                echo "<td>$cnpDescription</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <div id="addCancelPolicyPopup">
            <div class="popupContent">
                <span class="closePopup" id="cancelAddCancelPolicy">&times;</span>
                <h3>Add New Cancellation Policy</h3>

                <form id="Register" action="adminCancelPolicy.php" method="POST">
                    <label>Policy name**</label>
                    <input class="registerInfo" type="text" name="txtcnpName" required><br>
                    <label>Description**</label>
                    <input class="registerInfo" type="text" name="txtcnpDescription" required><br>

                    <input class="registerSubmit" type="submit" name="btnAdd" value="Save">
                </form>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
        var cancelPolicyTable = document.querySelector('.cancelPolicyTable');
        var deleteButton = document.querySelector('.deleteButton');
        var deleteLink = document.querySelector('.btnDelete');
        var addButton = document.querySelector('.btnAdd');
        var addCancelPolicyPopup = document.getElementById('addCancelPolicyPopup');
        var cancelAddCancelPolicyButton = document.getElementById('cancelAddCancelPolicy');
        
        var selectedRow = null;

        function handleRowClick(event) {
            var clickedRow = event.target.closest('tr');
            
            if (clickedRow && clickedRow.dataset.cnpid) {
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
                    
                    var cancelPolicyID = clickedRow.dataset.cnpid;
                    deleteLink.href = 'adminCancelPolicyDelete.php?cnpID=' + cancelPolicyID;
                }
            }
        }

        function handleDocumentClick(event) {
            if (!cancelPolicyTable.contains(event.target) && !deleteButton.contains(event.target)) {
                if (selectedRow) {
                    selectedRow.classList.remove('selected');
                    selectedRow = null;
                }
                deleteButton.classList.remove('visible');
            }
        }

        function showAddCancelPolicyPopup() {
            addCancelPolicyPopup.style.display = 'block';
        }

        function hideAddCancelPolicyPopup() {
            addCancelPolicyPopup.style.display = 'none';
        }

        cancelPolicyTable.addEventListener('click', handleRowClick);
        document.addEventListener('click', handleDocumentClick);
        addButton.addEventListener('click', showAddCancelPolicyPopup);
        cancelAddCancelPolicyButton.addEventListener('click', hideAddCancelPolicyPopup);

        window.onclick = function(event) {
            if (event.target == addCancelPolicyPopup) {
                hideAddCancelPolicyPopup();
            }
        }
    };
    </script>
</body>
</html>