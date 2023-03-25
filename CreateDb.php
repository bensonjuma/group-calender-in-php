<?php
class CreateDb
{
    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $tablename;
    public $con;
    public $sql;

    // class constructor
    public function __construct($dbname, $tablename, $sql, $servername = "localhost", $username = "root", $password = "")
    {
        $this->dbname = $dbname;
        $this->tablename = $tablename;
        $this->sql = $sql;
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;

        // create connection
        $this->con = mysqli_connect($servername, $username, $password);

        // check connection
        if (!$this->con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // query to create the database
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";

        // execute query
        if (mysqli_query($this->con, $sql)) {
            $this->con = mysqli_connect($servername, $username, $password, $dbname);

            // execute the sql to create the table
            if (!mysqli_query($this->con, $this->sql)) {
                echo "Error creating table: " . mysqli_error($this->con);
            } else {
                // get created table name from the executed sql
                $tablename = explode(" ", $this->sql)[5];
                echo "<h3>Table '" . $tablename . "' is created or already exists.</h3>";
            }
        } else {
            return false;
        }
    }
    // get product from the database
    public function getData()
    {
        $sql = "SELECT * FROM $this->tablename";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
