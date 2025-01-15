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
    $guest = 'guest';

    $credit = 1;
    $debit = 2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Your Payment Methods</title>
</head>
<body id="paymentBody">
    <div class="container">
        <h2>Your Payment Methods</h2>

        <div class="deleteButton">
            <a href="#" class="btn btnDelete">Delete</a>
        </div>

        <table class="paymentTable">
            <tr>
                <th>Payment No</th>
                <th>Brand</th>
                <th>Name on Card</th>
                <th>Expires On</th>
                <th>Type</th>
            </tr>

            <?php 
            $paymentSelect = "SELECT * FROM paymentinfo 
                              WHERE UserAccountType='Guest'
                              AND UserID = $uID
                              ORDER BY PaymentNo ASC";
            $result = mysqli_query($dbConnect, $paymentSelect);

            while ($array = mysqli_fetch_array($result)) { 
                $pID = $array['PaymentID'];
                $ptID = $array['PaymentTypeID'];
                $cardBrand = $array['CardBrand'];
                $paymentNo = $array['PaymentNo'];
                $cardHolderName = $array['CardHolderName'];
                $cardExpDate = $array['CardExpirationDate'];

                $ptSelect = "SELECT * FROM paymenttypes WHERE PaymentTypeID='$ptID'";
                $ptResult = mysqli_query($dbConnect, $ptSelect);
                $ptArray = mysqli_fetch_array($ptResult);
                $ptName = $ptArray['PaymentTypeName'];

                echo "<tr data-pid='$pID'>";
                echo "<td>$paymentNo</td>";
                echo "<td>$cardBrand</td>";
                echo "<td>$cardHolderName</td>";
                echo "<td>$cardExpDate</td>";
                echo "<td>$ptName</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <div class="addNew">
            <a href="userPaymentInfoAdd.php?ptID=<?php echo $credit ?>&at=<?php echo $guest ?>" class="btn">Add Credit Card</a>
            <a href="userPaymentInfoAdd.php?ptID=<?php echo $debit ?>&at=<?php echo $guest ?>" class="btn">Add Debit Card</a>
        </div>
    </div>

    <script>
        window.onload = function() {
            var table = document.querySelector('.paymentTable');
            var deleteButton = document.querySelector('.deleteButton');
            var deleteLink = document.querySelector('.btnDelete');
            var selectedRow = null;

            table.onclick = function(event) {
            var clickedRow = event.target.closest('tr');
                
             if (clickedRow && clickedRow.dataset.pid) {
                if (selectedRow === clickedRow) {
                    clickedRow.classList.remove('selected');
                    deleteButton.classList.remove('visible');
                    selectedRow = null;
                    }
                else {
                    if (selectedRow) {
                        selectedRow.classList.remove('selected');
                    }
                        
                    clickedRow.classList.add('selected');
                    deleteButton.classList.add('visible');
                    selectedRow = clickedRow;
                        
                    var paymentId = clickedRow.dataset.pid;
                    deleteLink.href = 'userPaymentInfoDelete.php?pID=' + paymentId + '&at=<?php echo $guest ?>';
                    }
                }
            };

            /* tap outside */
            document.onclick = function(event) {
                if (!table.contains(event.target) && !deleteButton.contains(event.target)) {
                    if (selectedRow) {
                        selectedRow.classList.remove('selected');
                        selectedRow = null;
                    }
                    deleteButton.classList.remove('visible');
                }
            };
        };
    </script>
</body>
</html>