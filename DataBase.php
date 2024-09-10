<?php
require "DataBaseConfig.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }


    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $username, $password)
    {
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where username = '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbusername = $row['username'];
            $dbpassword = $row['password'];
            if ($dbusername == $username && password_verify($password, $dbpassword)) {
                $login = true;
            } else $login = false;
        } else $login = false;

        return $login;
    }

    function signUp($table, $username, $email, $password)
    {
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $email = $this->prepareData($email);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->sql =
            "INSERT INTO " . $table . " (username, password, email) VALUES ('" . $username . "','" . $password . "','" . $email . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else 
        return false;
    }

    //my function
    function getQuestion(){
        $this->sql = "select question from questions ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $question = $row['question'];
            return $question;
        }else{
            return false;
        }
    }

    function getNotes($username){
        $username = $this->prepareData($username);

        $sql = "SELECT users.username, notes.date, questions.question, notes.answer
                FROM notes
                JOIN users ON notes.user_id = users.id
                JOIN questions ON notes.question_id = questions.id
                WHERE users.username = '" . $username . "'
                ORDER BY notes.date DESC";

        $this->sql = $sql;
        $result = mysqli_query($this->connect, $this->sql);
        if ($result->num_rows > 0) {
            // Вывод данных
            while ($row = $result->fetch_assoc()) {
                echo "Date: " . $row["date"] . "<br>";
                echo "Question: " . $row["question"] . "<br>";
                echo "Answer: " . $row["answer"] . "<br>";
                echo "<br>";
            }
            return true;
        } else {
            echo "You have no answers yet";
            return false;
        }
    }

    function makeNote($username, $question, $date, $answer)
    {
        //получили user_id
        $username = $this->prepareData($username);
        $user_id = null;
        $this->sql = "select id from users where username = '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $user_id = $row['id'];
        }
         //получили question_id
        $question = $this->prepareData($question);
        $question_id = null;
        $this->sql = "select id from questions where question = '" . $question . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $question_id = $row['id'];
        }

        $date = $this->prepareData($date);
        $answer = $this->prepareData($answer);
        $this->sql = "INSERT INTO notes (user_id, date, question_id, answer) VALUES ('" . $user_id . "','" . $date . "','" . $question_id . "','" . $answer . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else
            return false;
    }
}
?>
