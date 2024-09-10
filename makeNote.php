<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $answer = $_POST['answer'];
    $date = $_POST['date'];
    $username = $_POST['username'];
    $question = $_POST['question'];
    if ($db->makeNote($username, $question, $date, $answer)) {
        echo "Note was made successfully";
    } else echo "Failed making a note";
} else echo "Error: Database connection";
?>