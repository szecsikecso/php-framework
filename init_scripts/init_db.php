<?php

$table1 = "expense";
$table2 = "currency_value";

try {
    $db = new PDO("mysql:dbname=mydb;host=localhost", "root", "" );
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling

    $sql1 ="CREATE TABLE IF NOT EXISTS $table1(
     ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
     amount INT(3) UNSIGNED NOT NULL,
     amount_in_huf INT(3) UNSIGNED NOT NULL,
     currency VARCHAR(3) NOT NULL,
     description VARCHAR(250) NOT NULL);";
    $db->exec($sql1);
    print("Created $table1 Table.\n");

    $sql2 ="CREATE TABLE IF NOT EXISTS $table2(
     ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
     currency VARCHAR(3) NOT NULL,
     value_in_huf INT(3) UNSIGNED NOT NULL);";
    $db->exec($sql2);
    print("Created $table2 Table.\n");

    $sql = "INSERT INTO " . $table2 .
        " (" . 'currency' . ", " . 'value_in_huf' .
        ") VALUES (?,?)";
    $statement = $db->prepare($sql);
    $message = $statement->execute(['HUF', 1]);

} catch(PDOException $e) {
    echo $e->getMessage(); // Remove or change message in production code
}