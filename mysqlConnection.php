<?php
 
$conn = "";
 
try {
    $servername = "<ip>:<porta>";
    $dbname = "<nomeBD>";
    $username = "<user>";
    $password = "<senha>";
 
    $conn = new PDO(
        "mysql:host=$servername; dbname=$dbname;",
        $username, $password
    );
     
    $conn->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);            
     
} catch(PDOException $e) {
    echo "Connection failed: "
        . $e->getMessage();
}
 
?>
