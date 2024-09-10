<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $username = $_GET['username'];
    $history = $db->getNotes($username);
} else
    echo "Error: Database connection";
?>