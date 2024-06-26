<?php
// db_init.php
require 'db_conn.php'; 

$sqlFilePath = 'create_table.sql';

$sql = file_get_contents($sqlFilePath);

if ($sql === false) {
    die("Error reading SQL file");
}

// try {
$pdo->exec($sql);
    // echo "Table 'users' created successfully or already exists";
// } catch (PDOException $e) {
//     echo "Error creating table: " . $e->getMessage();
// }
?>