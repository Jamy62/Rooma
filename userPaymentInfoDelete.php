<?php 
	include('dbConnect.php');
    session_start();

	$pID = $_GET['pID'];
    $at = $_GET['at'];
    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    $select = "SELECT PaymentNo FROM paymentinfo
               WHERE PaymentID = $pID";
    $selectResult = mysqli_query($dbConnect, $select);
    $array = mysqli_fetch_array($selectResult);
    $paymentNo = $array['PaymentNo'];

    $delete = "DELETE FROM paymentinfo WHERE PaymentID = $pID";
    $deleteQuery = mysqli_query($dbConnect, $delete);

	echo $fixPaymentNo = "UPDATE paymentinfo SET 
                     PaymentNo = PaymentNo - 1
                     WHERE PaymentNo > $paymentNo
                     AND UserID = $uID
                     AND UserAccountType = '$at'";
	$fixQuery = mysqli_query($dbConnect, $fixPaymentNo);

    if ($at = 'guest')
    {
        if ($paymentNo and $delete and $fixQuery) 
        {
            echo "<script>window.alert('Payment method deleted.')</script>";
            echo "<script>window.location='guestPaymentInfo.php'</script>";
        }

        else
        {
            echo "<p>Something went wrong with deleting the payment method.</p>";
        }
    }

    else if ($at = 'host')
    {
        if ($selectQuery and $delete and $fixQuery) 
        {
            echo "<script>window.alert('Payment method deleted.')</script>";
            echo "<script>window.location='hostPaymentInfo.php'</script>";
        }

        else
        {
            echo "<p>Something went wrong with deleting the payment method.</p>";
        }
    }
