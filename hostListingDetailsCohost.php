<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    $ppID = $_GET['ppID'];

    if (isset($_POST['btnAddCohost'])) 
    {
        $cohostEmail = $_POST['txtCohostEmail'];

        $checkUserQuery = "SELECT UserID FROM users WHERE UserEmail = '$cohostEmail'";
        $checkUserResult = mysqli_query($dbConnect, $checkUserQuery);

        if (mysqli_num_rows($checkUserResult) > 0) 
        {
            $cohostUser = mysqli_fetch_assoc($checkUserResult);
            $cohostID = $cohostUser['UserID'];

            $checkCohostQuery = "SELECT * FROM propertyhosts WHERE UserID = $cohostID AND PropertyID = $ppID";
            $checkCohostResult = mysqli_query($dbConnect, $checkCohostQuery);

            if (mysqli_num_rows($checkCohostResult) == 0) 
            {
                $checkCohostVerifyQuery = "SELECT * FROM users WHERE UserID = $cohostID AND HostVerificationStatus = 'Verified'";
                $checkCohostVerifyResult = mysqli_query($dbConnect, $checkCohostVerifyQuery);

                if (mysqli_num_rows($checkCohostVerifyResult) > 0)
                {
                    $cohostInsert = "INSERT INTO propertyhosts (UserID, PropertyID, HostType)
                                 VALUES ('$cohostID', '$ppID', 'Co-host')";
                    $cohostQuery = mysqli_query($dbConnect, $cohostInsert);

                    if ($cohostQuery) 
                    {
                        echo "<script>window.alert('Co-host added successfully.')</script>";
                        echo "<script>window.location='hostListing.php'</script>";
                    } 
                    
                    else 
                    {
                        echo "<script>window.alert('Error adding co-host. Please try again.')</script>";
                    }
                }

                else
                {
                    echo "<script>window.alert('This user hasn't verified their host mode yet.')</script>";
                }
            } 
            
            else 
            {
                echo "<script>window.alert('This user is already a co-host for this property.')</script>";
            }
        } 

        else 
        {
            echo "<script>window.alert('User with this email does not exist.')</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="roomaStyle.css">
    <title>Add Co-host</title>
</head>
<body id="RegisterBody"><br><br><br><br><br><br><br><br><br><br>
    <div id="registerContainer">
        <h2>Add Co-host</h2>
        <form id="Register" action="hostListingDetailsCohost.php" method="POST">
            <label class="registerLabel">Co-host Email**</label><br>
            <input class="registerInfo" type="email" id="txtCohostEmail" name="txtCohostEmail" placeholder="Enter co-host's email" required>
            <input class="registerSubmit" type="submit" name="btnAddCohost" value="Add Co-host">
        </form>
    </div>
</body>
</html>