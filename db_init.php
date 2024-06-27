<?php
// db_init.php
require 'db_conn.php'; 

$sqlFilePath = 'create_table.sql';

$sql = file_get_contents($sqlFilePath);

$pdo->exec($sql);
?>