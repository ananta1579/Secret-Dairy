<?php
session_start();

if(array_key_exists("content", $_POST)){
    include("connection.php");

    $query = "UPDATE secretdairy SET dairy = '".mysqli_real_escape_string($link, $_POST['content'])."' where id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

    if(mysqli_query($link, $query)) {
        echo "success";
    } else {
        echo "failed";
    }
}
?>