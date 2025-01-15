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
		$rNameIn = $_POST['txtrName'];
		$rIconIn = $_POST['txtrIcon'];
		$rDescriptionIn = $_POST['txtrDescription'];

		/*Check existing rules*/
		$checkRule = "SELECT * FROM rules WHERE RuleID = '$rNameIn'";
		$result = mysqli_query($dbConnect, $checkRule);
		$count = mysqli_num_rows($result);

		if ($count > 0)
		{
			echo "<script>window.alert('The entered rule already exists. Please try another one.')</script>";
			echo "<script>window.location='adminRule.php'</script>";
		}

		else
		{
			$insert = "INSERT INTO rules(RuleName, RuleIcon, RuleDescription)
					   VALUES('$rNameIn','$rIconIn', '$rDescriptionIn')";

			$finalInsert = mysqli_query($dbConnect, $insert);

			if ($finalInsert)
			{
				echo "<script>window.alert('New rule added.')</script>";
                echo "<script>window.location='adminRule.php'</script>";
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="adminStyle.css">
    <title>Rules</title>
</head>
<body>
    <div class="container">
        <h2>Rules</h2>

        <div class="addButton">
            <a href="#" class="btn btnAdd">Add rule</a>
        </div>

        <div class="deleteButton">
            <a href="#" class="btn btnDelete">Delete</a>
        </div>

        <table class="ruleTable">
            <tr>
                <th>Rule ID</th>
                <th>Icon</th>
                <th>Name</th>
                <th>Description</th>
            </tr>

            <?php 
            $ruleSelect = "SELECT * FROM rules";
            $result = mysqli_query($dbConnect, $ruleSelect);

            while ($array = mysqli_fetch_array($result)) 
            { 
                $rID = $array['RuleID'];
                $rName = $array['RuleName'];
                $rIcon = $array['RuleIcon'];
                $rDescription = $array['RuleDescription'];

                echo "<tr data-rid='$rID'>";
                echo "<td>$rID</td>";
                echo "<td id='iconCell'>$rIcon</td>";
                echo "<td>$rName</td>";
                echo "<td>$rDescription</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <div id="addRulePopup">
            <div class="popupContent">
                <span class="closePopup" id="cancelAddRule">&times;</span>
                <h3>Add New Rule</h3>

                <form id="Register" action="adminRule.php" method="POST">
                    <label>Rule name**</label>
                    <input class="registerInfo" type="text" name="txtrName" required><br>

                    <label>Icon**</label>
                    <input class="registerInfo" type="text" name="txtrIcon" required><br>

                    <label>Description</label>
                    <textarea name="txtrDescription" class="registerInfo"></textarea>

                    <input class="registerSubmit" type="submit" name="btnAdd" value="Save">
                </form>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
        var ruleTable = document.querySelector('.ruleTable');
        var deleteButton = document.querySelector('.deleteButton');
        var deleteLink = document.querySelector('.btnDelete');
        var addButton = document.querySelector('.btnAdd');
        var addRulePopup = document.getElementById('addRulePopup');
        var cancelAddRuleButton = document.getElementById('cancelAddRule');
        
        var selectedRow = null;

        function handleRowClick(event) {
            var clickedRow = event.target.closest('tr');
            
            if (clickedRow && clickedRow.dataset.rid) {
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
                    
                    var ruleID = clickedRow.dataset.rid;
                    deleteLink.href = 'adminRuleDelete.php?rID=' + ruleID;
                }
            }
        }

        function handleDocumentClick(event) {
            if (!ruleTable.contains(event.target) && !deleteButton.contains(event.target)) {
                if (selectedRow) {
                    selectedRow.classList.remove('selected');
                    selectedRow = null;
                }
                deleteButton.classList.remove('visible');
            }
        }

        function showAddRulePopup() {
            addRulePopup.style.display = 'block';
        }

        function hideAddRulePopup() {
            addRulePopup.style.display = 'none';
        }

        ruleTable.addEventListener('click', handleRowClick);
        document.addEventListener('click', handleDocumentClick);
        addButton.addEventListener('click', showAddRulePopup);
        cancelAddRuleButton.addEventListener('click', hideAddRulePopup);

        window.onclick = function(event) {
            if (event.target == addRulePopup) {
                hideAddRulePopup();
            }
        }
    };
    </script>
</body>
</html>