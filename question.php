<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $question = $db->getQuestion();
    // Текст запроса
    if($question){
        echo $question;
    }else{
        echo "No question today;(";
    }
} else

?>