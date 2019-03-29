<?php

class Database
{
    public $conn;

    public static function getData()
    {
        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $server = $url["host"];
        $username = $url["user"];
        $password = $url["pass"];
        $db = substr($url["path"], 1);

        $conn = new mysqli($server, $username, $password, $db);
        return $conn;
    }

    public static function setupDatabase($conn)
    {
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "CREATE TABLE shopify (
                url VARCHAR(255) NOT NULL)";

        if ($conn->query($sql) === TRUE) {
            echo "<br> New table created successfully </br>";
        } else {
            echo "<script type= 'text/javascript'>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    }
}