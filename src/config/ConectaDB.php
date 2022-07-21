<?php

class ConectaDB
{

    public function conexaoDB(){

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "xml";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error)
            return die("Connection failed: " . $conn->connect_error);

        return $conn;
    }
}