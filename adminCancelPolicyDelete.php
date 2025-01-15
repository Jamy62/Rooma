<?php 
	include('dbConnect.php');
    session_start();

	$cnpID = $_GET['cnpID'];

    $delete = "DELETE FROM cancellationpolicies WHERE CancelPolicyID = $cnpID";
    $deleteQuery = mysqli_query($dbConnect, $delete);

    if ($deleteQuery) 
    {
        echo "<script>window.alert('Policy deleted.')</script>";
        echo "<script>window.location='adminCancelPolicy.php'</script>";
    }

    else
    {
        echo "<p>Something went wrong with deleting the policy.</p>";
    }