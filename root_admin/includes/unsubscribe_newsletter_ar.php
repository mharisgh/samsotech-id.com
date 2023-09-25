<?php

error_reporting(0);
include 'connections.php';
echo $email=$_GET['mailid'];
$query="Delete from tbl_newsletter where email='".$email."'";
$result = execute($query);
    if ($result == 'Query Executed Successfully') {
        $_SESSION["err"] = "Newsletter Unsubscribed Successfully";
        header("Location:../../index.php");
    } else {
        $_SESSION["err"] = "Error Unsubscribing Newsletter .Please try again later";
    header("Location:../../index.php");
    }
    ?>