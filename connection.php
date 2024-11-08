<?php 

    include "credentials.php";

    // Database Connection 
    $connection = new mysqli('localhost', $user, $pw, $db);

    // Select all records from our table 
    $AllRecords = $connection->prepare("SELECT * FROM scp");
    $AllRecords->execute();
    $result = $AllRecords->get_result();
    
?>
