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

    public static function addToDatabase($url, $conn)
    {
        $sql = "INSERT INTO shopify (url)
        VALUES ('" . $url . "')";

        if ($conn->query($sql) === TRUE) {
            echo "<br> New records created successfully </br>";
        } else {
            echo "<script type= 'text/javascript'>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }

        $select = mysqli_query($conn, "SELECT DISTINCT * FROM shopify");
        echo "<br><br> Websites using this app : <br>";
        try {
            while ($row = $select->fetch_row()) {
                print_r($row[0]);
                print_r("<br>");
            }
        } catch (Exception $exception) {
            echo 'Error printing websites.';
        }
        $conn->close();

    }

    public static function dropTable($conn)
    {
        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $db = substr($url["path"], 1);

        $sql = "USE $db";
        $conn->query($sql);

        $sql2 = "DROP TABLE shopify";
        if ($conn->query($sql2) === true) {
            echo "<br>Table dropped.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        Config::deleteCookies();
    }
}
