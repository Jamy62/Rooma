<?php 
	include('dbConnect.php');
    session_start();

	$rID = $_GET['rID'];

    $delete = "DELETE FROM rules WHERE RuleID = $rID";
    $deleteQuery = mysqli_query($dbConnect, $delete);

    if ($deleteQuery) 
    {
        echo "<script>window.alert('Rule deleted.')</script>";
        echo "<script>window.location='adminRule.php'</script>";
    }

    else
    {
        echo "<p>Something went wrong with deleting the rule.</p>";
    }
